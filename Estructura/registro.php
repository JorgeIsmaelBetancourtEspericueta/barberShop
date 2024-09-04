<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresar</title>
    <link rel="stylesheet" href="../Diseno/estiloRegistro.css">
</head>
<body>
    <header>REGISTRO</header>
    

        <div class="formulario">
            <form action="registro-manejo.php" method="post" enctype="multipart/form-data">

                <div class="formularios">
                    <img src="../Imagenes/nombre.png" alt="">
                    <input type="text" placeholder="Nombre" name="nombre" required> 
                </div>
                    <br>
                <div class="formularios">
                    <img src="../Imagenes/telefono.png" alt="">
                    <input type="tel" placeholder="Teléfono" name="telefono" required>
                </div>
                    <br>
                <div class="formularios">
                    <img src="../Imagenes/contra.png" alt="">
                    <input type="password" placeholder="Contraseña" name="password" required>
                </div>
                    <br>
                <div class="formularios">
                    <img src="../Imagenes/contra.png" alt="">
                    <input type="password" placeholder="Confirmar contraseña" name="confirmar" required style="font-size: 1.5rem;">
                </div>

                <footer>
                    <input type="submit" value="Registrarse"  id="registro">
 
                </footer>
            </form>
            
        </div>
   
</body>
</html>