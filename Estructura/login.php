<?php

    include('includes/utilerias.php');

    session_start();

 

    if (isset($_SESSION['usuario'])){
        redireccionar('La sesión ya está iniciada','index.php');
        die();
    }

    if (isset($_SESSION['administrador'])){
        redireccionar('La sesión ya está iniciada','inicioAdmon.php');
        die();
    }
    

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../Diseno/estiloLogin.css">
    <script src="../Scripts/login.js" defer></script>

    <title>Modern Login Page | AsmrProg</title>
</head>

<body>

    <div class="container" id="container">
        <div class="form-container sign-up">
            <form action="send.php" method="post" enctype="multipart/form-data">
                <h1>Crear cuenta</h1>
                <input type="text" placeholder="email" name="email" required> 
                <button>Enviar código</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form action="entrar-manejo.php" method="post">
                <h1>INICIO SESIÓN</h1>
                <!--<span>or use your email password</span>-->
                <input type="email" name="usuario" id="usuario" required placeholder="Email">
                <input type="password" name="password" id="password" required placeholder="Contraseña">
                <a href="#">¿Olvidaste tu contraseña?</a>
                <button>Ingresar</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Hola, bienvenido!</h1>
                    <p>Ingrese su correo electrónico para comenzar su registro</p>
                    <button class="hidden" id="login">Ingresar</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>¡Hola, bienvenid@!</h1>
                    <p>Nos complace recibirte en nuestro portal oficial, donde podrás agendar tu cita fácilmente, conocer nuestros servicios y descubrir lo que nos hace únicos. </p>
                    <button class="hidden" id="register">Registrarse</button>
                </div>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>

</html>