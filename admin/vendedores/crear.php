<?php 
require '../../includes/app.php';

autenticado();
use App\Vendedor;

$vendedor = new Vendedor;

$errores = Vendedor::getErrores();

if($_SERVER['REQUEST_METHOD']==='POST') {
    // Nueva Instacia
    $vendedor = new Vendedor($_POST['vendedor']);

    //Validar que no hay campos vacios
    $errores = $vendedor->validar();
    
    //no hay errores
    if(empty($errores)){
        $vendedor->guardar();
    }

}
    //HEADER TEMPLATE
    incluirTemplate('header');

?>
    <main class="contenedor seccion">
        <h1>Registrar Vendedor(a)</h1>

        <a href="/admin" class="boton boton-verde">Volver</a>
        <?php foreach($errores as $error): ?>
        <div class="alerta error">
        <?php echo $error ?>
        </div>
        <?php endforeach; ?>

        <form action="" class="formulario" method="POST" action="/admin/vendedores/crear.php" enctype="multipart/form-data">
           <?php include '../../includes/templates/formulario_vendedores.php' ?>

            <input type="submit" value="Registrar Vendedor(a)" class="boton boton-verde">

        </form> 

    </main>

<?php //FOOTER
    incluirTemplate('footer');
?>