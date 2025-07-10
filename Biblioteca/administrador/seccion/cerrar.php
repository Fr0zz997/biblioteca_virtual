<?php
session_start();
session_unset();
session_destroy();
header("Location: /Biblioteca/administrador/index.php");
exit();
?>