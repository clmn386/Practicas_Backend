<?php 
        require '../../includes/funciones.php';
        $auth = autenticado();
        if (!$auth){
            header('location: /');
        }

    /* Importar Conexion */
    require '../../includes/config/database.php';
    $db = conectarDB();

    /* Escribir Query */
    $consulta = "SELECT * FROM vendedores";

    /* Consultar BD */
    $resultado = mysqli_query($db, $consulta);
    

    // ARREGLO MENSAJES DE ERRORES
    $errores = [];

    $titulo = '';
    $precio = '';
    $descripcion = '';
    $habitaciones = '';
    $wc = '';
    $estacionamientos = '';
    $vendedorId = '';

    // EJECUCION DEL CODIGO DESPUES QUE EL USUARIO ENVIA EL FORMULARIO
    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        $titulo = mysqli_real_escape_string( $db, $_POST['titulo'] );
        $precio = mysqli_real_escape_string( $db, $_POST['precio'] );
        $descripcion = mysqli_real_escape_string( $db, $_POST['descripcion'] );
        $habitaciones = mysqli_real_escape_string( $db, $_POST['habitaciones'] );
        $wc = mysqli_real_escape_string( $db, $_POST['wc'] );
        $estacionamientos = mysqli_real_escape_string( $db, $_POST['estacionamientos'] );
        $vendedorId = mysqli_real_escape_string( $db, $_POST['vendedor'] );
        $creado = date('Y/m/d');
        $imagen =  $_FILES['imagen'];
        // asignar files hacia una varieble

        if(!$titulo){
            $errores[] =  'falta colocar titutlo';
        }

        if(!$precio){            
            $errores[] = 'falta colocar precio';
        }
        
        if( strlen($descripcion) < 50){
            $errores[] = 'necesario mas de 50 caracteres en descripcion obligatoria';
        }

        if(!$habitaciones){            
            $errores[] = 'faltan numero de habitaciones';
        }

        if(!$wc){            
            $errores[] = 'faltan numero de baños';
        }
    
        if(!$estacionamientos){            
            $errores[] = 'faltan numero de estacionamiento';
        }
                
        if(!$vendedorId){            
            $errores[] = 'elige un vendedor';
        }

        if(!$imagen['name'] || $imagen['error']){
            $errores[] = 'la imagen es obligatoria';
        }
        
        /* Validar por tamaño */
        $medida =  1000 * 1000;

        if($imagen['size'] > $medida){
            $errores[] = 'tamaño muy grande';
        }

        //Validar Arreglo errores - Vacio - 
        if (empty($errores)){

            /* Subida de Archivos */
            $carpetaImagenes = '../../imagenes/';
            if(!is_dir($carpetaImagenes)){
                mkdir($carpetaImagenes);
            }

            $final = '';
                if ($imagen['type'] === 'image/png') {
                    $final = '.png';
                }else{
                    $final ='.jpg';
                }
            
            $nombreImagen = md5( uniqid( rand(), true) ). $final;

            move_uploaded_file($imagen['tmp_name'], $carpetaImagenes. $nombreImagen );
 
            //Insertar en la Base de Datos
            $query = "INSERT INTO propiedades (titulo, precio, imagen, descripcion, habitaciones, wc, estacionamientos, creado, vendedorId) VALUES ('$titulo','$precio','$nombreImagen','$descripcion','$habitaciones','$wc','$estacionamientos','$creado','$vendedorId') ";

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

                <select name="vendedor">
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