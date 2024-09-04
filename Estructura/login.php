<?php

    include('includes/utilerias.php');

    session_start();

    if (isset($_SESSION['usuario'])){
        redireccionar('La sesión ya está iniciada','index.php');
        die();
    }
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link rel="stylesheet" href="../Diseno/estiloLogin.css">
    <script src="../scripts/formulario-ingresar.js" defer></script>
</head>
<body>

    <header><h1>Iniciar sesión</h1></header>

    <main>
        <form action="entrar-manejo.php" method="post">
            <fieldset>
                <h1>Bienvenid@</h1>
                <img src="../Imagenes/barbero.png">
                <label for="usuario">Nombre</label> 
                <br>
                <input type="text" name="usuario" id="usuario" required>
                <br>
                <img src="../Imagenes/candado.png">
                <label for="password">Contraseña</label> 
                <br>
                <input type="password" name="password" id="password" required>
                <br>
                <br>
                <input type="submit" value="Iniciar Sesión" class="registrar">
                <br>
                <br>
                <a href="registro.php">¿No tienes cuenta? Regístrate</a>
                <br>
                <a href="recuperarContra.php">Olvidé mi constraseña</a>
            </fieldset>
            
            
        </form>
    </main>
    
</body>
</html>