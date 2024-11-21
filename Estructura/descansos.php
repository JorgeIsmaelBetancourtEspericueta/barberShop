<?php
include('includes/utilerias.php');

session_start();
// Verifica que el idUsuario esté en la sesión

if (!isset($_SESSION['administrador']) && !isset($_SESSION['usuario'])) {
    die("Error: No hay ningún usuario autenticado.");
}

$conexion = conectar();

$sqlBarber = "SELECT idBarbero, nombre FROM barbero";
$listaBarberos = $conexion->query($sqlBarber);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idDescanso = $conexion->real_escape_string($_POST['idDescanso']);
    $idBarbero = $conexion->real_escape_string($_POST['barbero']); // Escapando cadenas para prevenir SQL Injection
    $fecha = $conexion->real_escape_string($_POST['fecha']);

    if (empty($fecha)) {
        redireccionar("Por favor, selecciona una fecha.", "descansos.php");
        exit();
    }

    if($idDescanso=="" || $idDescanso==null){
        $sql = "SELECT * FROM descanso WHERE fecha = '$fecha' AND idBarbero = '$idBarbero'";
        $result = $conexion->query($sql);

        if (!$result) {
            die("Error en la consulta: " . $conexion->error);
        }

        if ($result->num_rows > 0) {
            redireccionar("La fecha seleccionada ya está registrada. Por favor, selecciona otra fecha.", "descansos.php");
            exit;
        }

        $sql = "SELECT * FROM citas WHERE fecha = '$fecha' AND idBarbero = '$idBarbero'";
        $result = $conexion->query($sql);

        if (!$result) {
            die("Error en la consulta: " . $conexion->error);
        }

        if ($result->num_rows > 0) {
            redireccionar("No es posible agregar el descanso. Hay citas programadas para esa fecha.", "descansos.php");
            exit;
        }

        // Insertar datos en la tabla citas
        $sql = "INSERT INTO descanso (fecha, idBarbero) VALUES ('$fecha','$idBarbero')";

        if ($conexion->query($sql) === TRUE) {
            redireccionar("Fecha agendada correctamente.", "descansos.php");
            exit;
        } else {
            echo "Error: " . $sql . "<br>" . $conexion->error;
        }
    
        //$conexion->close();
    }else{ //Actualizar
        $sql = "SELECT * FROM descanso WHERE fecha = '$fecha' AND idBarbero = '$idBarbero' AND idDescanso != '$idDescanso'";
        $result = $conexion->query($sql);

        if (!$result) {
            die("Error en la consulta: " . $conexion->error);
        }

        if ($result->num_rows > 0) {
            redireccionar("La fecha seleccionada ya está registrada. Por favor, selecciona otra fecha.", "descansos.php");
            exit;
        }

        $sql = "SELECT * FROM citas WHERE fecha = '$fecha' AND idBarbero = '$idBarbero'";
        $result = $conexion->query($sql);

        if (!$result) {
            die("Error en la consulta: " . $conexion->error);
        }

        if ($result->num_rows > 0) {
            redireccionar("No es posible agregar el descanso. Hay citas programadas para esa fecha.", "descansos.php");
            exit;
        }

        $sql = "UPDATE descanso SET idBarbero = ?, fecha = ? WHERE idDescanso = ?";

        // Preparar la declaración
        if ($stmt = $conexion->prepare($sql)) {
            // Vincular los parámetros
            $stmt->bind_param("isi", $idBarbero, $fecha, $idDescanso);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                redireccionar("Descanso actualizado exitosamente.","descansos.php");
            } else {
                redireccionar("Error al actualizar el descanso: " . $stmt->error,"descansos.php");
            }

            // Cerrar la declaración
            $stmt->close();
        } else {
            echo "Error al preparar la consulta: " . $conexion->error;
        }

        // Cerrar la conexión
        //$conexion->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pa' La Barber Shop - Descansos</title>

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"  defer></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" defer></script>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">



    <link rel="stylesheet" href="../Diseno/estiloHorario.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Ga+Maamli&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
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
                <button class="navbar-toggler order-lg-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                    aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
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
                                <a class="nav-link mx-lg-2" href="usuario.php">Usuarios</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mx-lg-2" href="citas.php">Citas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mx-lg-2" href="agendar.php">Agendar</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mx-lg-2 active" href="descansos.php">Descansos</a>
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
        <div class="alto" style="height: 200px;"></div>
        <div class="bg-image h-100" style="background-color: #c1ed63;">
            <div class="mask d-flex align-items-center h-100">
            <div class="container">
                <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card">
                    <div class="card-body p-0">

                    <div class="d-flex justify-content-center align-items-center" style="background-color: white;">
            <!-- <div class="w-50"> -->

                <div id="agendar" class="container" >
                    <div class="row justify-content-center" >
                        <div class="col-12 col-md-8 col-lg-6 pb-5" >

                            
                            <form method="POST" action="descansos.php" class="form-row justify-content-center">
                                <div class="card">
                                    <div class="card-header p-0">
                                        <div class="custom-bg text-white text-center py-2">
                                            <div class="custom-bg ">
                                                <h2 ><i class="fa fa-envelope"></i> Agregar Descanso</h2>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Campo de nombre -->
                                    <div class="form-group col-md-10">
                                        <div class="input-group mb-2 inputBarbero">
                                            <div class="input-group-prepend inputBarbero">
                                                <div class="input-group-text"><i class="fa fa-list custom-icon-color"></i></div>
                                            </div>
                                            <select class="form-control inputBarbero" id="barbero" name="barbero" required>
                                                <option value="" disabled selected>Selecciona barbero</option>
                                                    <?php
                                                        // Verificar si la consulta tiene resultados
                                                        if ($listaBarberos->num_rows > 0) {
                                                            while($row = $listaBarberos->fetch_assoc()) {
                                                                echo '<option value="' . $row["idBarbero"] . '">' . $row["nombre"] . '</option>';
                                                            }
                                                        } else {
                                                            echo '<option value="" disabled>No hay barberos disponibles</option>';
                                                        }
                                                    ?>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- <div class="container"> -->
                                        <div class="form-group col-md-10">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fa fa-user custom-icon-color"></i></div>
                                                </div>
                                                <input type="text" class="form-control" id="fecha" name="fecha" placeholder="Selecciona la fecha" required>
                                            </div>
                                        </div>
                                    <!-- </div> -->
                                    <script>     
                                        flatpickr("#fecha", {
                                            locale: "es",  // Idioma a español
                                                minDate: "today",
                                                dateFormat: "Y-m-d" // Formato de fecha
                                        });
                                    </script>
                                                    
                                    
                                    <!-- Botón de guardar -->
                                    <div class="form-group col-md-4 align-self-end">
                                        <input type="hidden" id="idDescanso" name="idDescanso" class="btn btn-info btn-block rounded-0 py-2 custom-bg">
                                        <button type="submit" class="btn-success btn-block rounded-0 py-2 custom-bg">Guardar</button>
                                    </div>
                                </div> 
                            </form>
                        </div>
                    </div>
                </div>
            <!-- </div> -->
        </div>

                        <div class="table-responsive table-scroll" data-mdb-perfect-scrollbar="true" style="position: relative; height: 300px">
                        <table class="table table-striped mb-0">
                            <thead style="background-color: #002d72;">
                            <tr>
                                <th colspan="4">Filtrar</th>
                                <th colspan="2">
                                    <form class="form-inline d-flex" method="GET" action="descansos.php">
                                        <input class="form-control me-2" type="search" name="buscar" placeholder="Buscar" aria-label="Buscar" style="width: 50%;">
                                        <button class="btn btn-info my-2 my-sm-0" type="submit">Buscar</button>
                                    </form>
                                </th>
                            </tr>
                            <tr>
                                <th scope="col" colspan="2">Barbero</th>
                                <th scope="col" colspan="2">Fecha</th>
                                <th scope="col" colspan="1">Eliminar</th>
                                <th scope="col" colspan="1">Editar</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                // Conectar a la base de datos
                                #$conexion = conectar();

                                // Capturar el valor de búsqueda
                                $buscar = isset($_GET['buscar']) ? $_GET['buscar'] : '';

                                // Llamar a la función que muestra los usuarios, pasándole el valor de búsqueda
                                ver_Descansos($conexion, $buscar);

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

    <script>
        function confirmarEliminacion(event) {
            if (!confirm('¿Estás seguro de que deseas eliminar este descanso?')) {
                event.preventDefault();
            }
        }
    </script>
