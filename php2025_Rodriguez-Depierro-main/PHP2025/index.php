<?php
// Bloque PHP inicial (Sesión y Conexión)
session_name('back');
session_start();

// Si la variable de sesión 'is_logged' NO existe o su valor es 0, redirige a login.
if (!isset($_SESSION['is_logged']) || $_SESSION['is_logged'] == 0) {
    header('location: login.php?mensaje=Se ha desconectado del sistema');
    exit(); // Es crucial usar exit() después de un header location
}

// Incluimos la conexión a la base de datos
include 'conexion.php'; 

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Personas - PHP</title>
    <style>
        /* Estilos CSS básicos para la tabla */
        body { font-family: Arial, sans-serif; margin: 20px; }
        /* Tabla más ancha para incluir la columna de acciones */
        table { width: 90%; border-collapse: collapse; margin-top: 20px; } 
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .foto-mini { 
            width: 50px; 
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    
    <header>
        <nav>
            <a href="index.php">Inicio</a>
            <a href="logout.php" >Salir</a>
        </nav>
        <hr>
    </header>
    
    <section>
        <h1>Bienvenido al Panel de Personas</h1>
        
        <?php
            // Mostrar información de la sesión
            echo "La variable de sesión es: <strong>" . session_id() . "</strong>";
            echo '<br>';
            echo "El nombre de Usuario es: <strong>" . $_SESSION['Nombre'] . "</strong>";
            echo '<br>';
            echo "El ID de Usuario es: <strong>" . $_SESSION['IDUsuario'] . "</strong>";
            echo '<hr>';
            
            // Mostrar mensajes de éxito o error
            if (isset($_GET['mensaje'])) {
                echo '<p style="color: green; font-weight: bold;">' . htmlspecialchars($_GET['mensaje']) . '</p>';
            }
            if (isset($_GET['error'])) {
                echo '<p style="color: red; font-weight: bold;">Error: ' . htmlspecialchars($_GET['error']) . '</p>';
            }
        ?>

        <h2>Listado de la Tabla 'personas'</h2>
        
        <?php
            // 1. CONSULTAR DATOS DE LA TABLA PERSONAS
            $sql_select = "SELECT id, nombre, apellido, foto FROM personas";
            $resultado = mysqli_query($conexion, $sql_select);

            if (mysqli_num_rows($resultado) > 0) {
        ?>
            <a href="formulario.php" style="display: block; margin-bottom: 10px;">Crear Nueva Persona</a>
            
            <form action="eliminar.php" method="post" onsubmit="return confirm('¿Está seguro de que desea eliminar las personas seleccionadas?');">
            <table>
                <thead>
                    <tr>
                        <th><input type="checkbox" id="seleccionar_todo"></th>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Foto</th> 
                        <th>Acciones</th> </tr>
                </thead>
                <tbody>
                    <?php
                        // 3. MOSTRAR DATOS (ITERAR SOBRE LOS RESULTADOS)
                        while($fila = mysqli_fetch_assoc($resultado)){
                            echo '<tr>';
                            
                            // Columna de Checkbox Individual para Eliminar
                            echo '<td><input type="checkbox" name="ids_a_eliminar[]" value="' . htmlspecialchars($fila['id']) . '"></td>';
                            
                            echo '<td>' . htmlspecialchars($fila['id']) . '</td>';
                            echo '<td>' . htmlspecialchars($fila['nombre']) . '</td>';
                            echo '<td>' . htmlspecialchars($fila['apellido']) . '</td>';
                            
                            // Columna de Foto (Ruta Absoluta)
                            echo '<td><img src="/PHP2025/' . htmlspecialchars($fila['foto']) . '" alt="Foto de Persona" class="foto-mini"></td>';
                            
                            // Columna de Acciones: Enlace a Modificar
                            echo '<td><a href="modificar.php?id=' . htmlspecialchars($fila['id']) . '">Modificar</a></td>';
                            
                            echo '</tr>';
                        }
                    ?>
                </tbody>
            </table>

            <button type="submit" name="Eliminar" class="btn btn-danger" style="margin-top: 15px; padding: 10px 20px;">Eliminar Seleccionados</button>
            </form>
            <?php
            } else {
                echo "<p>No hay registros en la tabla 'personas' para mostrar.</p>";
            }

            // Liberar resultado y cerrar conexión (Buena práctica)
            mysqli_free_result($resultado);
            mysqli_close($conexion);
        ?>
    </section>
    
    <footer>
        <hr>
        <p>&copy; <?php echo date('Y'); ?> Mi Proyecto PHP</p>
    </footer>
    
    <script>
        // 1. Obtener el elemento del checkbox principal (el que está en el encabezado de la tabla)
        // Usamos document.getElementById() porque le dimos el id="seleccionar_todo" a ese checkbox.
        document.getElementById('seleccionar_todo').onclick = function(e){
            
            // 2. Definir qué sucede cuando se hace clic en el checkbox principal
            
            // Obtener todos los checkboxes individuales de las filas
            // Usamos document.getElementsByName() porque todos los checkboxes de las filas
            // tienen el mismo atributo name="ids_a_eliminar[]".
            var checkboxes = document.getElementsByName('ids_a_eliminar[]');
            
            // Iterar (recorrer) sobre todos los checkboxes individuales
            for(var i=0, n=checkboxes.length; i<n; i++){
                
                // Sincronizar el estado:
                // Asigna el estado del checkbox principal (e.target.checked) a cada checkbox individual.
                // Si el principal está chequeado (true), los demás se chequean.
                // Si el principal NO está chequeado (false), los demás se deschequean.
                checkboxes[i].checked = e.target.checked;
            }
        }
    </script>
</body>
</html>