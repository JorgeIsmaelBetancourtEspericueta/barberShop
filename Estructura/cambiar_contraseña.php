<?php
include('includes/utilerias.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nueva_contra = validar($_POST['nueva_contra']);
    $confirmar_contra = validar($_POST['confirmar_contra']);

    // Verifica que ambas contraseñas coincidan
    if ($nueva_contra === $confirmar_contra) {
        $conexion = conectar();
        $hash_contra = password_hash($nueva_contra, PASSWORD_BCRYPT);
        $email = $_SESSION['email']; // Obtén el correo electrónico de la sesión

        // Actualiza la contraseña en la base de datos
        $sql = "UPDATE usuarios SET password='$hash_contra' WHERE email='$email'";
        if (mysqli_query($conexion, $sql)) {
            unset($_SESSION['email']); // Limpia la sesión después de actualizar
            redireccionar('Contraseña cambiada con éxito', 'login.php');
        } else {
            echo "Error al actualizar la contraseña. Inténtalo nuevamente.";
        }
    } else {
        echo "Las contraseñas no coinciden. Vuelve a intentarlo.";
    }
} else {
    redireccionar('Acceso denegado', 'index.php');
}
?>
