<?php
// Incluir la conexión a la base de datos
include 'conexion.php'; 

// 1. Verificar si el formulario fue enviado (botón 'Alta' presionado)
if (isset($_POST['Alta'])) {
    
    // 2. Recoger y limpiar los datos de texto
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conexion, $_POST['apellido']);
    
    // 3. Manejar la subida de la foto
    
    // a. Definir la carpeta donde se guardarán las fotos
    $directorio_destino = "fotos/";
    
    // b. Obtener el nombre y la extensión original del archivo
    $nombre_archivo = basename($_FILES["foto"]["name"]);
    $extension = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));
    
    // c. Generar un nombre de archivo único para evitar que se sobrescriban
    $nombre_unico = time() . "_" . uniqid() . "." . $extension;
    
    // d. Ruta completa del archivo en el servidor
    $ruta_final_servidor = $directorio_destino . $nombre_unico;
    
    // e. Ruta que se guardará en la base de datos
    $ruta_final_bbdd = $directorio_destino . $nombre_unico; // Ej: fotos/1678886400_654321.jpg

    $subida_ok = 1;
    
    // f. Verificar si el archivo es una imagen real
    $check = getimagesize($_FILES["foto"]["tmp_name"]);
    if($check === false) {
        $subida_ok = 0;
        $mensaje_error = "El archivo no es una imagen.";
    }
    
    // g. Mover el archivo subido de la carpeta temporal a la carpeta destino
    if ($subida_ok == 1) {
        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $ruta_final_servidor)) {
            // El archivo se movió con éxito, procedemos a insertar en la BBDD
            
            // 4. Crear la consulta SQL de inserción
            $sql_insert = "INSERT INTO personas (nombre, apellido, foto) 
                           VALUES ('$nombre', '$apellido', '$ruta_final_bbdd')";
            
            // 5. Ejecutar la consulta
            if (mysqli_query($conexion, $sql_insert)) {
                
                // Redirigir al panel principal con mensaje de éxito
                header('location: index.php?mensaje=Persona guardada con éxito');
                exit();
                
            } else {
                // Error en la base de datos
                $mensaje_error = "Error al guardar en la base de datos: " . mysqli_error($conexion);
            }
            
        } else {
            // Error al mover el archivo (problema de permisos en la carpeta 'fotos')
            $mensaje_error = "Hubo un error al subir el archivo. Verifique los permisos de la carpeta 'fotos'.";
        }
    }
    
    // Si llega aquí, es porque hubo un error, redirigimos al formulario con el error
    // Nota: Es mejor manejar los errores en el formulario para mostrarlos al usuario.
    // Por ahora, solo redirigimos:
    header('location: formulario.php?error=' . urlencode($mensaje_error));
    exit();

} else {
    // Si se accedió al archivo directamente sin POST, redirigir
    header('location: index.php');
    exit();
}
?>