<?php

define('TEMPLATE_URL', __DIR__ . '/templates'); // concatenamos la constante __DIR__ con nuestra archivo, para permitir que php defina el mismo donde encontrar el o los archivos.
define('FUNCIONES_URL', __DIR__ . 'funciones.php'); // de esta manera podemos hacer codigo portable, porque los distintos S.O tienen directorios diferentes.
define('CARPETAS_IMAGENES', __DIR__.'/../imagenes/');


function incluirTemplate(string $nombre, bool $inicio = false ){   
include TEMPLATE_URL . "/${nombre}.php"; // <-- require 'app.php';

}
function autenticado(){
    session_start();

    if(!$_SESSION['login']) {
        header('Location: /');
    }
}

function debuggear($a) {
    echo "<pre>";
    var_dump($a);
    echo "</pre>";
    exit;
}

//Escapa Sanitizar el html
function san($html) : string {
    $san = htmlspecialchars($html);                     
    return $san;
}
