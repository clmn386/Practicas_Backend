<?php

require 'app.php';

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