<?php

$host="localhost";
$bd="sitio";
$usuario="root";
$contraseña="";
session_start();
include("config/bd.php"); 

// Verifica si el usuario ya está autenticado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    // Buscar el usuario en la base de datos
    $sentencia = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = :usuario LIMIT 1");
    $sentencia->bindParam(':usuario', $usuario);
    $sentencia->execute();
    $usuarioDB = $sentencia->fetch(PDO::FETCH_ASSOC);

    if ($usuarioDB && password_verify($contrasena, $usuarioDB['contrasena'])) {
        $_SESSION['usuario'] = $usuarioDB['usuario']; // Guarda el usuario en la sesión
        header("Location: ../administrador/inicio.php");
        exit();
    } else {
        $mensaje = "Usuario o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
            <h3 class="mb-4 text-center">Iniciar Sesión</h3>
            <?php if (isset($mensaje)) { ?>
                <div class="alert alert-danger" role="alert">
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
                    <button type="submit" class="btn btn-primary">Ingresar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>