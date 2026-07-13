<?php
// Incluir la conexión a la base de datos
include 'conexion.php'; 

// Verificar si se recibieron IDs para eliminar
if (isset($_POST['Eliminar']) && isset($_POST['ids_a_eliminar']) && is_array($_POST['ids_a_eliminar'])) {
    
    // 1. Recoger el array de IDs seleccionadas
    $ids_a_eliminar = $_POST['ids_a_eliminar'];
    
    // 2. Limpiar las IDs (convertir el array de IDs a números enteros limpios)
    $ids_limpias = array_map('intval', $ids_a_eliminar);
    
    // 3. Crear una cadena de IDs separadas por comas (Ej: 1,2,5,8)
    $lista_ids = implode(',', $ids_limpias);
    
    // 4. Consulta SQL para eliminar las personas seleccionadas
    // Usamos 'IN' para eliminar múltiples IDs a la vez
    $sql_delete = "DELETE FROM personas WHERE id IN ($lista_ids)";
    
    // 5. Ejecutar la consulta
    if (mysqli_query($conexion, $sql_delete)) {
        
        // Cierre de conexión
        mysqli_close($conexion);

        // Redirigir al panel principal con mensaje de éxito
        header('location: index.php?mensaje=Registros eliminados con éxito');
        exit();
        
    } else {
        // Error en la base de datos
        $mensaje_error = "Error al eliminar registros: " . mysqli_error($conexion);
        mysqli_close($conexion);
        header('location: index.php?error=' . urlencode($mensaje_error));
        exit();
    }
    
} else {
    // No se seleccionó ninguna casilla o se accedió al archivo directamente
    header('location: index.php?aviso=No se seleccionó ninguna persona para eliminar');
    exit();
}
?>