</body>
</html>

<?php
function ver_Descansos($conexion, $buscar = '') {
    // Si hay un valor de búsqueda, agregar una cláusula WHERE a la consulta
    if (!empty($buscar)) {
        $sql = "SELECT idDescanso, fecha, barbero.nombre AS nombreBarbero 
                FROM descanso 
                INNER JOIN barbero ON descanso.idBarbero = barbero.idBarbero 
                WHERE barbero.nombre LIKE '%$buscar%'";
    } else {
        // Consulta predeterminada sin búsqueda
        $sql = "SELECT idDescanso, fecha, barbero.nombre AS nombreBarbero 
                FROM descanso 
                INNER JOIN barbero ON descanso.idBarbero = barbero.idBarbero";
    }    

    $resultado = mysqli_query($conexion, $sql);

    if (!$resultado) {
        echo "<tr><td colspan='3'>Error en la consulta: " . mysqli_error($conexion) . "</td></tr>";
        return;
    }

    if (mysqli_num_rows($resultado) > 0) {
        while ($renglon = mysqli_fetch_assoc($resultado)) {
            $idDescanso = $renglon['idDescanso'];
            $nombre = $renglon['nombreBarbero'];
            $fecha = $renglon['fecha'];
            echo
            "<tr>
                <td colspan=\"2\">$nombre</td>
                <td colspan=\"2\">$fecha</td>
                <td colspan=\"1\">
                    <form method='POST' action='eliminar_descanso.php' style='display:inline;' onsubmit='confirmarEliminacion(event)'>
                        <input type='hidden' name='idDescanso' value='$idDescanso'>
                        <button type='submit' class='btn btn-eliminar'>Eliminar</button>
                    </form>
                </td>
                <td colspan=\"1\">
                    <button type='button' class='btn btn-editar' onclick='modificar($idHorario)'>Editar</button>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No se encontraron resultados</td></tr>";
    }
}
?> 
    <!-- Fuera del ciclo -->
    <script>
        function modificar(idDescanso) {
            // Hacer la solicitud AJAX
            fetch(`getDescanso.php?id=${idDescanso}`)
                .then(response => response.json())
                .then(data => {
                    // Aquí llenamos el formulario con los datos recibidos
                    console.log(data.idBarbero);
                    console.log(data.fecha);
                    console.log(data.idDescanso);
                    document.getElementById('barbero').value = data.idBarbero;
                    document.getElementById('fecha').value = data.fecha;
                    document.getElementById('idDescanso').value = data.idDescanso;
                })
                .catch(error => console.error('Error:', error));
        }
    </script>