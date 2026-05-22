<?php
session_start();

if (!isset($_SESSION['user'])) {

    header("Location: login.php");
    exit();
}

include("conexion.php");

$mi_id = $_SESSION['user']['id'];
$correo = $_SESSION['user']['correo'];
$nombre = explode("@", $correo)[0];

/* =====================================
   CONSULTA NOTIFICACIONES
===================================== */

$sql = "SELECT
        i.id,
        i.estado,
        i.fecha_solicitud,

        u.correo AS solicitante,

        p.ofreces,
        p.descripcion,
        p.imagen,
        p.fecha_vencimiento

        FROM intercambios i

        INNER JOIN publicaciones p
        ON i.publicacion_id = p.id

        INNER JOIN usuarios u
        ON i.solicitante_id = u.id

        WHERE p.usuario_id = ?
        AND i.estado = 'pendiente'

        ORDER BY i.fecha_solicitud DESC";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $mi_id);

$stmt->execute();

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Notificaciones</title>

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

/* Logout */

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

    font-size:34px;

    font-weight:700;
}

.user-box{

    background:rgba(255,255,255,0.08);

    border:1px solid rgba(255,255,255,0.08);

    padding:12px 18px;

    border-radius:16px;

    color:#cbd5e1;
}

/* Notifications */

.notifications{

    display:flex;

    flex-direction:column;

    gap:25px;
}

.notification-card{

    background:rgba(255,255,255,0.08);

    border:1px solid rgba(255,255,255,0.08);

    backdrop-filter:blur(15px);

    border-radius:28px;

    overflow:hidden;

    display:flex;

    gap:25px;

    padding:20px;

    transition:0.3s;
}

.notification-card:hover{

    transform:translateY(-5px);

    box-shadow:0 0 25px rgba(0,0,0,0.35);
}

/* Imagen */

.product-img{

    width:220px;

    height:220px;

    object-fit:cover;

    border-radius:20px;
}

/* Info */

.notification-info{

    flex:1;

    display:flex;

    flex-direction:column;

    justify-content:center;
}

.notification-info h4{

    font-size:24px;

    margin-bottom:12px;
}

.notification-info p{

    color:#cbd5e1;

    line-height:1.6;

    margin-bottom:10px;
}

/* Badge */

.badge-pendiente{

    display:inline-block;

    background:#f59e0b;

    color:white;

    padding:8px 14px;

    border-radius:14px;

    font-size:12px;

    font-weight:600;

    margin-bottom:15px;
}

/* Buttons */

.buttons{

    display:flex;

    gap:15px;

    margin-top:15px;
}

.btn-aceptar,
.btn-rechazar{

    border:none;

    padding:14px 20px;

    border-radius:16px;

    color:white;

    font-weight:600;

    transition:0.3s;

    cursor:pointer;
}

.btn-aceptar{

    background:linear-gradient(
    90deg,
    #10b981,
    #22c55e
    );
}

.btn-rechazar{

    background:linear-gradient(
    90deg,
    #ef4444,
    #dc2626
    );
}

.btn-aceptar:hover,
.btn-rechazar:hover{

    transform:scale(1.05);
}

/* Empty */

.empty{

    background:rgba(255,255,255,0.08);

    border:1px solid rgba(255,255,255,0.08);

    padding:35px;

    border-radius:25px;

    text-align:center;

    color:#cbd5e1;
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

            <a href="notificaciones.php">
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

        <div class="topbar">

            <h2>
                Notificaciones 🔔
            </h2>

            <div class="user-box">

                <?php echo ucfirst($nombre); ?>

            </div>

        </div>

        <div class="notifications">

        <?php if ($result->num_rows == 0) { ?>

            <div class="empty">

                <h4>No tienes solicitudes 😴</h4>

            </div>

        <?php } ?>

        <?php while($row = $result->fetch_assoc()) { ?>

            <div class="notification-card">

                <img
                src="<?php echo $row['imagen']; ?>"
                class="product-img">

                <div class="notification-info">

                    <span class="badge-pendiente">

                        PENDIENTE

                    </span>

                    <h4>

                        <?php echo $row['solicitante']; ?>

                    </h4>

                    <p>

                        quiere intercambiar contigo 🚀

                    </p>

                    <p>

                        <b>Producto:</b>

                        <?php echo $row['ofreces']; ?>

                    </p>

                    <p>

                        <b>Descripción:</b>

                        <?php echo $row['descripcion']; ?>

                    </p>

                    <p>

                        <b>Vence:</b>

                        <?php echo $row['fecha_vencimiento']; ?>

                    </p>

                    <div class="buttons">

                        <form action="aceptar.php" method="POST">

                            <input
                            type="hidden"
                            name="id"
                            value="<?php echo $row['id']; ?>">

                            <button class="btn-aceptar">

                                Aceptar

                            </button>

                        </form>

                        <form action="rechazar.php" method="POST">

                            <input
                            type="hidden"
                            name="id"
                            value="<?php echo $row['id']; ?>">

                            <button class="btn-rechazar">

                                Rechazar

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        <?php } ?>

        </div>

    </div>

</div>

</body>
</html>