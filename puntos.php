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

    overflow-x:hidden;

    position:relative;
}

/* Glow */

body::before{

    content:'';

    position:fixed;

    width:350px;

    height:350px;

    background:#c026d3;

    border-radius:50%;

    filter:blur(140px);

    top:-100px;

    left:-100px;

    opacity:0.3;

    z-index:-1;
}

body::after{

    content:'';

    position:fixed;

    width:350px;

    height:350px;

    background:#06b6d4;

    border-radius:50%;

    filter:blur(140px);

    bottom:-100px;

    right:-100px;

    opacity:0.25;

    z-index:-1;
}

/* Layout */

.container-dashboard{
    display:flex;
    min-height:100vh;
}

/* Sidebar */

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

    align-items:center;

    margin-bottom:60px;
}

.logo-img{

    width:170px;

    object-fit:contain;

    transition:0.3s;
}

.logo-img:hover{

    transform:scale(1.05);
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

    font-size:15px;
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

.logout{

    position:absolute;

    bottom:30px;

    left:20px;

    right:20px;
}

.logout a{

    display:block;

    text-align:center;

    padding:14px;

    border-radius:16px;

    background:rgba(255,255,255,0.08);

    color:#ff6b6b;

    text-decoration:none;

    transition:0.3s;
}

.logout a:hover{

    background:rgba(255,0,0,0.15);
}

/* Main */

.main{

    margin-left:260px;

    width:100%;

    padding:35px;
}

/* Topbar */

.topbar{

    display:flex;

    justify-content:space-between;

    align-items:center;

    margin-bottom:35px;
}

.topbar h2{

    font-size:35px;

    font-weight:700;
}

.user-box{

    background:rgba(255,255,255,0.08);

    border:1px solid rgba(255,255,255,0.08);

    padding:12px 18px;

    border-radius:16px;

    color:#cbd5e1;
}

/* GRID */

.grid-puntos{

    display:grid;

    grid-template-columns:repeat(auto-fit,minmax(320px,1fr));

    gap:25px;
}

/* TARJETAS */

.punto-card{

    background:rgba(255,255,255,0.08);

    border:1px solid rgba(255,255,255,0.08);

    border-radius:28px;

    overflow:hidden;

    transition:0.3s;

    backdrop-filter:blur(15px);
}

.punto-card:hover{

    transform:translateY(-6px);

    box-shadow:0 0 25px rgba(0,0,0,0.35);
}

.punto-img{

    width:100%;

    height:230px;

    object-fit:cover;
}

.punto-info{

    padding:22px;
}

.punto-info h3{

    margin-bottom:15px;

    font-size:24px;
}

.punto-info p{

    color:#cbd5e1;

    line-height:1.7;

    margin-bottom:12px;
}

.badge{

    display:inline-block;

    margin-top:10px;

    background:#22c55e;

    padding:8px 14px;

    border-radius:14px;

    font-size:13px;

    font-weight:600;
}

/* Responsive */

@media(max-width:900px){

    .sidebar{
        width:90px;
    }

    .sidebar .logo,
    .sidebar a span{
        display:none;
    }

    .main{
        margin-left:90px;
    }

    .sidebar-menu a{
        justify-content:center;
    }
}

@media(max-width:600px){

    .main{
        padding:20px;
    }

    .topbar{
        flex-direction:column;
        gap:15px;
        align-items:flex-start;
    }

    .topbar h2{
        font-size:28px;
    }
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

        <div class="logout">

            <a href="logout.php">
                <i class="bi bi-box-arrow-right"></i>
                Salir
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

            <div class="punto-card">

                <img
                src="puntos recoleccion/punto1.jpg"
                class="punto-img">

                <div class="punto-info">

                    <h3>
                        Bienestar Universitario
                    </h3>

                    <p>
                        Punto central de intercambios con acompañamiento de oficina de bienestar.
                    </p>

                    <p>
                        Ubicación:
                        Bloque 27 detrás del Instituto Técnico.
                    </p>

                    <p>
                        Horario:
                        8:00 AM - 6:00 PM
                    </p>

                    <span class="badge">
                        Disponible
                    </span>

                </div>

            </div>

            <!-- PUNTO 2 -->

            <div class="punto-card">

                <img
                src="puntos recoleccion/punto2.jpg"
                class="punto-img">

                <div class="punto-info">

                    <h3>
                        Oficina SIS
                    </h3>

                    <p>
                        Zona autorizada para realizar entregas seguras entre estudiantes,de manera regulada
                        con acompañamiento del SIS
                    </p>

                    <p>
                        Ubicación:
                        Bloque 25 al lado de la biblioteca,ubicado cerca de la entrada peatonal
                    </p>

                    <p>
                        Horario:
                        9:00 AM - 4:00 PM
                    </p>

                    <span class="badge">
                        Disponible
                    </span>

                </div>

            </div>

            <!-- PUNTO 3 -->

            <div class="punto-card">

                <img
                src="puntos recoleccion/punto3.webp"
                class="punto-img">

                <div class="punto-info">

                    <h3>
                        Oficina de Bienestar 2
                    </h3>

                    <p>
                        Espacio destinado mas cercano a la zona academica,para realizar los intercambios de manera segura
                    </p>

                    <p>
                        Ubicación:
                        Edificio 6,en el septimo piso cerca de los baños
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

</div>

</body>
</html>