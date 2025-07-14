<?php include("template/cabecera.php"); ?>

<?php include("administrador/config/bd.php"); ?>

<!-- Selecciona todos los libros de la base de datos -->
<?php
$sentenciaSQL = $conexion->prepare("SELECT * FROM Libros");
$sentenciaSQL->execute();
$listaLibros = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);  
?>

<!-- Mostrar los libros en una cuadrícula -->

<?php if(count($listaLibros) == 0) { ?>
<div class="alert alert-danger" role="alert">
    No hay libros disponibles. <?php }?>

<div class="container">
    <div class="row">
        <?php foreach($listaLibros as $libro) { ?>
        <div class="col-md-3 mb-4">
            <div class="card">
                <img class="card-img-top" src="img/<?php echo $libro["imagen"]; ?>" height="300" alt="imagen de libro">
                <div class="card-body">
                    <h4 class="card-title"><?php echo $libro["nombre"]; ?></h4> 
                    <a name="" id="" class="btn btn-primary" href="pdf/<?php echo htmlspecialchars($libro["pdf"]); ?>" target="_blank" role="button"> Ver más </a>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>



<?php include("template/pie.php"); ?>