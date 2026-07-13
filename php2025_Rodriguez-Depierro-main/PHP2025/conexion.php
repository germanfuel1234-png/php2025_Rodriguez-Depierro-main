
<?php
// ==========================================================
// ARCHIVO: conexion.php
// OBJETIVO: Establecer la conexión y selección de la base de datos
// ==========================================================

// 1.PARAMETROS DE CONEXIÓN

// Dirección del servidor de la base de datos.
$servidor = "localhost";
// Nombre de usuario 
$usuario = "german";
// Contraseña.
$contrasena = "german1234";
// Nombre exacto de la base de datos que utilizaremos en el proyecto.
$basededatos = "curso_php2025"; 
// 2.**ESTABLECER LA CONEXIÓN CON EL SERVIDOR**

// mysqli_connect() intenta establecer la conexión.
// La función 'or die()' detiene la ejecución del script y muestra un mensaje 
$conexion = mysqli_connect($servidor, $usuario, $contrasena) 
    or die ("No se pudo conectar al servidor. Verifique credenciales.");

// 3. **SELECCIONAR LA BASE DE DATOS**

// mysqli_select_db() intenta seleccionar la base de datos específica dentro del servidor.
$db = mysqli_select_db($conexion, $basededatos) 
    or die ("No se pudo conectar a la base de datos '$basededatos'. Verifique el nombre.");

?>