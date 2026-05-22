<?php
session_start();

include("conexion.php");

/* =========================
   VALIDAR SESIÓN
========================= */

if (!isset($_SESSION['user'])) {

    header("Location: login.php");
    exit();
}

/* =========================
   VERIFICAR POST
========================= */

if ($_SERVER['REQUEST_METHOD'] != 'POST') {

    die("Acceso no permitido");
}

/* =========================
   OBTENER DATOS
========================= */

$id = $_POST['id'] ?? '';

$descripcion = trim($_POST['descripcion'] ?? '');

$fecha = $_POST['fecha_vencimiento'] ?? '';

/* =========================
   VALIDAR
========================= */

if (
    empty($id) ||
    empty($descripcion) ||
    empty($fecha)
) {

    die("Todos los campos son obligatorios");
}

/* =========================
   ESTADO AUTOMÁTICO
========================= */

$fecha_actual = date("Y-m-d");

if ($fecha < $fecha_actual) {

    $estado = "vencido";

} else {

    $estado = "disponible";
}

/* =========================
   VALIDAR IMAGEN NUEVA
========================= */

$ruta_imagen = null;

if (
    isset($_FILES['imagen']) &&
    $_FILES['imagen']['error'] == 0
) {

    $permitidos = [
        'image/jpeg',
        'image/png',
        'image/webp'
    ];

    $mime = mime_content_type(
        $_FILES['imagen']['tmp_name']
    );

    if (!in_array($mime, $permitidos)) {

        die("Formato de imagen no permitido");
    }

    $carpeta = "img/";

    if (!is_dir($carpeta)) {

        mkdir($carpeta, 0777, true);
    }

    $extension = strtolower(
        pathinfo(
            $_FILES['imagen']['name'],
            PATHINFO_EXTENSION
        )
    );

    $nombre_final = uniqid("img_") . "." . $extension;

    $ruta_imagen = $carpeta . $nombre_final;

    if (
        !move_uploaded_file(
            $_FILES['imagen']['tmp_name'],
            $ruta_imagen
        )
    ) {

        die("Error al subir imagen");
    }
}

/* =========================
   SQL
========================= */

if ($ruta_imagen != null) {

    $sql = "UPDATE publicaciones 
    SET 
    descripcion = ?,
    fecha_vencimiento = ?,
    estado = ?,
    imagen = ?
    WHERE id = ?";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {

        die("Error SQL: " . $conn->error);
    }

    $stmt->bind_param(
        "ssssi",
        $descripcion,
        $fecha,
        $estado,
        $ruta_imagen,
        $id
    );

} else {

    $sql = "UPDATE publicaciones 
    SET 
    descripcion = ?,
    fecha_vencimiento = ?,
    estado = ?
    WHERE id = ?";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {

        die("Error SQL: " . $conn->error);
    }

    $stmt->bind_param(
        "sssi",
        $descripcion,
        $fecha,
        $estado,
        $id
    );
}

/* =========================
   EJECUTAR
========================= */

if ($stmt->execute()) {

    header("Location: productos.php");
    exit();

} else {

    die("Error al actualizar: " . $stmt->error);
}
?>