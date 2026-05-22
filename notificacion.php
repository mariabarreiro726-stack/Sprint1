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

$sql = "SELECT 
    i.id,
    i.estado,
    i.fecha_solicitud,

    i.producto_nombre,
    i.producto_descripcion,
    i.producto_imagen,
    i.producto_vencimiento,

    u.correo AS solicitante,

    p.ofreces,
    p.descripcion AS pub_descripcion,
    p.imagen AS pub_imagen,
    p.fecha_vencimiento AS pub_vencimiento

FROM intercambios i
INNER JOIN publicaciones p ON i.publicacion_id = p.id
INNER JOIN usuarios u ON i.solicitante_id = u.id
WHERE p.usuario_id = ?
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
    background:linear-gradient(135deg,#050816,#111827,#240046);
    color:white;
    min-height:100vh;
    overflow-x:hidden;
    position:relative;
}

/* 🔥 EFECTO GLOW (como tu proyecto) */
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

/* LAYOUT */
.container-dashboard{
    display:flex;
    min-height:100vh;
}

/* SIDEBAR (VERSIÓN FINAL IGUAL A TU PROYECTO) */
.sidebar{
    width:260px;
    background:rgba(255,255,255,0.06);
    border-right:1px solid rgba(255,255,255,0.08);
    backdrop-filter:blur(20px);
    padding:30px 20px;
    position:fixed;
    height:100vh;
}

/* LOGO */
.logo{
    display:flex;
    justify-content:center;
    align-items:center;
    margin-bottom:60px;
}

.logo img{
    width:170px;
    object-fit:contain;
    filter:drop-shadow(0 0 15px rgba(192,38,211,0.35));
    transition:0.3s;
}

.logo img:hover{
    transform:scale(1.05);
}

/* MENU */
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
    display:flex;
    align-items:center;
    gap:12px;
    transition:0.3s;
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

/* LOGOUT */
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

/* MAIN */
.main{
    margin-left:260px;
    width:100%;
    padding:35px;
}

/* TOPBAR */
.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:35px;
}

.topbar h2{
    font-size:34px;
}

.user-box{
    background:rgba(255,255,255,0.08);
    padding:12px 18px;
    border-radius:16px;
}

/* ALERTAS */
.alert{
    padding:15px;
    border-radius:15px;
    margin-bottom:20px;
    font-weight:600;
}

