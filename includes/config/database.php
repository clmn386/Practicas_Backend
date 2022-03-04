<?php

function conectarDB () : mysqli {
    $db = mysqli_connect('localhost:1433', 'clmn386', 'A.123456', 'bienes_raices');

    if(!$db) {
        echo "ERROR...";
        exit;
    } 
    return $db;
}