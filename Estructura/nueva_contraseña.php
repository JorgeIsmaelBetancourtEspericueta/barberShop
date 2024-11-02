<?php
session_start();
include('includes/utilerias.php');

// Verifica si la variable de sesión 'email_recuperacion' está configurada
if (!isset($_SESSION['email_recuperacion'])) {
    redireccionar('Sesión no válida. Por favor, inténtalo de nuevo.', 'login.php');
    exit();
}

// Procesar el formulario cuando se envía
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_SESSION['email_recuperacion'];
    $nuevaContrasena = $_POST['nueva_contra'];
    $confirmarContrasena = $_POST['confirmar_contra'];

    // Verifica si las contraseñas coinciden
    if ($nuevaContrasena !== $confirmarContrasena) {
        echo "<script>alert('Las contraseñas no coinciden. Por favor, inténtalo de nuevo.');</script>";
    } else {
        // Hashear la nueva contraseña
        $nuevaContrasenaHashed = password_hash($nuevaContrasena, PASSWORD_DEFAULT);

        $conexion = conectar();

        if (!$conexion) {
            echo "<script>alert('Error en la conexión a la base de datos.');</script>";
        } else {
            // Actualizar la contraseña en la base de datos
            $stmt = $conexion->prepare("UPDATE usuarios SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $nuevaContrasenaHashed, $email);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                // Contraseña cambiada con éxito
                echo "<script>alert('Contraseña cambiada con éxito'); window.location.href = 'login.php';</script>";
            } else {
                echo "<script>alert('Error al cambiar la contraseña. Por favor, inténtalo de nuevo.');</script>";
            }

            $stmt->close();
            $conexion->close();
        }
    }
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
            <form action="" method="post">
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
