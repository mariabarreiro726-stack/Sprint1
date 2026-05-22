<?php
session_start();

if (!isset($_SESSION['user'])) {

    header("Location: login.php");
    exit();
}

include("conexion.php");

$correo = $_SESSION['user']['correo'];
$nombre = explode("@", $correo)[0];
$mi_id = $_SESSION['user']['id'];

/* =====================================
   PUBLICACIONES ACTIVAS
===================================== */

$sql_publicaciones = "SELECT COUNT(*) AS total
FROM publicaciones
WHERE usuario_id = ?
AND estado = 'disponible'";

$stmt_publicaciones = $conn->prepare($sql_publicaciones);

$stmt_publicaciones->bind_param("i", $mi_id);

$stmt_publicaciones->execute();

$res_publicaciones = $stmt_publicaciones->get_result();

$total_publicaciones = $res_publicaciones->fetch_assoc()['total'];

/* =====================================
   INTERCAMBIOS
===================================== */

$sql_intercambios = "SELECT COUNT(*) AS total
FROM intercambios
WHERE solicitante_id = ?";

$stmt_intercambios = $conn->prepare($sql_intercambios);

$stmt_intercambios->bind_param("i", $mi_id);

$stmt_intercambios->execute();

$res_intercambios = $stmt_intercambios->get_result();

$total_intercambios = $res_intercambios->fetch_assoc()['total'];

/* =====================================
   NOTIFICACIONES
===================================== */

$sql_notificaciones = "SELECT COUNT(*) AS total
FROM intercambios i
INNER JOIN publicaciones p
ON i.publicacion_id = p.id
WHERE p.usuario_id = ?
AND i.estado = 'pendiente'";

$stmt_notificaciones = $conn->prepare($sql_notificaciones);

$stmt_notificaciones->bind_param("i", $mi_id);

$stmt_notificaciones->execute();

$res_notificaciones = $stmt_notificaciones->get_result();

$total_notificaciones = $res_notificaciones->fetch_assoc()['total'];

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard</title>

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

    font-size:30px;

    font-weight:700;

    margin-bottom:50px;

    text-align:center;
}

.logo span{
    color:#d946ef;
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

/* Cards */

.cards{

    display:grid;

    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));

    gap:20px;

    margin-bottom:35px;
}

.card-info{

    background:rgba(255,255,255,0.08);

    border:1px solid rgba(255,255,255,0.08);

    backdrop-filter:blur(15px);

    border-radius:25px;

    padding:25px;

    transition:0.3s;
}

.card-info:hover{

    transform:translateY(-8px);

    box-shadow:0 0 25px rgba(0,0,0,0.3);
}

.card-info i{

    font-size:35px;

    color:#22d3ee;

    margin-bottom:15px;

    display:block;
}

.card-info h3{

    font-size:32px;

    margin-bottom:5px;
}

.card-info p{
    color:#cbd5e1;
}

/* Productos */

.titulo-menu{

    font-size:28px;

    margin-bottom:25px;
}

.tabla-productos{

    display:grid;

    grid-template-columns:repeat(auto-fit,minmax(300px,1fr));

    gap:25px;
}

.producto-tabla{

    background:rgba(255,255,255,0.08);

    border:1px solid rgba(255,255,255,0.08);

    backdrop-filter:blur(15px);

    border-radius:28px;

    overflow:hidden;

    position:relative;

    transition:0.3s;
}

.producto-tabla:hover{

    transform:translateY(-6px);

    box-shadow:0 0 30px rgba(0,0,0,0.35);
}

.img-tabla{

    width:100%;

    height:220px;

    object-fit:cover;
}

.info{

    padding:22px;
}

.info h5{

    font-size:22px;

    margin-bottom:10px;
}

.info p{

    color:#cbd5e1;

    margin-bottom:10px;

    line-height:1.5;
}

.desc{

    font-size:14px;
}

