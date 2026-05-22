<?php
include("conexion.php");

$sql = "SELECT * FROM productos";
$res = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="container mt-5">
<div class="row">

<?php while($row = $res->fetch_assoc()) { ?>

<div class="col-12 col-md-6 col-lg-4 mb-4">
    <div class="card h-100 shadow">

        <div class="card-body">
            <h5><?php echo $row['nombre']; ?></h5>
            <p><?php echo $row['descripcion']; ?></p>
            <p><b>Vence:</b> <?php echo $row['fecha_vencimiento']; ?></p>
            <p><b>Estado:</b> <?php echo $row['estado']; ?></p>
        </div>

        <div class="card-footer">
            <a href="editar.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
            <a href="eliminar.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
        </div>

    </div>
</div>

<?php } ?>

</div>
</div>

</body>
</html>
