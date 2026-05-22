<?php
session_start();
include("conexion.php");

$usuario_id = $_SESSION['user']['id'];
$publicacion_id = $_POST['publicacion_id'];

$sql = "INSERT INTO intercambios (publicacion_id, solicitante_id)
        VALUES ($publicacion_id, $usuario_id)";

mysqli_query($conn, $sql);

header("Location: productos.php");
?>