<?php 
    //CONECTAR BASE DE DATOS
    require __DIR__.'/includes/config/database.php';
    $db = conectarDB();


    $errores = [];
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        
        //SANITIZAMOS LOS DATOS ANTES DE HACER LA PETICION A BD.
        $email = mysqli_real_escape_string($db, filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL) );
        $password = mysqli_real_escape_string($db, $_POST['password']);

        $query = "SELECT * FROM usuario WHERE email = '${email}'";
        $consulta = mysqli_query($db,$query);

        if ( $consulta -> num_rows ){
            $usuario = mysqli_fetch_assoc($consulta);
            $auth = password_verify($password, $usuario['passw']);
            if ($auth) {
                //el usuario autenticado
                session_start();

                    //llenar el arreglo de la session 
                    $_SESSION['usuario'] = $usuario['email'];
                    $_SESSION['login'] = true;


                    echo "<pre>";
                    var_dump($_SESSION);
                    echo "<pre>";

               header('Location: /admin'); 
            }else{
                $errores[] = "El password no corresponde";
            }

        }else{
            $errores[] = "no conincide el correo";
        }

    }
    //Incluye el header
    require 'includes/funciones.php';
    incluirTemplate('header');
?>

    <main class="contenedor seccion contenido-centrado">
        <h1>Login</h1>

        <?php foreach($errores as $error): ?>
            <div class="alerta error">
                <?php echo $error; ?>
            </div>
        <?php endforeach; ?>

        <form method="POST" class="formulario formulario-XS">
            <fieldset>
                
                <label for="email">E-mail:</label>
                <input type="email" name="email" placeholder="Tu Email" id="email" required>
                <!-- para la validacion del lado del cliente utilizamos "required" evitando que se envie el formulario con los datos incompletos o que no cumplan los requisitos -->
                <label for="password">Contraseña:</label>
                <input type="password" name="password" placeholder="password" id="password" required>
            </fieldset>

            <input type="submit" value="inicio sesion", class="boton boton-verde">
        </form>
    </main>



<?php 
    incluirTemplate('footer');
?>