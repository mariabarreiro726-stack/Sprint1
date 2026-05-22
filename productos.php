
<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include("conexion.php");

/* =========================
   ACTUALIZAR VENCIDOS
========================= */

$fecha_actual = date("Y-m-d");

$sql_vencidos = "UPDATE publicaciones
SET estado = 'vencido'
WHERE fecha_vencimiento < '$fecha_actual'";

mysqli_query($conn, $sql_vencidos);

$mi_id = $_SESSION['user']['id'];
$correo = $_SESSION['user']['correo'];
$nombre = explode("@", $correo)[0];
?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Intercambios</title>

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

    background:linear-gradient(
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

    filter:drop-shadow(0 0 15px rgba(192,38,211,0.35));

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
}

.user-box{

    background:rgba(255,255,255,0.08);

    padding:12px 18px;

    border-radius:16px;
}

/* PRODUCTOS */

.tabla-productos{

    display:grid;

    grid-template-columns:repeat(auto-fit,minmax(320px,1fr));

    gap:25px;
}

.producto-tabla{

    background:rgba(255,255,255,0.08);

    border:1px solid rgba(255,255,255,0.08);

    border-radius:28px;

    overflow:hidden;

    transition:0.3s;

    position:relative;
}

.producto-tabla:hover{

    transform:translateY(-8px);
}

.img-tabla{

    width:100%;

    height:240px;

    object-fit:cover;
}

.info{
    padding:22px;
}

.info p{

    color:#cbd5e1;

    margin-bottom:10px;
}

/* BADGES */

.badge-disponible{

    position:absolute;

    top:15px;

    right:15px;

    background:#22c55e;

    color:white;

    padding:8px 14px;

    border-radius:14px;

    font-size:12px;

    font-weight:600;
}

.badge-vencido{

    position:absolute;

    top:15px;

    right:15px;

    background:#ef4444;

    color:white;

    padding:8px 14px;

    border-radius:14px;

    font-size:12px;

    font-weight:600;
}

.badge-mio{

    position:absolute;

    top:15px;

    left:15px;

    background:#c026d3;

    color:white;

    padding:8px 14px;

    border-radius:14px;

    font-size:12px;

    font-weight:600;
}

/* BOTONES */

.btn-solicitar{

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

    cursor:pointer;

    transition:0.3s;
}

.btn-solicitar:hover{

    transform:scale(1.02);
}

.btn-editar{

    display:block;

    width:100%;

    margin-top:12px;

    padding:14px;

    text-align:center;

    text-decoration:none;

    border-radius:16px;

    background:rgba(255,255,255,0.08);

    color:white;

    transition:0.3s;
}

.btn-editar:hover{

    background:rgba(255,255,255,0.15);

    transform:scale(1.02);
}

.btn-eliminar{

    width:100%;

    margin-top:12px;

    padding:14px;

    border:none;

    border-radius:16px;

    background:linear-gradient(
    90deg,
    #ef4444,
    #dc2626
    );

    color:white;

    font-weight:600;

    cursor:pointer;

    transition:0.3s;
}

.btn-eliminar:hover{

    transform:scale(1.02);

    box-shadow:0 0 20px rgba(239,68,68,0.4);
}

/* Empty */

