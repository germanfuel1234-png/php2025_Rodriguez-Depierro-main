<?php
// ==========================================================
// ARCHIVO: actualizar.php
// OBJETIVO: Procesar el formulario de modificación y actualizar el registro en BBDD.
// ==========================================================

// Incluimos el archivo de conexión
include 'conexion.php'; 

// Verificamos que se haya presionado el botón 'Actualizar' del formulario
if (isset($_POST['Actualizar'])) {
    
    // (POST)
    
    // El ID viene como campo oculto y es crucial para el WHERE de la consulta UPDATE
    $id = intval($_POST['id']);
    
    // mysqli_real_escape_string() limpia el texto para prevenir inyecciones SQL
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conexion, $_POST['apellido']);
    
    // Obtenemos la ruta de la foto que ESTABA guardada, para saber si borrarla después
    $foto_path_anterior = mysqli_real_escape_string($conexion, $_POST['foto_path_anterior']);

    // Inicializamos las variables
    // $query_set contendrá la parte de la consulta SQL que define qué campos actualizar
    $query_set = "nombre = '$nombre', apellido = '$apellido'";
    
    // Por defecto, la ruta de la foto se mantiene igual
    $ruta_final_bbdd = $foto_path_anterior;
    
    // Bandera para saber si se subió una nueva foto (y si debemos borrar la antigua)
    $eliminar_foto_anterior = false;

    // ----------------------------------------------------------
    // 2. Manejar la subida de una nueva foto (Si el usuario subió un archivo nuevo)
    // ----------------------------------------------------------    
        // Verificamos si existe el archivo subido y si no hubo errores en la subida
        if (isset($_FILES['foto_nueva']) && $_FILES['foto_nueva']['error'] == UPLOAD_ERR_OK) {
               
            // Define la carpeta física donde se guardarán todas las fotos.
            $directorio_destino = "fotos/";
            
            // Extrae solo el nombre del archivo subido por el usuario (ej: "MiFoto.JPG").
            // Esto se hace para limpiar la ruta y asegurar que solo tengamos el nombre del fichero.
            $nombre_archivo = basename($_FILES["foto_nueva"]["name"]);
            
            // Extrae la extensión del archivo (ej: "jpg", "png") y la convierte a minúsculas.
            // Esto es vital para estandarizar el guardado y asegurar que la ruta coincida 
            // con el nombre final del archivo, sin importar cómo la haya escrito el usuario.
            $extension = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));
            
            // Generamos un nombre único basado en tiempo y un ID único para evitar colisiones.
            // Esto evita que si dos usuarios suben un archivo llamado "foto.jpg", uno sobrescriba al otro.
            $nombre_unico = time() . "_" . uniqid() . "." . $extension;
            
            // Rutas completas:
            // Ruta del archivo en el sistema de archivos del servidor
            $ruta_final_servidor = $directorio_destino . $nombre_unico;
            // Ruta que se guardará en la base de datos (y luego se usará en el <img src="...">)
            $ruta_final_bbdd = $directorio_destino . $nombre_unico;
        // Mover el archivo subido desde el temporal a la carpeta destino
        if (move_uploaded_file($_FILES["foto_nueva"]["tmp_name"], $ruta_final_servidor)) {
            
            // Si la subida es exitosa, modificamos la parte del SET en la consulta SQL
            $query_set .= ", foto = '$ruta_final_bbdd'";
            
            // Activamos la bandera para eliminar la foto antigua (si existe)
            $eliminar_foto_anterior = true; 
            
        } else {
            // Si falla el movimiento (a menudo por permisos de carpeta)
            header('location: modificar.php?id=' . $id . '&error=' . urlencode('Error al subir la nueva foto. Verifique permisos.'));
            exit();
        }
    }
    
    // ----------------------------------------------------------
    // 3. Crear y Ejecutar la consulta SQL de actualización
    // ----------------------------------------------------------

    // Construimos la consulta UPDATE usando el $query_set dinámico
    $sql_update = "UPDATE personas SET $query_set WHERE id = $id";
    
    // 4. Ejecutar la consulta
    if (mysqli_query($conexion, $sql_update)) {
        
        // 5. Borrar el archivo de la foto antigua (solo si se subió una nueva)
        // El condicional es importante: verifica que se haya subido una nueva foto ($eliminar_foto_anterior)
        // y que el archivo antiguo realmente exista en el disco (file_exists())
        if ($eliminar_foto_anterior && file_exists($foto_path_anterior) && $foto_path_anterior != 'ruta/default.jpg') {
            // unlink() es la función PHP que borra archivos del sistema de ficheros.
            unlink($foto_path_anterior); 
        }

        // Cierre de conexión y redirección de éxito
        mysqli_close($conexion);
        header('location: index.php?mensaje=Persona modificada con éxito');
        exit();
        
    } else {
        // Manejo de Error de la Base de Datos
        $mensaje_error = "Error al actualizar en la base de datos: " . mysqli_error($conexion);
        mysqli_close($conexion);
        
        // Redirigir de nuevo al formulario de modificación con el mensaje de error
        header('location: modificar.php?id=' . $id . '&error=' . urlencode($mensaje_error));
        exit();
    }
    
} else {
    // Si se accede a este archivo sin el método POST (sin el botón 'Actualizar'), redirigir al índice
    header('location: index.php');
    exit();
}
?>