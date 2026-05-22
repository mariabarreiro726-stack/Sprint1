<?php
include("conexion.php");

$id = $_GET['id'];
$conn->query("DELETE FROM productos WHERE id=$id");

header("Location: listar.php");
