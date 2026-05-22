<?php
session_start();
include("conexion.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $correo = trim($_POST['correo']);
    $password = $_POST['password'];

    // Buscar usuario
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {

        $user = $resultado->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            $_SESSION['user'] = $user;

            header("Location: dashboard.php");
            exit();

        } else {

            $error = "Contraseña incorrecta";

        }

    } else {

        $error = "Usuario no encontrado";

    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Fuente -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">

<style>

*{
    font-family:'Poppins',sans-serif;
}

body{

    height:100vh;

    display:flex;

    justify-content:center;

    align-items:center;

    background:
    linear-gradient(
    135deg,
    #050816,
    #111827,
    #240046
    );

    overflow:hidden;

    position:relative;
}

/* Glow fondo */

body::before{

    content:'';

    position:absolute;

    width:350px;

    height:350px;

    background:#c026d3;

    border-radius:50%;

    filter:blur(140px);

    top:-100px;

    left:-100px;

    opacity:0.4;
}

body::after{

    content:'';

    position:absolute;

    width:350px;

    height:350px;

    background:#06b6d4;

    border-radius:50%;

    filter:blur(140px);

    bottom:-100px;

    right:-100px;

    opacity:0.3;
}

/* Card */

.login-card{

    width:100%;

    max-width:420px;

    background:rgba(255,255,255,0.08);

    border:1px solid rgba(255,255,255,0.1);

    backdrop-filter:blur(20px);

    border-radius:28px;

    padding:40px;

    color:white;

    position:relative;

    z-index:2;

    box-shadow:0 0 40px rgba(0,0,0,0.4);
}

/* Títulos */

.titulo{

    font-size:34px;

    font-weight:700;

    text-align:center;

    margin-bottom:10px;
}

.subtitulo{

    text-align:center;

    color:#cbd5e1;

    margin-bottom:30px;
}

/* Inputs */

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

/* Botón */

.btn-login{

    width:100%;

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
}

.btn-login:hover{

    transform:scale(1.02);

    box-shadow:0 0 20px rgba(192,38,211,0.4);
}

/* Link */

.register-link{

    text-align:center;

    margin-top:20px;

    color:#cbd5e1;
}

.register-link a{

    color:#22d3ee;

    text-decoration:none;
}

/* Alert */

.alert{

    border-radius:14px;
}

</style>

</head>

<body>

<div class="login-card">

    <h1 class="titulo">
        Bienvenido
    </h1>

    <p class="subtitulo">
        Inicia sesión para continuar
    </p>

    <!-- Error -->
    <?php if($error): ?>

        <div class="alert alert-danger text-center">

            <?php echo $error; ?>

        </div>

    <?php endif; ?>

    <form method="POST">

        <!-- Correo -->
        <input
        type="email"
        name="correo"
        class="form-control mb-3"
        placeholder="correo@pascualbravo.edu.co"
        required>

        <!-- Contraseña -->
        <input
        type="password"
        name="password"
        class="form-control mb-4"
        placeholder="Contraseña"
        required>

        <!-- Botón -->
        <button class="btn-login">

            Ingresar

        </button>

    </form>

    <!-- Registro -->
    <div class="register-link">

        ¿No tienes cuenta?
        <a href="registro.php">

            Regístrate

        </a>

    </div>

</div>

</body>
</html>