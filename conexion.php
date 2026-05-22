<?php
$conn = new mysqli("localhost", "root", "", "sprint1");

if ($conn->connect_error) {
    die("Error de conexión");
}
?>
