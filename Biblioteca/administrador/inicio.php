<?php include("../administrador/template/cabecera.php"); ?>

        <div class="jumbotron">
        <h1 class="display-3"> Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['usuario']); ?></strong></h1>
        <p class="lead">Administración de la biblioteca virtual</p>
        <hr class="my-2">
        <!-- <p>More info</p> -->
        <p class="lead">
            <a class="btn btn-primary btn-lg" href="seccion/productos.php" role="button">Administrar libros</a>
        </p>
        </div>
<?php include("../administrador/template/pie.php"); ?>


