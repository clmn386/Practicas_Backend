<?php 
    $id = $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if(!$id) {
        header('Location: /anuncios.php');
    }
    require 'includes/app.php';
    //  Importar BD

    $db = conectarDB();
    //  Query
    $query =  "SELECT * FROM propiedades WHERE id = ${id}";
    //  Consulta BD
    $consulta = mysqli_query($db, $query);
    $propiedad = mysqli_fetch_assoc($consulta);

    incluirTemplate('header');
?>


    <main class="contenedor seccion contenido-centrado">
        <h1><?php echo $propiedad['titulo'] ?></h1>

        <picture>
            <!-- <source srcset="build/img/destacada.webp" type="image/webp">
            <source srcset="build/img/destacada.jpg" type="image/jpeg"> -->
            <img loading="lazy" src="/imagenes/<?php echo $propiedad['imagen'] ?>" alt="anuncio">
        </picture>

        <div class="resumen-propiedad">
            <p class="precio">$ <?php echo $propiedad['precio'] ?></p>
            <ul class="iconos-caracteristicas">
                <li>
                    <img class="icono" loading="lazy" src="build/img/icono_wc.svg" alt="icono wc">
                    <p><?php echo $propiedad['wc'] ?></p>
                </li>
                <li>
                    <img class="icono" loading="lazy" src="build/img/icono_estacionamiento.svg" alt="icono estacionamiento">
                    <p><?php echo $propiedad['estacionamientos'] ?></p>
                </li>
                <li>
                    <img class="icono"  loading="lazy" src="build/img/icono_dormitorio.svg" alt="icono habitaciones">
                    <p><?php echo $propiedad['habitaciones'] ?></p>
                </li>
            </ul>

            <p><?php echo $propiedad['descripcion'] ?></p>

        </div>
    </main>
    
<?php 
    mysqli_close($db);
    incluirTemplate('footer');
?>