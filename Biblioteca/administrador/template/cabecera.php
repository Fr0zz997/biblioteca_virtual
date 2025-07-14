<?php $url="http://".$_SERVER["HTTP_HOST"]."/biblioteca" ?>

<!-- Verifica si el usuario esta conectado, si no lo redirige a logearse -->
<?php session_start(); 
if (!isset($_SESSION['usuario'])) { 
    header("Location: ".$url."/administrador/index.php");
    exit;
}?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración</title>
    <link rel="stylesheet" href="<?php echo $url; ?>/css/bootstrap.min_admin.css">
    <link rel="stylesheet" href="<?php echo $url; ?>/css/custom.css">
</head>
<body>  



<!-- NAVEGACION -->



<nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Administración</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarColor01">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link active" href="<?php echo $url;?>/administrador/inicio.php">Inicio
            <span class="visually-hidden">(current)</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo $url;?>/administrador/seccion/productos.php">Libros</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo $url;?>/administrador/registrar.php">Registrar Admin</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo $url; ?>">Ver sitio web</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo $url;?>/administrador/seccion/cerrar.php">Cerrar sesión</a>
        </li>
        
    <?php if(isset($_SESSION['usuario'])): ?>
      <span class="navbar-text brillo">
        Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['usuario']); ?></strong>
      </span>
    <?php endif; ?>
  
      </ul> 
    </div>
  </div>
</nav>

<div class="container fondo">
    <br/>
    <div class="row">