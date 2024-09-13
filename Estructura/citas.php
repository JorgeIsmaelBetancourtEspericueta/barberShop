<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pa' La Barber Shop</title>
    <link rel="stylesheet" href="../Diseno/estiloCitas.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Ga+Maamli&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="../scripts/cita.js" defer></script>

</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            
                <a class="navbar-brand me-auto" href="#">Pa' La Barber Shop</a>
                
                <!-- Enlace de login, aparece antes de la hamburguesa en pantallas pequeñas -->
                <?php
                    echo '<a href="salir.php"" class="login-button order-lg-2">Salir</a>';
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
                                <a class="nav-link mx-lg-2" aria-current="page" href="cortes.php">Cortes</a>
                            </li>            
                            <li class="nav-item">
                                <a class="nav-link mx-lg-2" href="usuario.php">Usuarios</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mx-lg-2 active" href="citas.php">Citas</a>
                            </li>
                            <li class="nav-item" style="width: 120px;">
                                <a class="nav-link mx-lg-2" href="agendar.php">Agendar</a>
                            </li>
                        </ul>
                    </div>
                </div>
        </div>
    </nav>

    <main>
        <div class="alto"></div>
        <div class="bg-image h-100" style="background-color: #c1ed63;">
            <div class="mask d-flex align-items-center h-100">
            <div class="container">
                <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive table-scroll" data-mdb-perfect-scrollbar="true" style="position: relative; height: 700px">
                        <table class="table table-striped mb-0">
                            <thead style="background-color: #002d72;">
                            <tr>
                                <th colspan="2" style="text-align: center; vertical-align: middle;">
                                    <label for="filtroFecha" style="color: white;">Filtrar la información</label>
                                </th>
                                <th colspan="4" class="text-center">
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        <select name="campo" id="campo" class="form-select form-select-sm bg-light text-success w-auto" style="min-width: 200px;">
                                            <option value="">Seleccione un campo</option>
                                            <option value="Fecha">Fecha</option>
                                            <option value="Hora">Hora</option>
                                            <option value="Servicio">Servicio</option>
                                            <option value="Cleinte">Cliente</option>
                                            <option value="Barbero">Barbero</option>
                                        </select>
                                        <select name="fecha" id="filtroFecha" class="form-select form-select-sm bg-light text-success w-auto" style="min-width: 200px;">
                                            <option value="">Seleccione una opción</option>
                                            <option value="<?php echo date('Y-m-d'); ?>">Citas de hoy</option>
                                            <option value="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">Citas de mañana</option>
                                            <option value="all">Todas</option>
                                            <option value="custom">Seleccionar</option>
                                        </select>
                                        <!-- Input de fecha personalizado oculto por defecto -->
                                        <input type="date" id="fechaPersonalizada" class="form-control w-auto mt-2" style="display:none;">

                                        <script>
                                            const selectFecha = document.getElementById('filtroFecha');
                                            const inputFechaPersonalizada = document.getElementById('fechaPersonalizada');

                                            selectFecha.addEventListener('change', function() {
                                                if (this.value === 'custom') {
                                                    inputFechaPersonalizada.style.display = 'block'; // Mostrar input de fecha
                                                } else {
                                                    inputFechaPersonalizada.style.display = 'none'; // Ocultar input de fecha
                                                    inputFechaPersonalizada.value = ''; // Limpiar el valor si elige otra opción
                                                }
                                            });
                                        </script>
                                        <button onclick="filtrar()" class="btn btn-info btn-sm">Buscar</button>
                                    </div>
                                </th>
                            </tr>

                            <tr class="encabezados">
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Servicio</th>
                                <th>Cliente</th>
                                <th>Barbero</th>
                                <th>Cancelar</th>
                             </tr>
                            </thead>
                            <tbody>
                                <?php
                                    // Incluye el archivo que define la función conectar()
                                    include('includes/utilerias.php');

                                    // Definir la fecha por defecto
                                    $fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');

                                    // Conectar a la base de datos
                                    $conexion = conectar();

                                    // Llamar a la función ver_citas
                                    ver_citas($fecha, $conexion);

                                    // Cerrar la conexión
                                    mysqli_close($conexion);
                                ?>
                            </tbody>
                        </table>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; Todos los derechos reservados</p>
    </footer>

</body>
</html>

