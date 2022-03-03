<?php 

    $id = $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

/*     if(!$id) {
        header('Location: /anuncios.php');
    } */
    //  Importar BD
    require __DIR__.'/includes/config/database.php';
    $db = conectarDB();
    //  Query
    $query =  "SELECT * FROM propiedades WHERE id = ${id}";
    //  Consulta BD
    $consulta = mysqli_query($db, $query);
    $propiedad = mysqli_fetch_assoc($consulta);

    require 'includes/funciones.php';
    incluirTemplate('header');
?>

<?php
        $nueva_cadena = substr($propiedad['descripcion'],0 ,100)
        
?>

    <main class="contenedor seccion">
        <h1>Titulo Página</h1>


        <p><?php echo $propiedad['descripcion'] ?></p>

        <br>
        <p><?php echo $nueva_cadena ?></p>

    </main>

<?php 
    incluirTemplate('footer');
?>
