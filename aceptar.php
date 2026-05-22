<?php
session_start();
include("conexion.php");

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$id = $_POST['id'];

/* =========================
   1. OBTENER PUBLICACION
========================= */

$sql_get = "SELECT publicacion_id 
            FROM intercambios 
            WHERE id = ?";

$stmt_get = $conn->prepare($sql_get);
$stmt_get->bind_param("i", $id);
$stmt_get->execute();
$result = $stmt_get->get_result();

if($result->num_rows == 0){
    die("Intercambio no encontrado");
}

$row = $result->fetch_assoc();
$publicacion_id = $row['publicacion_id'];

/* =========================
   2. GENERAR PUNTO ALEATORIO
========================= */

$puntos = [
    "Bienestar Universitario Bloque 27",
    "Oficina SIS Bloque 25",
    "Bienestar Oficina 2 Bloque 6"
];

$punto = $puntos[array_rand($puntos)];

/* =========================
   3. ACEPTAR INTERCAMBIO
========================= */

$sql = "UPDATE intercambios
        SET estado = 'aceptado',
            punto_encuentro = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $punto, $id);

/* =========================
   4. EJECUTAR
========================= */

if($stmt->execute()){

    /* =========================
       5. ELIMINAR PUBLICACION
    ========================= */

    $sql_delete = "DELETE FROM publicaciones
                   WHERE id = ?";

    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $publicacion_id);
    $stmt_delete->execute();

    /* =========================
       6. REDIRIGIR CON LUGAR
    ========================= */

    header("Location: notificacion.php?ok=Intercambio aceptado 🎉&lugar=" . urlencode($punto));
    exit();

}else{
    echo "Error al aceptar";
}
?>