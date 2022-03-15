<?php 
require '../../includes/app.php';

autenticado();
use App\Vendedor;

// Validar que sea un ID valido
$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);

if(!$id) {
    header('location: /admin');
}
//Obtener arreglo de vendedor
$vendedor = Vendedor::find($id);

//Arreglo con mensaje de errores
$errores = Vendedor::getErrores();


if($_SERVER['REQUEST_METHOD']==='POST') {
    // asignar valor
    $args = $_POST['vendedor'];
    //sincronizar Obj en memoria con lo que usuario escribio
    $vendedor->sincronizar($args);
    // validacion
    $errores = $vendedor->validar();

    if(empty($errores)){
        $vendedor->guardar();
    }
}
    //HEADER TEMPLATE
    incluirTemplate('header');

?>
    <main class="contenedor seccion">
        <h1>Actualizar Vendedor(a)</h1>

        <a href="/admin" class="boton boton-verde">Volver</a>
        <?php foreach($errores as $error): ?>
        <div class="alerta error">
        <?php echo $error ?>
        </div>
        <?php endforeach; ?>

        <form action="" class="formulario" method="POST">
           <?php include '../../includes/templates/formulario_vendedores.php' ?>

            <input type="submit" value="Actualizar Vendedor(a)" class="boton boton-verde">

        </form> 

    </main>

<?php //FOOTER
    incluirTemplate('footer');
?>