.ok{background:#22c55e;}
.error{background:#ef4444;}

/* CARD NOTIFICACIÓN */
.card{
    background:rgba(255,255,255,0.08);
    border:1px solid rgba(255,255,255,0.08);
    border-radius:25px;
    padding:20px;
    margin-bottom:20px;
    display:flex;
    gap:20px;
    transition:0.3s;
}

.card:hover{
    transform:translateY(-5px);
}

/* IMÁGENES */
.img-product{
    width:170px;
    height:170px;
    object-fit:cover;
    border-radius:15px;
}

.small-img{
    width:90px;
    border-radius:10px;
    margin:8px 0;
}

/* INFO */
.info{
    flex:1;
}

.info p{
    color:#cbd5e1;
    margin-bottom:6px;
}

/* BADGES */
.badge{
    padding:6px 12px;
    border-radius:10px;
    font-size:12px;
    display:inline-block;
    margin-bottom:10px;
}

.pendiente{background:#f59e0b;}
.aceptado{background:#22c55e;}
.rechazado{background:#ef4444;}

/* BOTONES */
.buttons{
    margin-top:12px;
    display:flex;
    gap:10px;
}

button{
    border:none;
    padding:10px 15px;
    border-radius:10px;
    color:white;
    cursor:pointer;
    transition:0.3s;
}

.btn-ok{
    background:linear-gradient(90deg,#10b981,#22c55e);
}

.btn-no{
    background:linear-gradient(90deg,#ef4444,#dc2626);
}

button:hover{
    transform:scale(1.05);
}

/* EMPTY */
.empty{
    background:rgba(255,255,255,0.08);
    padding:25px;
    border-radius:20px;
    text-align:center;
}

/* RESPONSIVE */
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
.modal{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.6);
    display:flex;
    justify-content:center;
    align-items:center;
    z-index:999;
}

.modal-box{
    background:rgba(255,255,255,0.08);
    backdrop-filter:blur(20px);
    padding:30px;
    border-radius:25px;
    text-align:center;
    width:350px;
}

.modal-box button{
    background:linear-gradient(90deg,#c026d3,#06b6d4);
    border:none;
    padding:10px 20px;
    border-radius:12px;
    color:white;
    cursor:pointer;
}

</style>

</head>

<body>

<div class="container-dashboard">

<!-- SIDEBAR -->
<div class="sidebar">

    <div class="logo">
        <img src="LOGO/logo.png">
    </div>

    <div class="sidebar-menu">
        <a href="dashboard.php"><i class="bi bi-house-fill"></i>Inicio</a>
        <a href="publicar.php"><i class="bi bi-plus-circle-fill"></i>Publicar</a>
        <a href="productos.php"><i class="bi bi-box-seam"></i>Intercambios</a>
        <a href="puntos.php"><i class="bi bi-geo-alt-fill"></i>Puntos</a>
        <a href="notificacion.php"><i class="bi bi-bell-fill"></i>Notificaciones</a>
    </div>

    <div class="logout">
        <a href="logout.php">Salir</a>
    </div>

</div>

<!-- MAIN -->
<div class="main">

    <div class="topbar">
        <h2>Notificaciones 🔔</h2>
        <div class="user-box"><?php echo ucfirst($nombre); ?></div>
    </div>

    <!-- ALERTAS -->
    <?php if(isset($_GET['ok'])){ ?>
        <div class="alert ok"><?php echo $_GET['ok']; ?></div>
    <?php } ?>

    <?php if(isset($_GET['error'])){ ?>
        <div class="alert error"><?php echo $_GET['error']; ?></div>
    <?php } ?>

    <?php if($result->num_rows == 0){ ?>
        <div class="empty">No tienes solicitudes 😴</div>
    <?php } ?>

    <?php while($row = $result->fetch_assoc()){ ?>

    <div class="card">

        <!-- TU PRODUCTO -->
        <img src="<?php echo $row['pub_imagen']; ?>" class="img-product">

        <div class="info">

            <span class="badge <?php echo $row['estado']; ?>">
                <?php echo strtoupper($row['estado']); ?>
            </span>

            <h4><?php echo $row['solicitante']; ?></h4>

            <p>
            <?php if($row['estado']=="pendiente"){ ?>
                quiere intercambiar contigo 🚀
            <?php } elseif($row['estado']=="aceptado"){ ?>
                aceptó tu intercambio ✅
            <?php } else { ?>
                rechazó tu intercambio ❌
            <?php } ?>
            </p>

            <hr>

            <p><b>Tu producto:</b> <?php echo $row['ofreces']; ?></p>
            <p><?php echo $row['pub_descripcion']; ?></p>

            <hr>

            <!-- PRODUCTO OFRECIDO -->
            <p><b>Te ofrece:</b> <?php echo $row['producto_nombre']; ?></p>

            <img src="<?php echo $row['producto_imagen']; ?>" class="small-img">

            <p><?php echo $row['producto_descripcion']; ?></p>
            <p><b>Vence:</b> <?php echo $row['producto_vencimiento']; ?></p>
            <?php if($row['estado']=="aceptado"){ ?>
<p style="margin-top:8px; color:#22d3ee;">
📍 <b>Punto de encuentro:</b> <?php echo $row['punto_encuentro']; ?>
</p>
<?php } ?>

            <?php if($row['estado']=="pendiente"){ ?>
            <div class="buttons">

                <form action="aceptar.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <button class="btn-ok">Aceptar</button>
                </form>

                <form action="rechazar.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <button class="btn-no">Rechazar</button>
                </form>

            </div>
            <?php } ?>

        </div>

    </div>

    <?php } ?>

</div>

</div>
<?php if(isset($_GET['lugar'])){ ?>

<div id="modal" class="modal">
    <div class="modal-box">
        <h2>📍 Punto de encuentro</h2>
        <p><?php echo $_GET['lugar']; ?></p>
        <button onclick="cerrarModal()">Entendido</button>
    </div>
</div>

<script>
function cerrarModal(){
    document.getElementById("modal").style.display = "none";
}
</script>

<?php } ?>
</body>
</html>