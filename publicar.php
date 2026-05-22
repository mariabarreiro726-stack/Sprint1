<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$mi_id = $_SESSION['user']['id'];
$correo = $_SESSION['user']['correo'];
$nombre = explode("@", $correo)[0];
?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Publicar intercambio</title>

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

    min-height:100vh;

    color:white;

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

    font-weight:700;
}

.user-box{

    background:rgba(255,255,255,0.08);

    border:1px solid rgba(255,255,255,0.08);

    padding:12px 18px;

    border-radius:16px;

    color:#cbd5e1;
}

/* Form Card */

.form-card{

    width:100%;

    max-width:850px;

    background:rgba(255,255,255,0.08);

    border:1px solid rgba(255,255,255,0.08);

    backdrop-filter:blur(20px);

    border-radius:30px;

    padding:35px;

    box-shadow:0 0 30px rgba(0,0,0,0.3);
}

/* Inputs */

.form-label{

    margin-bottom:8px;

    color:#cbd5e1;

    font-weight:500;
}

.form-control{

    background:rgba(255,255,255,0.08)!important;

    border:1px solid rgba(255,255,255,0.1)!important;

    color:white!important;

    border-radius:16px;

    padding:14px;
}

.form-control::placeholder{
    color:#94a3b8;
}

.form-control:focus{

    box-shadow:none!important;

    border-color:#d946ef!important;
}

textarea.form-control{

    min-height:130px;

    resize:none;
}

/* Upload */

.upload-box{

    border:2px dashed rgba(255,255,255,0.15);

    border-radius:20px;

    padding:25px;

    text-align:center;

    transition:0.3s;

    background:rgba(255,255,255,0.03);
}

.upload-box:hover{

    border-color:#22d3ee;

    background:rgba(255,255,255,0.05);
}

.upload-box i{

    font-size:45px;

    color:#22d3ee;

    margin-bottom:10px;
}

/* Button */

.btn-publicar{

    width:100%;

    padding:15px;

    border:none;

    border-radius:18px;

    background:linear-gradient(
    90deg,
    #c026d3,
    #06b6d4
    );

    color:white;

    font-size:16px;

    font-weight:600;

    transition:0.3s;

    cursor:pointer;
}

.btn-publicar:hover{

    transform:scale(1.02);

    box-shadow:0 0 20px rgba(192,38,211,0.4);
}

/* ALERTAS */

.alerta{

    position:fixed;

    top:30px;

    right:30px;

    background:rgba(15,23,42,0.95);

    border-left:5px solid #22c55e;

    color:white;

    padding:16px 22px;

    border-radius:18px;

    box-shadow:0 0 25px rgba(0,0,0,0.3);

    z-index:9999;

    display:none;

    animation:slideIn 0.4s ease;
}

.alerta i{

    margin-right:10px;

    color:#22c55e;
}

@keyframes slideIn{

    from{
        opacity:0;
        transform:translateX(100px);
    }

    to{
        opacity:1;
        transform:translateX(0);
    }
}

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

    .form-card{
        padding:25px;
    }

}

</style>

</head>

<body>

<!-- ALERTAS -->

<div class="alerta" id="alertaImagen">

    <i class="bi bi-check-circle-fill"></i>

    Imagen subida correctamente

</div>

<div class="alerta" id="alertaPublicacion">

    <i class="bi bi-check-circle-fill"></i>

    Producto publicado correctamente 🚀

</div>

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
                Publicar intercambio 🚀
            </h2>

            <div class="user-box">
                <?php echo $correo; ?>
            </div>

        </div>

        <div class="form-card">

            <form
            id="formPublicar"
            action="guardar_publicacion.php"
            method="POST"
            enctype="multipart/form-data">

                <!-- Imagen -->

                <div class="mb-4">

                    <label class="form-label">
                        Imagen del producto
                    </label>

                    <div class="upload-box">

                        <i class="bi bi-cloud-arrow-up-fill"></i>

                        <p class="mb-3">
                            Sube una imagen para tu intercambio
                        </p>

                        <input
                        type="file"
                        name="imagen"
                        id="imagen"
                        class="form-control"
                        required>

                    </div>

                </div>

                <!-- Ofreces -->

                <div class="mb-4">

                    <label class="form-label">
                        Producto que ofreces
                    </label>

                    <input
                    type="text"
                    name="ofreces"
                    class="form-control"
                    placeholder="Ej: Xbox Series S"
                    required>

                </div>


                <!-- Fecha -->

               <div class="mb-4">

    <label class="form-label">
        Fecha de vencimiento
    </label>

    <input
    type="date"
    name="fecha_vencimiento"
    class="form-control"
    min="<?php echo date('Y-m-d'); ?>"
    required>

</div>

                <!-- Descripción -->

                <div class="mb-4">

                    <label class="form-label">
                        Descripción
                    </label>

                    <textarea
                    name="descripcion"
                    class="form-control"
                    placeholder="Describe el estado del producto..."
                    required></textarea>

                </div>

                <!-- Botón -->

                <button class="btn-publicar">

                    Publicar intercambio

                </button>

            </form>

        </div>

    </div>

</div>

<script>

/* ALERTA IMAGEN */

const inputImagen = document.getElementById("imagen");

inputImagen.addEventListener("change", () => {

    const alerta = document.getElementById("alertaImagen");

    alerta.style.display = "block";

    setTimeout(() => {

        alerta.style.display = "none";

    }, 3000);
});

/* ALERTA PUBLICACIÓN */

const form = document.getElementById("formPublicar");

form.addEventListener("submit", (e) => {

    e.preventDefault();

    const alerta = document.getElementById("alertaPublicacion");

    alerta.style.display = "block";

    setTimeout(() => {

        form.submit();

    }, 1800);
});

</script>

</body>
</html>