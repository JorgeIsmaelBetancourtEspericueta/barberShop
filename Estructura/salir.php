<?php

include('includes/utilerias.php');
ob_start();
session_start();

if (isset($_SESSION['usuario'])) {
    session_unset(); // Elimina todas las variables de sesión
    session_destroy(); // Destruye la sesión
    redireccionar('Sesión cerrada', 'index.php');
}elseif(isset($_SESSION['administrador'])) {
    session_unset(); // Elimina todas las variables de sesión
    session_destroy(); // Destruye la sesión
    redireccionar('Sesión cerrada', 'index.php'); 
}else {
    redireccionar('No has iniciado sesión', 'index.php');
}

?>
