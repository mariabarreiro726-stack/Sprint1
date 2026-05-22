<?php
session_start();
include("conexion.php");

/* =========================
   VALIDAR SESIÓN
========================= */
if (!isset($_SESSION['user']['id'])) {
    die("Error: usuario no logueado");
}

$usuario_id = $_SESSION['user']['id'];

/* =========================
   VALIDAR DATOS
========================= */
if (
    empty($_POST['ofreces']) ||
    empty($_POST['fecha_vencimiento'])
) {
    die("Error: faltan datos del formulario");
}

$ofreces = trim($_POST['ofreces']);
$descripcion = trim($_POST['descripcion'] ?? "");
$fecha_vencimiento = $_POST['fecha_vencimiento'];

/* =========================
   VALIDAR IMAGEN
========================= */
if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== 0) {
    die("Error: imagen no válida");
}

/* validar tipo de archivo */
$permitidos = ['image/jpeg', 'image/png', 'image/webp'];

if (!in_array($_FILES['imagen']['type'], $permitidos)) {
    die("Solo se permiten imágenes JPG, PNG o WEBP");
}

$imagen = $_FILES['imagen']['name'];
$tmp = $_FILES['imagen']['tmp_name'];

/* =========================
   CARPETA DE SUBIDA
========================= */
$rutaCarpeta = "img/";

if (!is_dir($rutaCarpeta)) {
    mkdir($rutaCarpeta, 0777, true);
}

/* =========================
   RUTA SEGURA
========================= */
$nombreFinal = uniqid() . "_" . basename($imagen);

$ruta = $rutaCarpeta . $nombreFinal;

/* =========================
   MOVER ARCHIVO
========================= */
if (!move_uploaded_file($tmp, $ruta)) {
    die("Error al guardar la imagen");
}

/* =========================
   INSERT SEGURO
========================= */
$sql = "INSERT INTO publicaciones 
(usuario_id, imagen, ofreces, descripcion, fecha_vencimiento)
VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Error en la consulta: " . $conn->error);
}

$stmt->bind_param(
    "issss",
    $usuario_id,
    $ruta,
    $ofreces,
    $descripcion,
    $fecha_vencimiento
);

if (!$stmt->execute()) {
    die("Error al insertar: " . $stmt->error);
}

/* =========================
   REDIRECCIÓN
========================= */
header("Location: productos.php");
exit();
?>
