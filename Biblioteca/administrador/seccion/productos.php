
<?php include("../template/cabecera.php"); ?>

<?php 
date_default_timezone_set('America/La_Paz');

// Variables para manejar los datos del formulario
$nombrePDF = "";
if(isset($_FILES["txtPDF"]) && $_FILES["txtPDF"]["name"] != "") {
    $fecha = new DateTime();
    $nombrePDF = $fecha->getTimestamp() . "_" . $_FILES["txtPDF"]["name"];
    $tmpPDF = $_FILES["txtPDF"]["tmp_name"];
    move_uploaded_file($tmpPDF, "../../pdf/" . $nombrePDF);
}
$txtID  = (isset($_POST['txtID'])) ? $_POST['txtID'] : "";
$txtNombre = (isset($_POST['txtNombre'])) ? $_POST['txtNombre'] : "";
$accion = (isset($_POST['accion'])) ? $_POST['accion'] : ""; 
$txtImagen = (isset($_FILES['txtImagen']['name'])) ? $_FILES['txtImagen']['name'] : "";


// Conexión a la base de datos
$host="localhost";
$bd="sitio";
$usuario="root";
$contraseña="";



include("../config/bd.php"); 

// Manejo de acciones del formulario
switch($accion) {
    case "Agregar":
        $sentenciaSQL = $conexion->prepare("INSERT INTO Libros (nombre, imagen, fecha, admin, pdf) VALUES (:nombre, :imagen, :fecha, :admin, :pdf);");
        $sentenciaSQL->bindParam(':nombre', $txtNombre);
        $fechaActual = date("Y-m-d H:i:s");
        $sentenciaSQL->bindParam(':fecha', $fechaActual);
        $admin = $_SESSION['usuario'];
        $sentenciaSQL->bindParam(':admin', $admin);
        $fecha = new DateTime();
        $nombreArchivo = ($txtImagen != "") ? $fecha->getTimestamp() . "_" . $_FILES["txtImagen"]["name"] : "imagen.jpg";   
        $tmpImagen = $_FILES["txtImagen"]["tmp_name"];
        if($tmpImagen != ""){
            move_uploaded_file($tmpImagen, "../../img/" . $nombreArchivo);
        } else {
            $nombreArchivo = "imagen.jpg";
        }

      

        $sentenciaSQL->bindParam(':imagen', $nombreArchivo);
        $sentenciaSQL->bindParam(':pdf', $nombrePDF);
        $sentenciaSQL->execute();

        header("Location: productos.php");
        break;
    case "Modificar":
        $sentenciaSQL = $conexion->prepare("UPDATE Libros SET nombre = :nombre WHERE id = :id");
        $sentenciaSQL->bindParam(':nombre', $txtNombre);
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();

       


        if($txtImagen != ""){
        
        $fecha = new DateTime();
        $nombreArchivo = ($txtImagen != "") ? $fecha->getTimestamp() . "_" . $_FILES["txtImagen"]["name"] : "imagen.jpg";
        $tmpImagen = $_FILES["txtImagen"]["tmp_name"];
        move_uploaded_file($tmpImagen, "../../img/" . $nombreArchivo);

        $sentenciaSQL = $conexion->prepare("SELECT imagen FROM Libros WHERE id = :id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();
        $libro = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

        if(isset($libro["imagen"]) && ($libro["imagen"] != "imagen.jpg")) {
            if(file_exists("../../img/" . $libro["imagen"])) {
                unlink("../../img/" . $libro["imagen"]);
            }
        }



        $sentenciaSQL = $conexion->prepare("UPDATE Libros SET imagen = :imagen WHERE id = :id");
        $sentenciaSQL->bindParam(':imagen', $nombreArchivo);
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();

        }
        break;
    case "Cancelar":
        header("Location: productos.php");
        $txtID = "";
        $txtNombre = "";
        $txtImagen = "";
        break;
        
    case "Seleccionar":
        $sentenciaSQL = $conexion->prepare("SELECT * FROM Libros WHERE id = :id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();
        $libro = $sentenciaSQL->fetch(PDO::FETCH_LAZY);
        $txtNombre = $libro['nombre'];
        $txtImagen = $libro['imagen'];
        break;

    case "Borrar":

        $sentenciaSQL = $conexion->prepare("SELECT imagen, pdf FROM Libros WHERE id = :id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();
        $libro = $sentenciaSQL->fetch(PDO::FETCH_LAZY);

        if(isset($libro["imagen"]) && ($libro["imagen"] != "imagen.jpg")) {
            if(file_exists("../../img/" . $libro["imagen"])) {
                unlink("../../img/" . $libro["imagen"]);
            }
        }

        if(isset($libro["pdf"]) && ($libro["pdf"] != "")) {
            if(file_exists("../../pdf/" . $libro["pdf"])) {
                unlink("../../pdf/" . $libro["pdf"]);
            }
        }


        $sentenciaSQL = $conexion->prepare("DELETE FROM Libros WHERE id = :id");
        $sentenciaSQL->bindParam(':id', $txtID);
        $sentenciaSQL->execute();
        
        break;
}

// Consulta para obtener la lista de libros
$sentenciaSQL = $conexion->prepare("SELECT * FROM Libros");
$sentenciaSQL->execute();
$listaLibros = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);   
?>

<div class="col-md-5">
    <div class="card">
        <div class="card-header">
            Datos de Libros
        </div>
        <div class="card-body">

    <form method="POST" enctype="multipart/form-data" >

    <div class = "form-group">
    <label for="txtID">ID:</label>
    <input type="text" class="form-control" value="<?php echo $txtID;  ?>" name="txtID" id="txtID" placeholder="ID" required readonly>
    </div>

    <div class = "form-group">
    <label for="txtNombre">Nombre:</label>
    <input type="text" class="form-control"  value="<?php echo $txtNombre;  ?>" name="txtNombre" id="txtNombre" required placeholder="Nombre del Libro">
    </div>

    <div class = "form-group">
    <label for="txtImagen">Imagen:</label>

    
    <?php if($txtImagen != "") { ?>
        <br/>
        <img class="img-thumbnail rounded" src="../../img/<?php echo $txtImagen; ?>" width="100" alt="">
        <br/>

        <?php } ?>

    <input type="file" class="form-control" name="txtImagen" id="txtImagen">
   </div>

   <div class="form-group">
    <label for="txtPDF">Libro (PDF):</label>
    <input type="file" class="form-control" name="txtPDF" id="txtPDF" accept="application/pdf">
    </div>

    
    <div class="btn-group" role="group" aria-label="">
        <button type="submit" name="accion" <?php echo ($accion=="Seleccionar")?"disabled":"";?> value="Agregar" class="btn btn-success">Agregar</button>
        <button type="submit" name="accion" <?php echo ($accion!="Seleccionar")?"disabled":"";?> value="Modificar" class="btn btn-warning">Modificar</button>
        <button type="submit" name="accion" <?php echo ($accion!="Seleccionar")?"disabled":"";?> value="Cancelar" class="btn btn-info">Cancelar</button>
    </div>

    </form>
    
        </div>
    </div>


    
</div>

<div class="col-md-7">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Imagen</th>
                <th>PDF</th>
                <th>Fecha</th>
                <th>Agregado por</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($listaLibros as $libro) {  ?>
            <tr>
                <td><?php echo $libro["id"]; ?></td>
                <td><?php echo $libro["nombre"]; ?></td>

                <td>
                <img class="img-thumbnail rounded" src="../../img/<?php echo $libro["imagen"]; ?>" width="50" alt="">    
                
                </td>

            <td>
                    <?php if(!empty($libro["pdf"])): ?>
                        <a href="../../pdf/<?php echo htmlspecialchars($libro["pdf"]); ?>" target="_blank">Ver PDF</a>
                    <?php else: ?>
                        No disponible
                    <?php endif; ?>
                </td>
                        
               <td><?php $fecha = new DateTime($libro["fecha"]); echo $fecha->format('d/m/Y h:i A'); ?>
                </td>

                <td><?php echo $libro["admin"]; ?></td>


        <td>
    <form method="post" class="form-borrar" style="display:inline;">
        <input type="hidden" name="txtID" value="<?php echo $libro["id"]; ?>" />
       <button type="button" class="btn btn-danger btn-borrar">Borrar</button>
    </form>

    <form method="post" style="display:inline;">
        <input type="hidden" name="txtID" value="<?php echo $libro["id"]; ?>" />
        <button type="submit" name="accion" value="Seleccionar" class="btn btn-primary">Seleccionar</button>
    </form>
</td>
                
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>



<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-borrar').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('form');
            
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡Esta acción no se puede deshacer!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, borrar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Creamos un input hidden con el nombre "accion" y valor "Borrar"
                    const inputAccion = document.createElement('input');
                    inputAccion.type = 'hidden';
                    inputAccion.name = 'accion';
                    inputAccion.value = 'Borrar';
                    form.appendChild(inputAccion);

                    form.submit();
                }
            });
        });
    });
});
</script>




<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php include("../template/pie.php"); ?>
</body>
</html>





