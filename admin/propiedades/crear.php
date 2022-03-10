<?php 
require '../../includes/app.php';

use App\Propiedad;
use Intervention\Image\ImageManagerStatic as image;

autenticado();

/* Importar Conexion */
$db = conectarDB();

$propiedad = new Propiedad;
    /* Escribir Query */
    $consulta = "SELECT * FROM vendedores";

    /* Consultar BD */
    $resultado = mysqli_query($db, $consulta);
    
    // ARREGLO MENSAJES DE ERRORES
    $errores = Propiedad::getErrores();

    // EJECUCION DEL CODIGO DESPUES QUE EL USUARIO ENVIA EL FORMULARIO
    if($_SERVER['REQUEST_METHOD'] === 'POST'){

       /* Crea una nueva instancia */
         $propiedad = new Propiedad($_POST['propiedad']); 
/*         debuggear($propiedad); */
        /* Genera el nombre unico */

        $formato = $propiedad->FormatoImagen();
        $nombreImagen = md5( uniqid( rand(), true) ). $formato;

        /* Setear la imagen */
        if($_FILES['propiedad']['tmp_name']['imagen']){
            $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800,600);
            $propiedad->setImagen($nombreImagen);
        }

        /* validar la imagen  */
        $errores = $propiedad->validar();
        

        //Validar Arreglo errores - Vacio - 
        if (empty($errores)){
 
            /* crear carpeta */
            if(!is_dir(CARPETAS_IMAGENES)){
                mkdir(CARPETAS_IMAGENES);
            }
            
            //Guardar imagen en servidor
            $image->save(CARPETAS_IMAGENES . $nombreImagen);
            
            //Guarda en la BD
            $resultado = $propiedad->guardar();

            //Mostrar resultado


            //Redireccionar al usuario.
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
           <?php include '../../includes/templates/formulario_prodiedades.php' ?>

            <input type="submit" value="Crear Propiedad" class="boton boton-verde">

        </form> 

    </main>

<?php //FOOTER
    incluirTemplate('footer');
?>