<?php

function conectarDB()
{
    // 'unikabie_admin', 'Ivan1975*'
    $db = mysqli_connect('localhost', 'unikabie_admin', 'Ivan1975*', 'unikabie_bienesraices');
    //$db = mysqli_connect('localhost', 'root', 'admin', 'unikabie_bienesraices');

    if (!$db) {

        echo ("conexion fallida");

        exit;
    } else {

        return $db;
    }
}
?>

<!-- $db = mysqli_connect('localhost', 'unikacor_user', 'Mercurial123.', 'unikacor_bienes_raices'); -->