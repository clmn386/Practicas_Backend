<?php

function conectarDB () : mysqli {
    $db = new mysqli('localhost:1433', 'clmn386', 'A.123456', 'bienes_raices');

    if(!$db) {
        echo "ERROR no se puede conectar a BD...";
        exit;
    } 
    return $db;
}