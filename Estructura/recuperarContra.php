<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña</title>
    <link rel="stylesheet" href="../Diseno/estiloRecuperar.css">
    

</head>
<body>

    <header><h1>Recuperar contraseña</h1></header>

    <main>
        <form action="recuperar-manejo.php" method="post" enctype="multipart/form-data">
            <fieldset>
                <h1>Cambio de contraseña</h1>
                <img src="../Imagenes/whatsapp.png">
                <label>Número de celular</label> 
                <br>
                <input type="text" name="celular">
                <br>
                <img src="../Imagenes/candado.png">
                <label>Nueva contraseña</label> 
                <br>
                <input type="password" name="password">
                <br>
                <img src="../Imagenes/candado.png">
                <label>Nueva contraseña</label> 
                <br>
                <input type="password" name="confirmar">
                <br>
                <br>
                <input type="submit" value="Cambiar" class="registrar">
                <br>
                <br>
            </fieldset>
            
            
        </form>
    </main>
    
</body>
</html>