<?php 
    require 'includes/funciones.php';
    incluirTemplate('header');
?>

    <main class="contenedor seccion">

        <h2>Casas y Depas en Venta</h2>

    <?php   
        $limite = 9;

        include 'includes/templates/anuncio.php';
    ?>
        </div> <!--.contenedor-anuncios-->
    </main>

<?php 
    incluirTemplate('footer');
?>