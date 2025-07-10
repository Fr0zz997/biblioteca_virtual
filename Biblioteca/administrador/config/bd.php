<?php   try {
    $conexion = new PDO("mysql:host=$host;dbname=$bd", $usuario, $contraseña);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
} ?> 
