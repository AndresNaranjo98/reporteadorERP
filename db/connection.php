<?php

$host = "localhost";
$db = "ininbio_erp";
$usuario = "usererp";
$contrasena = "3435"; 

try {
    $conexion = new PDO("mysql:host=$host; dbname=$db", $usuario, $contrasena);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Successful connection";
} catch (PDOException $error) {
    echo "Error: " . $error->getMessage();
    die();
}

// $mysqli = new mysqli($host, $usuario, $contrasena, $db);

// if ($mysqli->connect_error) {
//     die("Error de conexión: " . $mysqli->connect_error);
// } else {
//     echo "Conexión exitosa a la base de datos";
// }

?>