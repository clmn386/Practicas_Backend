<?php 

use App\Propiedad;
require '../../includes/app.php';

autenticado();

    // Validacion de URL que sea id correcto
    $id = $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if(!$id) {
        header('Location: /admin');
    }

    //Obtener los datos de "propiedad" en Base de Datos por id
    $propiedad = Propiedad::find($id);
/*     debuggear($propiedad); */
    // Consulta para obtener VEndedores
    $consulta = "SELECT * FROM vendedores";
    $resultado = mysqli_query($db, $consulta);
    

    // ARREGLO MENSAJES DE ERRORES
    $errores = [];

    // EJECUCION DEL CODIGO DESPUES QUE EL USUARIO ENVIA EL FORMULARIO
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        debuggear($_POST);
        $args = [];
        $args['titulo'] = $_POST['titulo'] ?? null;
        $args['precio'] = $_POST['precio'] ?? null;
        $args['imagen'] = $_POST['imagen'] ?? null;
        $args['descripcion'] = $_POST['descripcion'] ?? null;
        $args['habitaciones'] = $_POST['habitaciones'] ?? null;
        $args['wc'] = $_POST['wc'] ?? null;
        $args['estacionamientos'] = $_POST['estacionamientos'] ?? null;
        $args['vendedorId'] = $_POST['vendedorId'] ?? null;

        $propiedad->sincronizar($args);

        debuggear($propiedad);        
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

                <?php include '../../includes/templates/formulario_prodiedades.php'; ?>
            
            <input type="submit" value="Actualizar Propiedad" class="boton boton-verde">

        </form> 

    </main>

<?php //FOOTER
    incluirTemplate('footer');
?>