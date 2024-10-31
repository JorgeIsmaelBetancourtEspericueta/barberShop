<?php
include('includes/utilerias.php');
session_start();

// Verifica si el usuario ya está autenticado o si tiene una sesión iniciada
if (isset($_SESSION['usuario'])) {
    redireccionar('La sesión ya está iniciada', 'index.php');
    die();
}

if (isset($_SESSION['administrador'])) {
    redireccionar('La sesión ya está iniciada', 'inicioAdmon.php');
    die();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Diseno/Prueba.css">
    <title>Nueva Contraseña</title>
</head>

<body>
    <div class="container" id="container">
        <!-- Sección de Nueva Contraseña -->
        <div class="form-container sign-in">
            <form action="cambiar_contraseña.php" method="post">
                <h1>Nueva Contraseña</h1>
                <p>Introduce tu nueva contraseña y confírmala para restablecer el acceso a tu cuenta.</p>
                <input type="password" name="nueva_contra" required placeholder="Nueva Contraseña">
                <input type="password" name="confirmar_contra" required placeholder="Confirmar Contraseña">
                <button type="submit">Restablecer Contraseña</button>
            </form>
        </div>

        <!-- Sección de regreso al Login -->
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-right">
                    <h1>¡Contraseña Actualizada!</h1>
                    <p>Ya puedes iniciar sesión con tu nueva contraseña.</p>
                    <button onclick="location.href='login.php'" class="hidden">Iniciar Sesión</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
