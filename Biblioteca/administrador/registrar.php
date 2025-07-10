<?php

$host="localhost";
$bd="sitio";
$usuario="root";
$contraseña="";

include("../administrador/template/cabecera.php"); 
include("config/bd.php"); 



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

    // Verifica si el usuario ya existe
    $sentencia = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = :usuario");
    $sentencia->bindParam(':usuario', $usuario);
    $sentencia->execute();

    if ($sentencia->rowCount() > 0) {
        $mensaje = "El usuario ya existe.";
    } else {
        // Inserta el nuevo usuario
        $sentencia = $conexion->prepare("INSERT INTO usuarios (usuario, contrasena) VALUES (:usuario, :contrasena)");
        $sentencia->bindParam(':usuario', $usuario);
        $sentencia->bindParam(':contrasena', $contrasena_hash);
        if ($sentencia->execute()) {
            $mensaje = "Administrador registrado correctamente.";
        } else {
            $mensaje = "Error al registrar el administrador.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Administrador</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
            <h3 class="mb-4 text-center">Registrar Administrador</h3>
            <?php if (isset($mensaje)) { ?>
                <div class="alert alert-info" role="alert">
                    <?php echo $mensaje; ?>
                </div>
            <?php } ?>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuario</label>
                    <input type="text" class="form-control" name="usuario" id="usuario" required>
                </div>
                <div class="mb-3">
                    <label for="contrasena" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" name="contrasena" id="contrasena" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-success">Registrar</button>
                </div>
            </form>
            <div class="mt-3 text-center">
                <a href="index.php">Volver al login</a>
            </div>
        </div>
    </div>
</body>
</html>

