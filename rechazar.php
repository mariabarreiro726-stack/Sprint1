<?php
session_start();

include("conexion.php");

if(!isset($_SESSION['user'])){

    header("Location: login.php");
    exit();
}

$id = $_POST['id'];

/* =========================
   RECHAZAR INTERCAMBIO
========================= */

$sql = "UPDATE intercambios
SET estado = 'rechazado'
WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $id);

if($stmt->execute()){

    header("Location: notificacion.php?ok=❌ Intercambio rechazado");
    exit();

}else{

    echo "Error";
}
?>