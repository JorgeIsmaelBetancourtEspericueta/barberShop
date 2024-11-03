<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../Diseno/Prueba.css">
    <script src="../Scripts/recuperar.js" defer></script>
    <title>Recuperar Contraseña</title>
</head>

<body onload="<?php if (isset($_SESSION['mostrar_verificar_codigo'])) echo 'switchToVerify()'; ?>">
    <div class="container" id="container">
        <!-- Sección de recuperación de contraseña -->
        <div class="form-container sign-in">
            <form action="send1.php" method="post">
                <h1>¿Olvidaste tu contraseña?</h1>
                <p>Escribe el correo electrónico asociado a tu cuenta y te enviaremos un código de verificación.</p>
                <input type="email" name="email" id="email" required placeholder="Email">
                <button type="submit">Enviar Código</button>
            </form>
        </div>

        <!-- Sección de verificación de código -->
        <div class="form-container sign-up">
            <form action="verificar_codigo1.php" method="post">
                <h1>Ingrese el Código</h1>
                <p>Introduce el código que enviamos a tu correo para continuar.</p>
                <input type="text" name="code" id="code" required placeholder="Código de verificación">
                <button type="submit">Verificar Código</button>
            </form>
        </div>

        <!-- Mensaje de bienvenida -->
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>¡Recupera tu cuenta!</h1>
                    <p>Ingresa tu correo para recibir el código de recuperación.</p>
                    <button class="hidden" onclick="switchToRecover()">Recuperar</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>¡Introduce el código!</h1>
                    <p>Cuando recibas el código, introdúcelo aquí para restablecer tu contraseña.</p>
                    <button class="hidden" onclick="switchToVerify()">Ingresar Código</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
// Elimina la variable de sesión para que no se muestre la próxima vez
unset($_SESSION['mostrar_verificar_codigo']);
?>
