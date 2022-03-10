<?php 

use App\Propiedad;
use Intervention\Image\ImageManagerStatic as image;
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

    // Consulta para obtener VEndedores
    $consulta = "SELECT * FROM vendedores";
    $resultado = mysqli_query($db, $consulta);
    

    // ARREGLO MENSAJES DE ERRORES
    $errores = Propiedad::getErrores();

   // EJECUCION DEL CODIGO DESPUES QUE EL USUARIO ENVIA EL FORMULARIO
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        
        $args = $_POST['propiedad'];
        
        $propiedad->sincronizar($args);
        //Validacion
        $ignore_img=true;
        $errores = $propiedad->validar($ignore_img);
        
        //Subida de Archivos
        $formato = $propiedad->FormatoImagen();
        
        $nombreImagen = md5( uniqid( rand(), true) ). $formato;


        //Validacion de imagen y archivar en carpeta
        if ($_FILES['propiedad']['tmp_name']['imagen']) {
            $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800,600);
            $propiedad->setImagen($nombreImagen);

            if (empty($errores)){
                $image->save(CARPETAS_IMAGENES . $nombreImagen);
                $propiedad->guardar();
            }
        }

        //Validar Arreglo errores - Vacio - 

        if (empty($errores)){
            
            $propiedad->guardar();
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