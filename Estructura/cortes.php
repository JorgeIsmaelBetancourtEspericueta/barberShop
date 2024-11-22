<?php
    include('includes/utilerias.php');
    session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Cortes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../Diseno/estiloCatalogo.css">
</head>

<body>
    <!--Navbar-->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand me-auto" href="#"><img src="../Imagenes/logo.png" alt="Logo de Barber Shop"></a>
            
            <!-- Enlace de login -->
            <?php
            if (isset($_SESSION['usuario']) || isset($_SESSION['administrador'])) {
                echo '<a href="salir.php" class="login-button order-lg-2">Salir</a>';
            } else {
                echo '<a href="login.php" class="login-button order-lg-2">Ingresar</a>';
            }
            ?>

            <!-- Menú hamburguesa -->
            <button class="navbar-toggler order-lg-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <a class="navbar-brand" href="#"><img src="../Imagenes/logo.png" alt="Logo de Barber Shop" style="height: 3rem;"></a>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-center flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link mx-lg-2 active" aria-current="page" href="cortes.php">Cortes</a>
                        </li>
                        <?php if (isset($_SESSION['administrador'])) { ?>
                            <li class="nav-item"><a class="nav-link mx-lg-2" href="usuario.php">Usuarios</a></li>
                            <li class="nav-item"><a class="nav-link mx-lg-2" href="citas.php">Citas</a></li>
                            <li class="nav-item"><a class="nav-link mx-lg-2" href="agendar.php">Agendar</a></li>
                            <li class="nav-item"><a class="nav-link mx-lg-2" href="descansos.php">Descansos</a></li>
                            <li class="nav-item"><a class="nav-link mx-lg-2" href="horarios.php">Horarios</a></li>
                            <li class="nav-item"><a class="nav-link mx-lg-2" href="barberos.php">Barberos</a></li>
                        <?php } else { ?>
                            <li class="nav-item"><a class="nav-link mx-lg-2" href="agendar.php">Agendar</a></li>
                            <li class="nav-item"><a class="nav-link mx-lg-2" href="https://maps.app.goo.gl/SMNS6bEzgwQCrNUB8" target="_blank">Ubicación</a></li>
                            <li class="nav-item"><a class="nav-link mx-lg-2" href="tel:+3112695860">Whatsapp</a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Catálogo de Cortes -->
    <div class="container py-5">
        <h1 class="text-center">Catálogo de Cortes</h1>
        <div class="row row-cols-1 row-cols-md-3 g-4 py-5">

            <div class="col">
                <div class="card">
                    <img src="../Imagenes/Mullet fade.jpeg" class="card-img-top" alt="Mullet fade">
                    <div class="card-body">
                        <h5 class="card-title">Mullet fade</h5>
                        <p class="card-text">Este corte combina un estilo clásico con los lados desvanecidos y un toque moderno en la parte trasera.</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <img src="../Imagenes/Mohicano.jpg" class="card-img-top" alt="Mohicano">
                    <div class="card-body">
                        <h5 class="card-title">Mohicano</h5>
                        <p class="card-text">Un corte que nunca pasa de moda, el Mohicano destaca por su agresiva cresta central, perfecta para un estilo rebelde y con actitud.</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <img src="../Imagenes/clasico.jpeg" class="card-img-top" alt="Corte clásico">
                    <div class="card-body">
                        <h5 class="card-title">Corte clásico</h5>
                        <p class="card-text">El corte tradicional por excelencia, limpio y formal, ideal para cualquier ocasión. Un estilo que resiste el paso del tiempo.</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <img src="../Imagenes/Taper fade.jpeg" class="card-img-top" alt="Taper fade">
                    <div class="card-body">
                        <h5 class="card-title">Taper fade</h5>
                        <p class="card-text">Con un desvanecido gradual desde las sienes hasta la nuca, el Taper fade ofrece un look pulido y moderno sin perder elegancia.</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <img src="../Imagenes/Burst fade.jpeg" class="card-img-top" alt="Burst fade">
                    <div class="card-body">
                        <h5 class="card-title">Burst fade</h5>
                        <p class="card-text">Este corte destaca por su desvanecido que rodea las orejas, creando un estilo fresco y juvenil, ideal para destacar en cualquier lugar.</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <img src="../Imagenes/Hide fade.jpeg" class="card-img-top" alt="Hide fade">
                    <div class="card-body">
                        <h5 class="card-title">Hide fade</h5>
                        <p class="card-text">Un desvanecido discreto que mantiene el enfoque en la parte superior del cabello, perfecto para quienes buscan un estilo reservado pero con clase.</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <img src="../Imagenes/Mid fade.jpeg" class="card-img-top" alt="Mid fade">
                    <div class="card-body">
                        <h5 class="card-title">Mid fade</h5>
                        <p class="card-text">Con un desvanecido a media altura, este corte logra un equilibrio perfecto entre lo clásico y lo moderno, brindando un estilo versátil.</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <img src="../Imagenes/Low fade.jpeg" class="card-img-top" alt="Low fade">
                    <div class="card-body">
                        <h5 class="card-title">Low fade</h5>
                        <p class="card-text">El Low fade presenta un desvanecido sutil en la parte inferior del cabello, ideal para un look limpio y natural con un toque moderno.</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card">
                    <img src="../Imagenes/personalizado.png" class="card-img-top" alt="Personalizado">
                    <div class="card-body">
                        <h5 class="card-title">Personalizado</h5>
                        <p class="card-text">Diseña tu propio estilo y deja que nuestros barberos expertos lo hagan realidad. La opción perfecta para quienes buscan originalidad.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
