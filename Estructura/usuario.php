<?php
include('includes/utilerias.php');
session_start();
// Verifica que el idUsuario esté en la sesión

if (!isset($_SESSION['administrador']) && !isset($_SESSION['usuario'])) {
    die("Error: No hay ningún usuario autenticado.");
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pa' La Barber Shop - Usuarios</title>
    <link rel="stylesheet" href="../Diseno/estiloCitas.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Ga+Maamli&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">

            <a class="navbar-brand me-auto" href="#"><img src="../Imagenes/logo.png" alt="Logo de Barber Shop"></a></a>
            <!-- Enlace de login, aparece antes de la hamburguesa en pantallas pequeñas -->
            <?php
            echo '<a href="salir.php"" class="login-button order-lg-2">Salir</a>';

            ?>

            <!-- Botón de hamburguesa -->
            <button class="navbar-toggler order-lg-3" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar"
                aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <a class="navbar-brand" href="#">
                        <img src="../Imagenes/logo.png" alt="Logo de Barber Shop" style="height: 3rem;">
                    </a>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-center flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link mx-lg-2" aria-current="page" href="cortes.php">Cortes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-lg-2 active" href="usuario.php">Usuarios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-lg-2" href="citas.php">Citas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-lg-2" href="agendar.php">Agendar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-lg-2" href="descansos.php">Descansos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-lg-2" href="horarios.php">Horarios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-lg-2" href="barberos.php">Barberos</a>
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
                                    <div class="table-responsive table-scroll" data-mdb-perfect-scrollbar="true"
                                        style="position: relative; height: 700px">
                                        <table class="table table-striped mb-0">
                                            <thead style="background-color: #002d72;">
                                                <tr>
                                                    <th colspan="3">Filtrar</th>
                                                    <th colspan="2">
                                                        <form class="form-inline d-flex" method="GET"
                                                            action="usuario.php">
                                                            <input class="form-control me-2" type="search" name="buscar"
                                                                placeholder="Buscar" aria-label="Buscar"
                                                                style="width: 50%;">
                                                            <button class="btn btn-info my-2 my-sm-0"
                                                                type="submit">Buscar</button>
                                                        </form>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th scope="col" colspan="2">Nombre</th>
                                                    <th scope="col" colspan="2">Email</th>
                                                    <th scope="col" colspan="1">Eliminar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Conectar a la base de datos
                                                $conexion = conectar();

                                                // Capturar el valor de búsqueda
                                                $buscar = isset($_GET['buscar']) ? $_GET['buscar'] : '';

                                                // Validar el término de búsqueda
                                                if (!empty($buscar) && !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+$/', $buscar)) {
                                                    // Redirigir con un mensaje de error específico
                                                    redireccionar("El término de búsqueda no puede contener números. Por favor, ingresa solo letras.", "usuario.php");
                                                    exit();
                                                }

                                                // Llamar a la función que muestra los usuarios, pasándole el valor de búsqueda
                                                ver_usuarios($conexion, $buscar);

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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </main>

    <footer>
        <p>&copy; Todos los derechos reservados</p>
    </footer>

    <script>
        function confirmarEliminacion(event) {
            if (!confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
                event.preventDefault();
            }
        }
    </script>
</body>

</html>

<?php
function ver_usuarios($conexion, $buscar = '')
{


    // Si hay un valor de búsqueda, agregar una cláusula WHERE a la consulta

    if (!empty($buscar)) {
        $sql = "SELECT idUsuario, nombre, email FROM usuarios WHERE es_admin = 0 AND nombre LIKE '%$buscar%'";
    } else {
        // Consulta predeterminada sin búsqueda
        $sql = "SELECT idUsuario, nombre, email FROM usuarios WHERE es_admin = 0";
    }

    $resultado = mysqli_query($conexion, $sql);

    if (!$resultado) {
        echo "<tr><td colspan='3'>Error en la consulta: " . mysqli_error($conexion) . "</td></tr>";
        return;
    }

    if (mysqli_num_rows($resultado) > 0) {
        while ($renglon = mysqli_fetch_assoc($resultado)) {
            $idUsuario = $renglon['idUsuario'];
            $nombre = $renglon['nombre'];
            $email = $renglon['email'];

            echo
                "<tr>
                <td colspan=\"2\">$nombre</td>
                <td colspan=\"2\">$email</td>
                <td colspan=\"1\">
                    <form method='POST' action='eliminar_usuario.php' style='display:inline;' onsubmit='confirmarEliminacion(event)'>
                        <input type='hidden' name='idUsuario' value='$idUsuario'>
                        <button type='submit' class='btn btn-danger btn-sm px-3'>x</button>
                    </form>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='3'>No se encontraron usuarios registrados.</td></tr>";
    }
}
?>