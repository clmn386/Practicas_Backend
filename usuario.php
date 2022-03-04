<?php 
   require 'includes/app.php';
// importar la conexion 

$db = conectarDB();

// Crear un Email y passw
$email = "admin@tartaisa.com";
$password = "123456";

$passwHash = password_hash($password, PASSWORD_DEFAULT); //HASHEAR PASSWORD PHP

/*  $passwHash = password_hash($passw, PASSWORD_BCRYPT); 
    OTRA OPCION PARA HASHEAR INCLUIDA EN PHP 7*/

// Query para crear el Usuario
$query =" INSERT INTO usuario (email,passw) VALUES ('$user','$passwHash')";
// Agregarlo a la base de datos

/* echo "<pre>";
var_dump($query);
echo "</pre>";
exit; */

$agregar = mysqli_query($db,$query);

