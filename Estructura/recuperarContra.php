<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../Diseno/Prueba.css">
    <script src="../Scripts/login.js" defer></script>

    <title>Recuperar Contraseña</title>
</head>

<body>

    <div class="container" id="container">
        <!-- Sección de recuperación de contraseña -->
        <div class="form-container sign-in">
            <form action="recuperar-manejo.php" method="post">
                <h1>¿Olvidaste tu contraseña?</h1>
                <p>Escribe el correo electrónico asociado a tu cuenta y te enviaremos las instrucciones para recuperarla.</p>
                <input type="email" name="email" id="email" required placeholder="Email">
                <button type="submit">Recuperar Contraseña</button>
            </form>
        </div>
        
        <!-- Mensaje de bienvenida -->
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-right">
                    <h1>¡Hola, bienvenid@!</h1>
                    <p>Nos complace recibirte en nuestro portal oficial, donde podrás recuperar tu cuenta de manera fácil y rápida.</p>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
