<?php
    include('includes/utilerias.php');

    if (session_status() == PHP_SESSION_NONE){
        session_start();
    }


    if(isset($_SESSION['usuario'])){
        redireccionar2('index.php');
    }

    
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="../Diseno/estiloPrincipal.css">

</head>

<body>
    <!--Navbar-->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand me-auto" href="#">Pa' La Barber Shop</a>
            
            <!-- Enlace de login, aparece antes de la hamburguesa en pantallas pequeñas -->
            <?php
            if (isset($_SESSION['administrador'])) {
                echo '<a href="salir.php"" class="login-button order-lg-2">Salir</a>';
            } else {
                echo '<a href="login.php" class="login-button order-lg-2">Ingresar</a>';
            }
        ?>

            <!-- Botón de hamburguesa -->
            <button class="navbar-toggler order-lg-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar"
                aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Logo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-center flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link mx-lg-2 active" aria-current="page" href="cortes.php">Cortes</a>
                        </li>            
                        <li class="nav-item">
                            <a class="nav-link mx-lg-2" href="usuario.php">Usuarios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-lg-2" href="citas.php">Citas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-lg-2" href="agendar.php">Agendar</a>
                        </li>

                    </ul>
                </div>
            </div>

        </div>
    </nav>

    <!--Hero section-->
    <section class="hero-section">
        <div class="container d-flex align-items-center justify-content-center fs-1 text-white flex-column">
            <h1>Elegencia en cada detalle</h1>
            <h2>Pa'la Barber Shop</h2>

        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
