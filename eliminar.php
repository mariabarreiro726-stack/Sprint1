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
   VALIDAR POST
========================= */

if ($_SERVER['REQUEST_METHOD'] != 'POST') {

    die("Acceso no permitido");
}

/* =========================
   DATOS
========================= */

$id = $_POST['id'] ?? '';

$mi_id = $_SESSION['user']['id'];

if (empty($id)) {

    die("ID inválido");
}

/* =========================
   ELIMINAR SOLO SI ES MÍO
========================= */

$sql = "DELETE FROM publicaciones
WHERE id = ?
AND usuario_id = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {

    die("Error SQL: " . $conn->error);
}

$stmt->bind_param(
    "ii",
    $id,
    $mi_id
);

/* =========================
   EJECUTAR
========================= */

if($stmt->execute()){

    if($stmt->affected_rows > 0){

        header("Location: productos.php?eliminado=1");
        exit();

    }else{

        die("No tienes permiso para eliminar este producto");
    }

}else{

    die("Error al eliminar");
}
?>