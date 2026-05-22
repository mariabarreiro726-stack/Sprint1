<?php
session_start();
include("conexion.php");

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$mi_id = $_SESSION['user']['id'];
$correo = $_SESSION['user']['correo'];
$nombre = explode("@", $correo)[0];

if (!isset($_GET['id'])) {
    die("Producto no encontrado");
}

$id = $_GET['id'];

$sql = "SELECT * FROM publicaciones WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Producto no existe");
}

$row = $result->fetch_assoc();

/* VALIDAR QUE EL PRODUCTO SEA DEL USUARIO */

if ($row['usuario_id'] != $mi_id) {
    die("No puedes editar este producto");
}
?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Editar producto</title>

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

    display:block;
}

.form-control{

    width:100%;

    background:rgba(255,255,255,0.08);

    border:1px solid rgba(255,255,255,0.1);

    color:white;

    border-radius:16px;

    padding:14px;

    margin-bottom:20px;
}

.form-control::placeholder{
    color:#94a3b8;
}

.form-control:focus{

    outline:none;

    border-color:#d946ef;
}

textarea.form-control{

    min-height:130px;

    resize:none;
}

/* Imagen actual */

.preview{

    width:100%;

    max-height:300px;

    object-fit:cover;

    border-radius:20px;

    margin-bottom:20px;

    border:2px solid rgba(255,255,255,0.1);
}

/* Botón */

.btn-guardar{

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

    cursor:pointer;

    transition:0.3s;
}

.btn-guardar:hover{

    transform:scale(1.02);

    box-shadow:0 0 20px rgba(192,38,211,0.4);
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

    .form-card{
        padding:25px;
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

        <div class="topbar">

            <h2>
                Editar producto ✨
            </h2>

            <div class="user-box">

                <?php echo ucfirst($nombre); ?>

            </div>

        </div>

        <div class="form-card">

            <form action="actualizar.php" method="POST" enctype="multipart/form-data">

                <input
                type="hidden"
                name="id"
                value="<?php echo $row['id']; ?>">

                <!-- Imagen actual -->

                <label class="form-label">
                    Imagen actual
                </label>

                <img
                src="<?php echo $row['imagen']; ?>"
                class="preview">

                <!-- Cambiar imagen -->

                <label class="form-label">
                    Cambiar imagen
                </label>

                <input
                type="file"
                name="imagen"
                class="form-control">

                <!-- Descripción -->

                <label class="form-label">
                    Descripción
                </label>

                <textarea
                name="descripcion"
                class="form-control"
                required><?php echo $row['descripcion']; ?></textarea>

                <!-- Fecha -->

                <label class="form-label">
                    Fecha de vencimiento
                </label>

                <input
                type="date"
                name="fecha_vencimiento"
                class="form-control"
                value="<?php echo $row['fecha_vencimiento']; ?>"
                required>

                <!-- Estado -->

                <label class="form-label">
                    Estado
                </label>

                <select
                name="estado"
                class="form-control">

                    <option value="disponible"
                    <?php if($row['estado'] == 'disponible') echo 'selected'; ?>>

                        Disponible

                    </option>

                    <option value="vencido"
                    <?php if($row['estado'] == 'vencido') echo 'selected'; ?>>

                        Vencido

                    </option>

                </select>

                <button class="btn-guardar">

                    Guardar cambios

                </button>

            </form>

        </div>

    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

/* =========================
   ALERTA IMAGEN
========================= */

const inputImagen = document.querySelector('input[name="imagen"]');

if(inputImagen){

    inputImagen.addEventListener('change', function(){

        if(this.files.length > 0){

            Swal.fire({

                icon: 'success',

                title: 'Imagen subida ✨',

                text: 'La imagen fue cargada correctamente',

                background: '#111827',

                color: '#fff',

                confirmButtonColor: '#c026d3'

            });

        }

    });

}

/* =========================
   ALERTA GUARDAR
========================= */

const formEditar = document.querySelector('form');

formEditar.addEventListener('submit', function(e){

    e.preventDefault();

    Swal.fire({

        icon: 'success',

        title: 'Actualizado con éxito 🚀',

        text: 'El producto fue actualizado correctamente',

        background: '#111827',

        color: '#fff',

        confirmButtonColor: '#06b6d4'

    }).then(() => {

        formEditar.submit();

    });

});

</script>

</body>
</html>