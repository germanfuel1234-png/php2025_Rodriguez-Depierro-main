<?php
session_name('back');
session_start();

         unset($_SESSION['IDUsuario']);
   		unset($_SESSION['Usuario']);
   		unset($_SESSION['Nombre']);
   		unset($_SESSION['is_logged']);
		
           session_destroy();

           header('location: login.php?mensaje=Se ha desconectado del sistema');

?>