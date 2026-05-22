<?php
session_start();

include("conexion.php");

if(!isset($_SESSION['user'])){

    header("Location: login.php");
    exit();
}

$id = $_POST['id'];

/* =========================
   ACEPTAR INTERCAMBIO
========================= */

$sql = "UPDATE intercambios
SET estado = 'aceptado'
WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $id);

if($stmt->execute()){

    header("Location: notificacion.php?ok=✅ Intercambio aceptado exitosamente");
    exit();

}else{

    echo "Error";
}
?>