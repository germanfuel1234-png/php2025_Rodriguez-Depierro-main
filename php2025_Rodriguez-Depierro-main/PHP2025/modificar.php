<?php
// Incluir la conexión a la base de datos y la sesión (si es necesario)
include 'conexion.php'; 

// 1. Obtener el ID de la persona a modificar desde la URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('location: index.php?error=ID de persona no especificado.');
    exit();
}

$id = intval($_GET['id']);

// 2. Consultar los datos actuales de la persona
$sql_select = "SELECT id, nombre, apellido, foto FROM personas WHERE id = $id";
$resultado = mysqli_query($conexion, $sql_select);

if (!$fila = mysqli_fetch_assoc($resultado)) {
    header('location: index.php?error=Persona no encontrada.');
    exit();
}

// Los datos de la persona están ahora en la variable $fila
$nombre_actual = htmlspecialchars($fila['nombre']);
$apellido_actual = htmlspecialchars($fila['apellido']);
$foto_actual_path = htmlspecialchars($fila['foto']); // Ruta guardada en BBDD

// 3. Liberar y cerrar conexión (si no se va a usar más)
mysqli_free_result($resultado);
// No cierro la conexión por si se necesita para otras funciones, pero es buena práctica si no hay más consultas.

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modificar Persona ID: <?php echo $id; ?></title>
    <style>
        .foto-preview { width: 100px; height: 100px; object-fit: cover; border-radius: 5px; margin-bottom: 10px; }
    </style>
</head>

<body>
    <main>
        <section id="modificarPersona" class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7 col-xl-8">
                    
                    <h2>MODIFICAR PERSONA ID: <?php echo $id; ?></h2>
                    
                    <form action="actualizar.php" method="post" enctype="multipart/form-data">
                        
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="hidden" name="foto_path_anterior" value="<?php echo $foto_actual_path; ?>">

                        <div>
                            <label for="nombre">Nombre</label>
                            <input name="nombre" id="nombre" type="text" value="<?php echo $nombre_actual; ?>" required>
                        </div> 
                        
                        <div>
                            <label for="apellido">Apellido</label>
                            <input name="apellido" id="apellido" type="text" value="<?php echo $apellido_actual; ?>" required>
                        </div>

                        <div>
                            <label>Foto Actual:</label>
                            <div>
                                <img src="/PHP2025/<?php echo $foto_actual_path; ?>" alt="Foto Actual" class="foto-preview">
                            </div>
                        </div>

                        <div>
                            <label for="fotoPersona">Subir Nueva Foto (Dejar vacío para mantener la actual)</label>
                            <input name="foto_nueva" id="fotoPersona" type="file">
                        </div>

                        <div>
                            <button type="submit" name="Actualizar" >Guardar Cambios</button>
                        </div>
                    </form>

                    <a href="index.php">Volver al Panel</a>
                
                </div>
            </div>
        </section>
    </main>
</body>
</html>