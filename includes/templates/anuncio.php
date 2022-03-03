<?php   
        //Importar BD
        require __DIR__.'/../config/database.php';
        $db = conectarDB();
        
        //Escribir Query
         $query = "SELECT * FROM propiedades LIMIT ${limite}"; 
        
        //Consultar
        $resultado = mysqli_query($db, $query);


?>

<div class="contenedor-anuncios">
    <?php while($propiedad = mysqli_fetch_assoc($resultado)): ?>
        <div class="anuncio">
    
        <?php $reseña = substr($propiedad['descripcion'],0 ,100); ?>
            <img loading="lazy" src="/imagenes/<?php echo $propiedad['imagen'] ?>" alt="anuncio">


            <div class="contenido-anuncio">
                <div class="contenido-anuncio_titulo">
                    <h3><?php echo $propiedad['titulo'] ?></h3>
                </div>

                <div class="contenido-anuncio_reseña">
                    <p><?php echo $reseña ?><br> <a href=""> mas...</a></p>
                </div>

                <div class="contenido-anuncio_varios">
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
                            <img class="icono" loading="lazy" src="build/img/icono_dormitorio.svg" alt="icono habitaciones">
                            <p><?php echo $propiedad['habitaciones'] ?></p>
                        </li>
                    </ul>

                    <a href="/anuncio.php?id=<?php echo $propiedad['id'] ?>" class="boton-amarillo-block">
                        <?php echo $propiedad['id'] ?>
                    </a>
                </div>

            </div><!--.contenido-anuncio-->
        </div><!--anuncio-->
    <?php endwhile; ?>
</div> <!--.contenedor-anuncios-->

<?php 
    //cerrar BD
    mysqli_close($db);
    
?>