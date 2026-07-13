<?php
// ==========================================================
// ARCHIVO: login.php (VERSIÓN CON AUTENTICACIÓN POR BASE DE DATOS)
// ==========================================================

if (isset($_POST['Login'])) {
//ini_set('display_errors',1);
 include 'conexion.php'; // 1. Incluimos la conexión

// 2. Recoger y Hashear las credenciales
// mysqli_real_escape_string se usa para sanitizar el nombre de usuario
$u=mysqli_real_escape_string($conexion, $_POST['usuario']);
// La clave se hashea usando MD5 para compararla con la BBDD
$p=md5($_POST['clave']);

// 3. Consulta SQL para buscar el usuario en la tabla seg_usuarios
$sql = "select id, usuario, nombre from seg_usuarios where usuario ='".$u."' and clave='".$p."' and activo='1'";
$result = mysqli_query($conexion, $sql);
$rstlogin = mysqli_fetch_array($result);	

if ($rstlogin) { // Si la consulta devolvió un registro (Usuario encontrado y clave correcta)

   session_name('back');
   session_start();
   
   // 4. Asignar los valores de la BBDD a la sesión
   $_SESSION['Usuario']   = $rstlogin['usuario'];	
   $_SESSION['IDUsuario'] = $rstlogin['id'];
   $_SESSION['Nombre'] = $rstlogin['nombre'];	
   $_SESSION['is_logged'] = 1; // Bandera de sesión iniciada
                
   header ('location: index.php'); // Redirección exitosa
   exit();
 }else{
    // Usuario o contraseña incorrectos
    header('location: login.php?mensajee=Usuario o Password Incorrectos');
 }  
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link href="css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/estilos.css" />
</head>

<body>
    <header>
        <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.html">
                   
                </a>
                
            </div>
        </nav>
    </header>
    <main>
        
       
       
        <section id="serOrador" class="container" >
            <div class="row justify-content-center">
                <div class="col-lg-7 col-xl-8">
                    <p class="text-center"></p>
                    <h2 class="text-center">LOGIN</h2>
                    <p class="text-center"></p>
                    <form action="login.php" method="post" enctype="multipart/form-data" name="contact-form" >
                        <div class="row gx-2">
                            <div class="form-floating col-md mb-3">
                                <input name="usuario" id="nombreOrador" type="text" class="form-control" placeholder="Usuario" aria-label="Usuario" required>
                                <label for="nombreOrador">Usuario</label>
                            </div>
                           
                        <div class="row gx-2">
                            <div class="form-floating col-md mb-3">
                                <input name="clave" id="correoOrador" type="password" class="form-control" placeholder="Contraseña" aria-label="Contraseña" required>
                                <label for="correoOrador">Contraseña</label>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col mb-3">
                               
                                 <div class="d-grid">
                                    <button type="submit" name="Login" class="btn btn-success btn-lg btn-form">Ingresar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                      <a href="index.php">Volver</a>
                </div>
            </div>
        </section>
    </main>
    <footer>
       
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOomMi466C8"
        crossorigin="anonymous"></script>
    
</body>

</html>