.vence{

    color:#fbbf24!important;

    font-size:14px;
}

/* Badge */

.badge-estado{

    position:absolute;

    top:15px;

    right:15px;

    padding:8px 14px;

    border-radius:14px;

    font-size:12px;

    font-weight:600;

    z-index:2;
}

.verde{

    background:#22c55e;

    color:white;
}

.rojo{

    background:#ef4444;

    color:white;
}

/* Botón */

.btn-ver{

    width:100%;

    margin-top:15px;

    padding:14px;

    border:none;

    border-radius:16px;

    background:linear-gradient(
    90deg,
    #c026d3,
    #06b6d4
    );

    color:white;

    font-weight:600;

    transition:0.3s;

    cursor:pointer;
}

.btn-ver:hover{

    transform:scale(1.02);
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

    <!-- Sidebar -->

    <div class="sidebar">

        <div class="logo">
            Neo<span>Panel</span>
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

    <!-- Main -->

    <div class="main">

        <!-- Topbar -->

        <div class="topbar">

            <h2>
                Bienvenido <?php echo ucfirst($nombre); ?> 👋
            </h2>

            <div class="user-box">
                <?php echo $correo; ?>
            </div>

        </div>

        <!-- Cards -->

        <div class="cards">

            <!-- Publicaciones -->

            <div class="card-info">

                <i class="bi bi-box"></i>

                <h3>

                    <?php echo $total_publicaciones; ?>

                </h3>

                <p>

                    Publicaciones activas

                </p>

            </div>

            <!-- Intercambios -->

            <div class="card-info">

                <i class="bi bi-arrow-repeat"></i>

                <h3>

                    <?php echo $total_intercambios; ?>

                </h3>

                <p>

                    Intercambios realizados

                </p>

            </div>

            <!-- Notificaciones -->

            <div class="card-info">

                <i class="bi bi-bell"></i>

                <h3>

                    <?php echo $total_notificaciones; ?>

                </h3>

                <p>

                    Notificaciones pendientes

                </p>

            </div>

        </div>

        <!-- Productos -->

        <h2 class="titulo-menu">

            Tus intercambios activos

        </h2>

        <div class="tabla-productos">

        <?php

        $sql = "SELECT *
                FROM publicaciones
                WHERE usuario_id = $mi_id
                AND estado = 'disponible'
                ORDER BY id DESC
                LIMIT 9";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 0) {

            echo "<p>No tienes intercambios activos 😢</p>";
        }

        while($row = mysqli_fetch_assoc($result)) {

            $vencido = false;

            if (!empty($row['fecha_vencimiento'])) {

                $vencido = (
                    strtotime($row['fecha_vencimiento']) < time()
                );
            }
        ?>

        <div class="producto-tabla">

            <!-- Badge -->

            <div class="badge-estado <?php echo $vencido ? 'rojo' : 'verde'; ?>">

                <?php echo $vencido ? 'VENCIDO' : 'ACTIVO'; ?>

            </div>

            <!-- Imagen -->

            <img
            src="<?php echo $row['imagen']; ?>"
            class="img-tabla">

            <!-- Info -->

            <div class="info">

                <h5>

                    <?php echo strtoupper($row['ofreces']); ?>

                </h5>

                <p>

                    <b>Intercambia por:</b>

                    <?php echo $row['quieres']; ?>

                </p>

                <?php if (!empty($row['descripcion'])) { ?>

                    <p class="desc">

                        <?php echo $row['descripcion']; ?>

                    </p>

                <?php } ?>

                <?php if (!empty($row['fecha_vencimiento'])) { ?>

                    <p class="vence">

                        Vence:
                        <?php echo $row['fecha_vencimiento']; ?>

                    </p>

                <?php } ?>

                <button class="btn-ver">

                    Ver más

                </button>

            </div>

        </div>

        <?php } ?>

        </div>

    </div>

</div>

</body>
</html>