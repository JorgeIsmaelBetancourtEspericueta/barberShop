<?php
include('includes/utilerias.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo_ingresado = validar($_POST['code']);
    
    // Compara el código ingresado con el código de sesión
    if ($codigo_ingresado == $_SESSION['codigo']) {
        // Redirige a la página de nueva contraseña si el código es correcto
        header("Location: nueva_contraseña.php");
        exit();
    } else {
        echo "<script>alert('Código incorrecto, intenta nuevamente.'); window.location.href = 'recuperarcontra.php';</script>";
    }
} else {
    header("Location: recuperarcontra.php");
    exit();
}
?>
