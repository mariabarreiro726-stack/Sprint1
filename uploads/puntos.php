<?php
session_start();

if (!isset($_SESSION['user'])) {

    header("Location: login.php");
    exit();
}

include("conexion.php");

$correo = $_SESSION['user']['correo'];
$nombre = explode("@", $correo)[0];
?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Puntos de Recolección</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins',sans-serif;
}

body{

    background:
    linear-gradient(
    135deg,
    #050816,
    #111827,
    #240046
    );

    color:white;

    min-height:100vh;
}

.container-dashboard{

    display:flex;

    min-height:100vh;
}

/* SIDEBAR */

.sidebar{

    width:260px;

    background:rgba(255,255,255,0.06);

    border-right:1px solid rgba(255,255,255,0.08);

    backdrop-filter:blur(20px);

    padding:30px 20px;

    position:fixed;

    height:100vh;
}

.logo{

    display:flex;

    justify-content:center;

    margin-bottom:60px;
}

.logo-img{

    width:170px;
}

.sidebar-menu{

    display:flex;

    flex-direction:column;

    gap:15px;
}

.sidebar-menu a{

    text-decoration:none;

    color:#cbd5e1;

    padding:15px 18px;

    border-radius:18px;

    transition:0.3s;

    display:flex;

    align-items:center;

    gap:12px;
}

.sidebar-menu a:hover{

    background:linear-gradient(
    90deg,
    rgba(192,38,211,0.25),
    rgba(6,182,212,0.2)
    );

    color:white;

    transform:translateX(5px);
}

/* MAIN */

.main{

    margin-left:260px;

    width:100%;

    padding:35px;
}

.topbar{

    display:flex;

    justify-content:space-between;

    align-items:center;

    margin-bottom:35px;
}

.user-box{

    background:rgba(255,255,255,0.08);

    padding:12px 18px;

    border-radius:16px;
}

/* TARJETAS */

.grid-puntos{

    display:grid;

    grid-template-columns:repeat(auto-fit,minmax(300px,1fr));

    gap:25px;
}

.card{

    background:rgba(255,255,255,0.08);

    border:1px solid rgba(255,255,255,0.08);

    border-radius:28px;

    padding:25px;

    transition:0.3s;
}

.card:hover{

    transform:translateY(-6px);
}

.card i{

    font-size:40px;

    color:#22d3ee;

    margin-bottom:15px;
}

.card h3{

    margin-bottom:15px;
}

.card p{

    color:#cbd5e1;

    line-height:1.7;
}

.badge{

    display:inline-block;

    margin-top:15px;

    background:#22c55e;

    padding:8px 14px;

    border-radius:14px;

    font-size:13px;

    font-weight:600;
}

</style>

</head>

<body>

<div class="container-dashboard">

    <!-- SIDEBAR -->

    <div class="sidebar">

        <div class="logo">

            <img
            src="LOGO/logo.png"
            class="logo-img">

        </div>

        <div class="sidebar-menu">

            <a href="dashboard.php">
                <i class="bi bi-house-fill"></i>
                <span>Inicio</span>
            </a>

            <a href="publicar.php">
                <i class="bi bi-plus-circle-fill"></i>
                <span>Publicar</span>
            </a>

            <a href="productos.php">
                <i class="bi bi-box-seam"></i>
                <span>Intercambios</span>
            </a>

            <a href="puntos.php">
                <i class="bi bi-geo-alt-fill"></i>
                <span>Puntos de recolección</span>
            </a>

            <a href="notificacion.php">
                <i class="bi bi-bell-fill"></i>
                <span>Notificaciones</span>
            </a>

        </div>

    </div>

    <!-- MAIN -->

    <div class="main">

        <div class="topbar">

            <h2>
                Puntos de recolección 📍
            </h2>

            <div class="user-box">

                <?php echo ucfirst($nombre); ?>

            </div>

        </div>

        <div class="grid-puntos">

            <!-- PUNTO 1 -->

            <div class="card">

                <i class="bi bi-building"></i>

                <h3>
                    Bienestar Universitario
                </h3>

                <p>
                    Punto oficial para entrega y recolección
                    de productos dentro de la universidad.
                </p>

                <p>
                    Horario:
                    8:00 AM - 5:00 PM
                </p>

                <span class="badge">
                    Disponible
                </span>

            </div>

            <!-- PUNTO 2 -->

            <div class="card">

                <i class="bi bi-door-open-fill"></i>

                <h3>
                    Oficina Administrativa
                </h3>

                <p>
                    Zona autorizada para realizar
                    entregas seguras entre estudiantes.
                </p>

                <p>
                    Horario:
                    9:00 AM - 4:00 PM
                </p>

                <span class="badge">
                    Disponible
                </span>

            </div>

            <!-- PUNTO 3 -->

            <div class="card">

                <i class="bi bi-shop"></i>

                <h3>
                    Punto Campus Central
                </h3>

                <p>
                    Espacio destinado para recolección
                    rápida de productos y donaciones.
                </p>

                <p>
                    Horario:
                    7:00 AM - 6:00 PM
                </p>

                <span class="badge">
                    Disponible
                </span>

            </div>

        </div>

    </div>

</div>

</body>
</html>