<?php
    include('includes/utilerias.php');

    if (session_status() == PHP_SESSION_NONE){
        session_start();
    }

    if(isset($_SESSION['administrador'])){
        redireccionar2('inicioAdmon.php');
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pa' La Barber Shop</title>
    <link rel="stylesheet" href="../Diseno/estiloIndex.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Ga+Maamli&display=swap" rel="stylesheet">
    <style>
        #map {
            height: 400px;
            width: 100%;
        }
    </style>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBF2gwzt2kc7o0xwUP6WXSX_3zQtJPG34g&callback=initMap"></script>
    <script src="../scripts/mapa.js"></script>
</head>
<body>
    <header>
        <h1>"Elegancia en cada detalle."</h1>
    </header>

    <nav>
        <div>
            <h1>Calle Olivos 67A</h1>
            <h1>Colonia Comerciantes</h1>
            <br>
        </div>
        <div>
            <div id="map"></div>
            <br>
            <a href="https://maps.app.goo.gl/6LogxduiVN2n4mUr7" target="_blank">Ver en <br>Google Maps</a>
        </div>
    </nav>

    <main>
        <?php
            if (isset($_SESSION['usuario'])) {
                echo '<a href="salir.php"><button>Salir</button></a>';
                echo '<a href="agendar.php"><button>Agendar</button></a>';
            } else {
                echo '<a href="login.php"><button>Ingresar</button></a>';
                echo '<a href="registro.php"><button>Registrarse</button></a>';
            }
        ?>
        <a href="cortes.php"><button>Ver cortes</button></a>
    </main>

    <aside>
        <img src="../Imagenes/logo.png" class="imagen">
        <div class="contacto">
            <img class="imgContacto" src="../Imagenes/whatsapp.png">
            <h1>Contacto</h1>
            <a href="tel:+3112695860">3112695860</a>
        </div>
        <div class="redes">
            <img class="imgRedes" src="../Imagenes/instagram.png">
            <h2>Síguenos</h2>
            <a href="https://www.instagram.com/palabarbershop?igsh=aWN5YnludjB5bjU0" target="_blank">Pa'la<br>Barber Shop</a>
        </div>
    </aside>

    <footer>
        <p>&copy; Todos los derechos reservados</p>
    </footer>
</body>
</html>