.empty{

    background:rgba(255,255,255,0.08);

    padding:30px;

    border-radius:25px;

    text-align:center;
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

    <!-- Main -->

    <div class="main">

        <div class="topbar">

            <h2>
                Intercambios disponibles 🚀
            </h2>

            <div class="user-box">
                <?php echo ucfirst($nombre); ?>
            </div>

        </div>

        <div class="tabla-productos">

        <?php

        $sql = "SELECT *
                FROM publicaciones
                ORDER BY id DESC";

        $result = mysqli_query($conn, $sql);

        if (!$result) {

            die("Error en SQL: " . mysqli_error($conn));
        }

        if (mysqli_num_rows($result) == 0) {

            echo "
            <div class='empty'>
                <h4>No hay productos publicados 😢</h4>
            </div>
            ";
        }

        ?>

      <?php while($row = mysqli_fetch_assoc($result)) { ?>

    <div class="producto-tabla">

        <!-- BADGE ESTADO -->
        <?php if($row['estado'] == 'disponible') { ?>

            <div class="badge-disponible">
                DISPONIBLE
            </div>

        <?php } else { ?>

            <div class="badge-vencido">
                VENCIDO
            </div>

        <?php } ?>

        <!-- BADGE MIO -->
        <?php if($row['usuario_id'] == $mi_id) { ?>

            <div class="badge-mio">
                MI PUBLICACIÓN
            </div>

        <?php } ?>

        <!-- IMAGEN -->
        <img
        src="<?php echo $row['imagen']; ?>"
        class="img-tabla">

        <div class="info">

            <!-- DESCRIPCIÓN -->
            <?php if (!empty($row['descripcion'])) { ?>

                <p>
                    <?php echo $row['descripcion']; ?>
                </p>

            <?php } ?>

            <!-- FECHA -->
            <p>
                <b>Vence:</b>
                <?php echo $row['fecha_vencimiento']; ?>
            </p>

            <!-- BOTÓN SOLICITAR SOLO SI NO ES MIO Y ESTÁ DISPONIBLE -->
            <?php if($row['usuario_id'] != $mi_id && $row['estado'] == 'disponible') { ?>

                <button type="button" class="btn-solicitar"
                        onclick="abrirModal(<?php echo $row['id']; ?>)">
                    Solicitar intercambio
                </button>

            <?php } ?>

            <!-- MENSAJE SI ES TUYO -->
            <?php if($row['usuario_id'] == $mi_id) { ?>

                <p style="color:#22c55e; font-weight:600; margin-top:10px;">
                    Este es tu producto
                </p>

            <?php } ?>

            <!-- MENSAJE SI ESTÁ VENCIDO -->
            <?php if($row['estado'] == 'vencido') { ?>

                <p style="color:#ef4444; font-weight:600; margin-top:10px;">
                    Producto no disponible
                </p>

            <?php } ?>

            <!-- BOTONES DEL DUEÑO -->
            <?php if($row['usuario_id'] == $mi_id) { ?>

                <a href="editar.php?id=<?php echo $row['id']; ?>"
                class="btn-editar">
                    Editar producto
                </a>

                <form action="eliminar.php" method="POST">

                    <input
                    type="hidden"
                    name="id"
                    value="<?php echo $row['id']; ?>">

                    <button
                    class="btn-eliminar"
                    onclick="return confirm('¿Eliminar este producto?')">
                        Eliminar producto
                    </button>

                </form>

            <?php } ?>

        </div>

    </div>

<?php } ?>


                </div>

            </div>

   

        </div>

    </div>

</div>
<div id="modalIntercambio" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); justify-content:center; align-items:center;">

<div style="background:#111827; padding:25px; border-radius:20px; width:400px;">

<h3>Ofrecer producto 🔄</h3>

<form action="guardar_intercambio.php" method="POST" enctype="multipart/form-data">

<input type="hidden" name="publicacion_id" id="publicacion_id">

<input type="text" name="producto" placeholder="Nombre del producto" required>

<input type="date" name="fecha" required>

<input type="file" name="imagen" required>

<textarea name="descripcion" placeholder="Descripción" required></textarea>

<button type="submit" class="btn-solicitar">Solicitar intercambio</button>

<button type="button" onclick="cerrarModal()" style="background:red; margin-top:10px;">Cancelar</button>

</form>

</div>
</div>
<script>
function abrirModal(id){
    document.getElementById("modalIntercambio").style.display="flex";
    document.getElementById("publicacion_id").value=id;
}

function cerrarModal(){
    document.getElementById("modalIntercambio").style.display="none";
}
</script>

</body>
</html>
```
