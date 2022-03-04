<?php

define('TEMPLATE_URL', __DIR__ . '/templates'); // concatenamos la constante __DIR__ con nuestra archivo, para permitir que php defina el mismo donde encontrar el o los archivos.
define('FUNCIONES_URL', __DIR__ . 'funciones.php'); // de esta manera podemos hacer codigo portable, porque los distintos S.O tienen directorios diferentes.

function incluirTemplate(string $nombre, bool $inicio = false ){   
include TEMPLATE_URL . "/${nombre}.php"; // <-- require 'app.php';

}
function autenticado(): bool{
    session_start();

    $auth = $_SESSION['login'];
    if($auth) {
        return true;
    }
    return false;
}

function debuggear($a) {
    echo "<pre>";
    var_dump($a);
    echo "</pre>";
}