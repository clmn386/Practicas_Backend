<?php

function conectarDB () : mysqli {
    $db = mysqli_connect('localhost:1433', 'root', 'cj65gty', 'bienes_raices');

    if(!$db) {
        echo "ERROR...";
        exit;
    } 
    return $db;
}