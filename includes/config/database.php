<?php

function conectarDB()
{
    //$db = mysqli_connect('localhost', 'root', '', 'bienes_raices');
    // 'unikabie_admin', 'Ivan1975*'
    $db = mysqli_connect('localhost', 'root', '', 'unikabie_bienesraices');

    if (!$db) {

        echo ("conexion fallida");

        exit;
    } else {

        return $db;
    }
}
?>

<!-- $db = mysqli_connect('localhost', 'unikacor_user', 'Mercurial123.', 'unikacor_bienes_raices'); -->