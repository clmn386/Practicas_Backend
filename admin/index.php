<?php 
require '../includes/app.php';

autenticado();
    use App\Propiedad;

    // implementar un metodo para obtenrer todas la propiedades
    $propiedades = Propiedad::all();

    
    // Muestra mensaje condicional 
    $resultado = $_GET['resultado'] ?? null; 


    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if($id) {

            $propiedad = Propiedad::find($id);
            $propiedad-> eliminar();

        }
    }

    // Incluye template 
    incluirTemplate('header');
?>

    <main class="contenedor seccion">
        <h1>administrador de Bienes Raices</h1>

        <?php if(intval($resultado) === 1): ?>
            <p class="alerta exito"> Anuncio Creado Correctamente</p>
        <?php elseif(intval($resultado) === 2): ?>
            <p class="alerta exito"> Anuncio Actualizado Correctamente</p>
        <?php elseif(intval($resultado) === 3): ?>
            <p class="alerta exito"> Anuncio Eliminado Correctamente</p>    
        <?php endif; ?>

        <a href="/admin/propiedades/crear.php" class="boton boton-verde">Nueva Propiedad</a>
        
        <table class="propiedades">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titulo</th>
                    <th>Imagen</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody> <!-- Mostrar los Resultados -->
                <?php foreach( $propiedades as $propiedad ): ?>
                <tr class="centrado">
                    <td><?php echo $propiedad->id; ?></td>
                    <td><?php echo $propiedad->titulo; ?></td>
                    <td><img src="/imagenes/<?php echo $propiedad->imagen; ?>" class="imagen-tabla"></td>
                    <td> $<?php echo $propiedad->precio; ?></td>
                    <td>
                        <form method="POST" class="w-100">

                            <input type="hidden" name="id" value="<?php echo $propiedad->id; ?>"> 

                            <input type="submit" class="boton-rojo-block" value="Eliminar">
                        </form>
                        <a class="boton-amarillo-block" href="/admin/propiedades/actualizar.php?id=<?php echo $propiedad->id; ?>">Actualiza</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

<?php 

    /* Cerrar la conexion */

    incluirTemplate('footer');
?>