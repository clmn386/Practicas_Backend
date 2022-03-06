<?php 
require '../../includes/app.php';

use App\Propiedad;

autenticado();

/* Importar Conexion */
$db = conectarDB();

    /* Escribir Query */
    $consulta = "SELECT * FROM vendedores";

    /* Consultar BD */
    $resultado = mysqli_query($db, $consulta);
    
    // ARREGLO MENSAJES DE ERRORES
    $errores = Propiedad::getErrores();

    
    
    $titulo = '';
    $precio = '';
    $descripcion = '';
    $habitaciones = '';
    $wc = '';
    $estacionamientos = '';
    $vendedorId = '';
    // EJECUCION DEL CODIGO DESPUES QUE EL USUARIO ENVIA EL FORMULARIO
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $Archivo = $_FILES['imagen']['type'];
        $validar = '';
        
        $propiedad = new Propiedad($_POST); 
        
        $errores = $propiedad->validar();
        
        $formato = $propiedad->FormatoImagen();

    
        //Validar Arreglo errores - Vacio - 
        if (empty($errores)){
 
 
            $propiedad->guardar();
 
            $imagen =  $_FILES['imagen'];


            /* Subida de Archivos */
            $carpetaImagenes = '../../imagenes/';
            if(!is_dir($carpetaImagenes)){
                mkdir($carpetaImagenes);
            }

            $nombreImagen = md5( uniqid( rand(), true) ). $formato;

            move_uploaded_file($imagen['tmp_name'], $carpetaImagenes. $nombreImagen );
 
    
            $resultado = mysqli_query($db,$query);
            if($resultado){
                header('Location: /admin?resultado=1');
            }
        }
    }
    //HEADER TEMPLATE
    incluirTemplate('header');

?>
    <main class="contenedor seccion">
        <h1>Crear</h1>

        <a href="/admin" class="boton boton-verde">Volver</a>
        <?php foreach($errores as $error): ?>
        <div class="alerta error">
        <?php echo $error ?>
        </div>
        <?php endforeach; ?>

        <form action="" class="formulario" method="POST" action="/admin/propiedades/crear.php" enctype="multipart/form-data">
            <fieldset>
                <legend>Informacion General</legend>

                <label for="titulo">Titulo:</label>
                <input 
                type="text" 
                id="titulo" 
                value="<?php echo $titulo; ?>" 
                name="titulo" 
                placeholder="Titulo Propiedad"> <!---name premite ver los valores del input.-->

                <label for="precio">Precio:</label>
                <input 
                type="number" 
                id="precio" 
                value="<?php echo $precio; ?>"
                name="precio" 
                placeholder="Precio Propiedad" 
                min="1" 
                max="9999999999">

                <label for="imagen">Imagen:</label>
                <input 
                type="file" 
                id="imagen" 
                name="imagen" 
                accept="image/jpeg, image/png" name="imagen">

                <label for="descripcion">Descripcion</label>
                <textarea id="descripcion" name="descripcion"><?php echo $descripcion;?></textarea>
            </fieldset>

            <fieldset>
                <legend>Información Propiedad</legend>

                <label for="habitaciones">Habitaciones:</label>
                <input 
                type="number" 
                id="habitaciones" 
                value="<?php echo $habitaciones; ?>" 
                name="habitaciones" 
                placeholder="ej: 3"
                 min="1" 
                 max="9">

                <label for="wc">Baños:</label>
                <input 
                type="number" 
                id="wc" 
                value="<?php echo $wc; ?>" 
                name="wc" 
                placeholder="ej: 3" 
                min="1" 
                max="9">

                <label for="estacionamientos">Estacionamiento:</label>
                <input 
                type="number" 
                id="estacionamientos" 
                value="<?php echo $estacionamientos; ?>" 
                name="estacionamientos" 
                placeholder="ej: 3" 
                min="1" 
                max="9">
            </fieldset>

            <fieldset>
                <legend>Vendedor</legend>

                <select name="vendedorId">
                    <option value="">--Seleccione--</option>
                    <!-- <option value="1">Carlos</option> -->

                    <?php while($vendedor = mysqli_fetch_assoc($resultado) ) : ?> 
                        <option <?php echo $vendedorId ===  $vendedor['id'] ? 'selected' : ''; ?> value="<?php echo $vendedor['id']; ?>"> <?php echo $vendedor['nombre'] . " " . $vendedor['apellido']; ?> </option>
                    <?php endwhile; ?>

                </select>
            </fieldset>

            <input type="submit" value="Crear Propiedad" class="boton boton-verde">

        </form> 

    </main>

<?php //FOOTER
    incluirTemplate('footer');
?>