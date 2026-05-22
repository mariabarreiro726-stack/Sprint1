<?php
include("conexion.php");

$id = $_POST['id'];

$sql = "UPDATE intercambios SET estado='rechazado' WHERE id=$id";
mysqli_query($conn, $sql);

header("Location: dashboard.php#notificaciones");
?>
