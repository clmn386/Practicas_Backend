<?php 
   require '../../includes/app.php';
        $auth = autenticado();
        if (!$auth){
            header('location: /');
        }

    // Validacion de URL que sea id correcto
    $id = $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if(!$id) {
        header('Location: /admin');
    }

    /* Importar Conexion */
    $db = conectarDB();

    //Obtener los datos de "propiedad" en Base de Datos por id
    $consulta = "SELECT * FROM propiedades WHERE id = ${id}";
    $resultado = mysqli_query($db,$consulta);
    $propiedad = mysqli_fetch_assoc($resultado);

/*  echo"<pre>";
    var_dump($propiedad);
    echo"</pre>"; */

    // Consulta para obtener VEndedores
    $consulta = "SELECT * FROM vendedores";
    $resultado = mysqli_query($db, $consulta);
    

    // ARREGLO MENSAJES DE ERRORES
    $errores = [];


    $titulo = $propiedad['titulo'];
    $precio = $propiedad['precio'];
    $descripcion = $propiedad['descripcion'];
    $habitaciones = $propiedad['habitaciones'];
    $wc = $propiedad['wc'];
    $estacionamientos = $propiedad['estacionamientos'];
    $vendedorId = $propiedad['vendedorId'];
    $imagenPropiedad = $propiedad['imagen'];

    // EJECUCION DEL CODIGO DESPUES QUE EL USUARIO ENVIA EL FORMULARIO
    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        // todo este codigo sigue funcionando para actualizar porque seguimos leyendo del $_POST.
        $titulo = mysqli_real_escape_string( $db, $_POST['titulo'] );
        $precio = mysqli_real_escape_string( $db, $_POST['precio'] );
        $descripcion = mysqli_real_escape_string( $db, $_POST['descripcion'] );
        $habitaciones = mysqli_real_escape_string( $db, $_POST['habitaciones'] );
        $wc = mysqli_real_escape_string( $db, $_POST['wc'] );
        $estacionamientos = mysqli_real_escape_string( $db, $_POST['estacionamientos'] );
        $vendedorId = mysqli_real_escape_string( $db, $_POST['vendedor'] );
        $creado = date('Y/m/d');
        
        // asignar files hacia una varieble
        $imagen =  $_FILES['imagen']; 

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
       
        /* Validar por tamaño */
        $medida =  1000 * 1000;
        if($imagen['size'] > $medida){
        $errores[] = 'tamaño muy grande';
        }

        //Validar Arreglo errores - Vacio - 
        if (empty($errores)){
            
            $carpetaImagenes = '../../imagenes/';
            if(!is_dir($carpetaImagenes)){
                mkdir($carpetaImagenes);
            }
            
            if($imagen['name']) {
                //Eliminar la imagen Previa
                unlink($carpetaImagenes . $propiedad['imagen']);

                // Detectar y agregar png/jpg en final de string $nombreImagen
                $final = '';
                if ($imagen['type'] === 'image/png') {
                    $final = '.png';
                }elseif ($imagen['type'] === 'image/jpeg'){
                    $final ='.jpeg';
                }else{
                    $final ='.jpg';}

                //Generar nombre unico
                $nombreImagen = md5( uniqid( rand(), true) ). $final;
                    
            } else {
                $nombreImagen = $propiedad['imagen'];
            }

            //Subir la imagen
            move_uploaded_file($imagen['tmp_name'], $carpetaImagenes. $nombreImagen );
 
            //Actualizar en la Base de Datos
            $query = "UPDATE propiedades SET titulo = '${titulo}', precio = '${precio}', imagen = '${nombreImagen}', descripcion = '${descripcion}', habitaciones = ${habitaciones}, wc = ${wc}, estacionamientos = ${estacionamientos}, vendedorId = ${vendedorId} WHERE id =${id} ";


            $resultado = mysqli_query($db,$query);
            if($resultado){
                //redireccionar a la usuario
                header('Location: /admin?resultado=2');
            }
        }
    }
    //HEADER TEMPLATE
    incluirTemplate('header');

?>
    <main class="contenedor seccion">
        <h1>Actualizar Propiedades</h1>

        <a href="/admin" class="boton boton-verde">Volver</a>
        <?php foreach($errores as $error): ?>
        <div class="alerta error">
        <?php echo $error ?>
        </div>
        <?php endforeach; ?>

        <form action="" class="formulario" method="POST" enctype="multipart/form-data">
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

                <input 
                type="file" 
                id="imagen" 
                name="imagen" 
                accept="image/jpeg, image/png" name="imagen"> 

                <img src="/imagenes/<?php echo $imagenPropiedad ?>" class="imagen-small">

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

            <input type="submit" value="Actualizar Propiedad" class="boton boton-verde">

        </form> 

    </main>

<?php //FOOTER
    incluirTemplate('footer');
?>