<?php
include("conexion.php");

$registroExitoso = false;
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $correo = trim($_POST['correo']);
    $password = $_POST['password'];

    if (!preg_match("/@pascualbravo\.edu\.co$/", $correo)) {

        $error = "Solo se permiten correos institucionales";

    } elseif(strlen($password) < 6){

        $error = "La contraseña debe tener mínimo 6 caracteres";

    } else {

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Verificar si el correo ya existe
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE correo = ?");
        $stmt->bind_param("s", $correo);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {

            $error = "Ese correo ya está registrado";

        } else {

            $insert = $conn->prepare("INSERT INTO usuarios (correo, password) VALUES (?, ?)");

            $insert->bind_param("ss", $correo, $passwordHash);

            if($insert->execute()){

                $registroExitoso = true;

            } else {

                $error = "Error al registrar";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Registro</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

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

.registro-card{

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

.titulo{

    font-size:32px;

    font-weight:700;

    text-align:center;

    margin-bottom:10px;
}

.subtitulo{

    text-align:center;

    color:#cbd5e1;

    margin-bottom:30px;
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

.btn-register{

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

.btn-register:hover{

    transform:scale(1.02);
}

.login-link{

    text-align:center;

    margin-top:20px;

    color:#cbd5e1;
}

.login-link a{

    color:#22d3ee;

    text-decoration:none;
}

.alert{

    border-radius:14px;
}

/* MODAL */

.modal-exito{

    position:fixed;

    top:0;
    left:0;

    width:100%;
    height:100%;

    background:rgba(0,0,0,0.6);

    display:flex;

    justify-content:center;
    align-items:center;

    z-index:9999;

    animation:fadeIn 0.4s ease;
}

.modal-contenido{

    background:rgba(15,23,42,0.95);

    border:1px solid rgba(255,255,255,0.1);

    padding:40px;

    border-radius:24px;

    text-align:center;

    color:white;

    width:90%;
    max-width:380px;

    backdrop-filter:blur(20px);

    box-shadow:0 0 40px rgba(0,0,0,0.5);

    animation:zoom 0.3s ease;
}

.check{

    width:80px;
    height:80px;

    background:linear-gradient(
    135deg,
    #22c55e,
    #16a34a
    );

    border-radius:50%;

    display:flex;

    justify-content:center;
    align-items:center;

    margin:auto;

    font-size:38px;

    margin-bottom:20px;
}

.modal-contenido h2{

    font-weight:700;

    margin-bottom:10px;
}

.modal-contenido p{

    color:#cbd5e1;

    margin-bottom:25px;
}

.modal-contenido button{

    border:none;

    padding:12px 25px;

    border-radius:14px;

    background:linear-gradient(
    90deg,
    #c026d3,
    #06b6d4
    );

    color:white;

    font-weight:600;

    transition:0.3s;
}

.modal-contenido button:hover{

    transform:scale(1.05);
}

@keyframes fadeIn{

    from{
        opacity:0;
    }

    to{
        opacity:1;
    }
}

@keyframes zoom{

    from{
        transform:scale(0.7);
        opacity:0;
    }

    to{
        transform:scale(1);
        opacity:1;
    }
}

</style>

</head>

<body>

<div class="registro-card">

    <h1 class="titulo">
        Crear cuenta
    </h1>

    <p class="subtitulo">
        Regístrate con tu correo institucional
    </p>

    <?php if($error): ?>

        <div class="alert alert-danger">
            <?php echo $error; ?>
        </div>

    <?php endif; ?>

    <form method="POST">

        <input
        type="email"
        name="correo"
        class="form-control mb-3"
        placeholder="correo@pascualbravo.edu.co"
        required>

        <input
        type="password"
        name="password"
        class="form-control mb-4"
        placeholder="Contraseña"
        required>

        <button class="btn-register">
            Registrarse
        </button>

    </form>

    <div class="login-link">

        ¿Ya tienes cuenta?

        <a href="login.php">
            Inicia sesión
        </a>

    </div>

</div>

<?php if($registroExitoso): ?>

<div class="modal-exito">

    <div class="modal-contenido">

        <div class="check">
            ✔
        </div>

        <h2>
            Registro exitoso
        </h2>

        <p>
            Tu cuenta fue creada correctamente
        </p>

        <button onclick="redirigirLogin()">
            Ir al login
        </button>

    </div>

</div>

<script>

function redirigirLogin(){

    window.location.href = "login.php";
}

</script>

<?php endif; ?>

</body>
</html>