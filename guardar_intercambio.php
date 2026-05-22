<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['user'])) {
    die("Usuario no logueado");
}

$publicacion_id = $_POST['publicacion_id'];
$usuario_id = $_SESSION['user']['id'];

$producto = $_POST['producto'];
$descripcion = $_POST['descripcion'];
$fecha = $_POST['fecha'];

/* SUBIR IMAGEN */
$imagen = $_FILES['imagen']['name'];
$ruta = "uploads/" . time() . "_" . $imagen;

move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta);

/* INSERTAR */
$sql = "INSERT INTO intercambios
(publicacion_id, solicitante_id,
producto_nombre,
producto_descripcion,
producto_imagen,
producto_vencimiento)
VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "iissss",
    $publicacion_id,
    $usuario_id,   // ✅ CORREGIDO
    $producto,     // ✅ CORREGIDO
    $descripcion,
    $ruta,         // ✅ guardar la ruta correcta
    $fecha         // ✅ CORREGIDO
);

$stmt->execute();

header("Location: productos.php?ok=Solicitud enviada 🚀");
exit();
?>