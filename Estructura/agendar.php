<?php
// session_start();
include('includes/utilerias.php');

// Verifica que el idUsuario esté en la sesión

if (!isset($_SESSION['administrador']) && !isset($_SESSION['usuario'])) {
    die("Error: No hay ningún usuario autenticado.");
}

$conn = conectar();
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Consultar las citas del usuario
$citas = [];

if (!empty($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
} else if (!empty($_SESSION['administrador'])) {
    $usuario = $_SESSION['idAdmon'];
}

$mensajeCita = '';

// Corrección en el campo citas.idUsuarios (debe ser citas.idUsuario)
$sql = "SELECT citas.idCita, citas.fecha, citas.hora, citas.servicio, 
        citas.idUsuario, barbero.nombre AS nombreBarbero
        FROM citas
        INNER JOIN usuarios ON citas.idUsuario = usuarios.idUsuario
        INNER JOIN barbero ON citas.idBarbero = barbero.idBarbero
        WHERE usuarios.idUsuario = $usuario
        ORDER BY citas.fecha DESC, citas.hora DESC";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $citas[] = $row;
    }
} else {
    $mensajeCita = "No se encontró ninguna cita para el usuario.";
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $conn->real_escape_string($_POST['nombre']); // Escapando cadenas para prevenir SQL Injection
    $fecha = $conn->real_escape_string($_POST['fecha']);
    $hora = $conn->real_escape_string($_POST['hora']);
    $servicio = $conn->real_escape_string($_POST['servicio']);
    $idBarbero = $conn->real_escape_string($_POST['barbero']);

    if (empty($usuario)) {
        die("Error: idUsuarios no está definido.");
    }

    $sql = "SELECT * FROM citas WHERE fecha = '$fecha' AND hora = '$hora' AND idBarbero = '$idBarbero'";
    $result = $conn->query($sql);

    if (!$result) {
        die("Error en la consulta: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        redireccionar("La hora y fecha seleccionadas ya están ocupadas. Por favor, selecciona otra hora.", "agendar.php");
        exit;
    }

    // Insertar datos en la tabla citas
    $sql = "INSERT INTO citas (fecha, hora, servicio, nombre, idBarbero, idUsuario) VALUES ('$fecha', '$hora', '$servicio', '$nombre', '$idBarbero', '$usuario')";

    if ($conn->query($sql) === TRUE) {
        redireccionar("Cita agendada correctamente.", "agendar.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

if (isset($_POST['fecha']) && isset($_POST['barbero'])) {
    $fecha = $_POST['fecha'];
    $idBarbero = $_POST['barbero'];

    $sql = "SELECT hora FROM citas WHERE fecha = '$fecha' AND idBarbero = '$idBarbero'";
    $result = $conn->query($sql);

    $horasOcupadas = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $horasOcupadas[] = $row['hora'];
        }
    }
    echo json_encode($horasOcupadas);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar</title>
    <link rel="stylesheet" href="../Diseno/agendar.css">
    <script src="../Scripts/agendar.js" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

        <!-- <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->

        <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"  defer></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" defer></script>

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">

</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand me-auto" href="#">Pa' La Barber Shop</a>
            <!-- <div class="titulo">
                <h1 class="offcanvas-title" id="offcanvasNavbarLabel">AGENDAR</h1><br>
            </div> -->

            <a href="index.php" class="login-button order-lg-2">Regresar</a>

            <!-- Botón de hamburguesa -->
            <button class="navbar-toggler order-lg-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar"
                aria-labelledby="offcanvasNavbarLabel">

                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel"><img src="../Imagenes/logo.png" alt="" style="width: 70px"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>

                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-center flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a href="#" class="nav-link mx-lg-2 active" aria-current="page"  onclick="mostrarDiv('agendar', this)">Agendar</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link mx-lg-2 active" aria-current="page"  onclick="mostrarDiv('miCita', this)">Mi cita</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <main>
        <!-- Div de agendar -->
        <div id="agendar" class="container" >
            <div class="row justify-content-center" >
                <div class="col-12 col-md-8 col-lg-6 pb-5" >
                

                    <form  id="agendarForm" action="agendar.php" method="post" onsubmit="return validarFormulario()">

                        <div class="card">
                            <div class="card-header p-0">
                                <div class="custom-bg text-white text-center py-2">
                                <div class="custom-bg ">
                                        <h3 ><i class="fa fa-envelope"></i> Agendar</h3>
                                        <p class="m-0">Agenda tu cita con nosotros</p>
                                    </div>
                                </div>
                            </div>

                            <!-- <label for="nombre">Nombre</label><br> -->
                            <!-- <input id="nombre" name="nombre" type="text" placeholder="Escribe tu nombre" required><br> -->

                            <div class="card-body p-3" >

                                <!--Body-->
                                <div class="form-group" >
                                    <div class="input-group mb-2" >
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-user custom-icon-color"></i></div>
                                        </div>
                                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required>
                                    </div>
                                </div>

                                <!-- <label for="barbero">Barbero</label><br>
                                <select id="barbero" name="barbero" required onchange="actualizarHorasDisponibles()">
                                    <option value="">Selecciona un barbero</option>
                                    <option value="1">Alex</option>
                                    <option value="2">Erick</option>
                                </select><br> -->

                                <div class="form-group">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-list custom-icon-color"></i></div>
                                        </div>
                                        <select class="form-control" id="barbero" name="barbero" required onchange="actualizarHorasDisponibles()">
                                            <option value="" disabled selected>Selecciona a tu barbero</option>
                                            <option value="1">Alex</option>
                                            <option value="2">Mujer</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- <label for="fecha">Fecha</label><br>
                                <input id="fecha" name="fecha" type="date" min="2024-01-01" required onchange="actualizarHorasDisponibles()"><br> -->

                                <div class="form-group">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-calendar-alt custom-icon-color"></i></div>
                                        </div>
                                        <input type="date" class="form-control" id="fecha" name="fecha" placeholder="Selecciona la fecha">
                                    </div>
                                </div>

                                




                                <!-- <label for="hora">Seleccionar hora</label><br>
                                <select id="hora" name="hora" required>
                                    <option value="">Selecciona una hora</option>
                                    <option value="11:00:00">11:00 a.m.</option>
                                    <option value="11:40:00">11:40 a.m.</option>
                                    <option value="12:20:00">12:20 p.m.</option>
                                    <option value="13:00:00">01:00 p.m.</option>
                                    <option value="13:40:00">01:40 p.m.</option>
                                    <option value="14:20:00">02:20 p.m.</option>
                                    <option value="16:00:00">04:00 p.m.</option>
                                    <option value="16:40:00">04:40 p.m.</option>
                                    <option value="17:20:00">05:20 p.m.</option>
                                    <option value="18:00:00">06:00 p.m.</option>
                                    <option value="18:40:00">06:40 p.m.</option>
                                    <option value="19:20:00">07:20 p.m.</option>
                                    <option value="20:00:00">08:00 p.m.</option>
                                </select><br> -->

                                <div class="form-group">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-list custom-icon-color"></i></div>
                                        </div>
                                        <select class="form-control" id="hora" name="hora" required onchange="actualizarHorasDisponibles()">
                                            <option value="">Selecciona la hora</option>
                                            <option value="11:00:00">11:00 a.m.</option>
                                            <option value="11:40:00">11:40 a.m.</option>
                                            <option value="12:20:00">12:20 p.m.</option>
                                            <option value="13:00:00">01:00 p.m.</option>
                                            <option value="13:40:00">01:40 p.m.</option>
                                            <option value="14:20:00">02:20 p.m.</option>
                                            <option value="16:00:00">04:00 p.m.</option>
                                            <option value="16:40:00">04:40 p.m.</option>
                                            <option value="17:20:00">05:20 p.m.</option>
                                            <option value="18:00:00">06:00 p.m.</option>
                                            <option value="18:40:00">06:40 p.m.</option>
                                            <option value="19:20:00">07:20 p.m.</option>
                                            <option value="20:00:00">08:00 p.m.</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- <label for="servicio">Servicio</label><br>
                                <select id="servicio" name="servicio" required>
                                    <option value="">Selecciona una opción</option>
                                    <option value="Corte y barba">Corte y barba</option>
                                    <option value="Solo corte">Solo corte</option>
                                    <option value="Solo barba">Solo barba</option>
                                </select><br> -->
                                
                                <div class="form-group">
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fa fa-list custom-icon-color"></i></div>
                                        </div>
                                        <select class="form-control" id="servicio" name="servicio" required>
                                            <option value="">Selecciona la opcion de tu servicio</option>
                                            <option value="Corte y barba">Corte y barba</option>
                                            <option value="Solo corte">Solo corte</option>
                                            <option value="Solo barba">Solo barba</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="text-center">
                                    <input type="submit" value="Agendar" class="btn btn-info btn-block rounded-0 py-2 custom-bg">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Div de mi cita -->
        <div id="miCita" class="container" style="display: none">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6 pb-5">
                    <div class="card ">
                            <div class="card-header p-0">
                                <div class="custom-bg text-white text-center py-2">
                                    <h3><i class="fa fa-envelope"></i> Mi Cita</h3>
                                    <p class="m-0">Estas son tus citas pendientes</p>
                                </div>
                            </div>
                        <?php if (!empty($citas)): ?>
                            <?php foreach ($citas as $cita): ?>
                                    
                                            <h4 class="text-white">Cita a nombre de:</h4>
                                            <p><?php echo htmlspecialchars($cita['idUsuario']); ?></p>
                                            <h4>Fecha y hora de la cita:</h4>
                                            <p><?php echo htmlspecialchars($cita['fecha']) . ' ' . htmlspecialchars($cita['hora']); ?></p>
                                            <h4>Barbero:</h4>
                                            <p><?php echo htmlspecialchars($cita['nombreBarbero']); ?></p>
                                            <h4>Servicio:</h4>
                                            <p><?php echo htmlspecialchars($cita['servicio']); ?></p>
                                            <form action="cancelar_cita.php" method="post" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta cita?');">
                                                <input type="hidden" name="idCita" value="<?php echo htmlspecialchars($cita['idCita']); ?>">
                                                <button type="submit" name="eliminar" id="elim">Eliminar cita</button>
                                            </form>
                                            <hr>
                        
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p ><?php echo htmlspecialchars($mensajeCita); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div> 
        </div>
    </main>

    <script>
        function mostrarDiv(divId, element) {
            document.getElementById('agendar').style.display = 'none';
            document.getElementById('miCita').style.display = 'none';
            // document.getElementById('barbero').style.display = 'none';
            
            document.getElementById(divId).style.display = 'block';

            const links = document.querySelectorAll(".menu a");
            links.forEach((link) => link.classList.remove("selected"));

            element.classList.add("selected");
        }

        function validarFormulario() {
            const fecha = document.getElementById('fecha').value;
            const hora = document.getElementById('hora').value;
            const servicio = document.getElementById('servicio').value;
            const barbero = document.getElementById('barbero').value;

            if (fecha === "" || hora === "" || servicio === "" || barbero === "") {
                alert("Por favor, completa todos los campos.");
                return false;
            }

            return true;
        }

        async function actualizarHorasDisponibles() {
        const fecha = document.getElementById('fecha').value;
        const barbero = document.getElementById('barbero').value;

        if (fecha && barbero) {
            try {
                const response = await fetch(`obtener_horas_ocupadas.php?fecha=${fecha}&barbero=${barbero}`);
                const horasOcupadas = await response.json();
                console.log("Horas ocupadas:", horasOcupadas);

                const selectHora = document.getElementById('hora');
                const opciones = selectHora.options;

                for (let i = 0; i < opciones.length; i++) {
                    const opcion = opciones[i];
                    const opcionValue = opcion.value.trim(); // Normalización
                    console.log("Revisando opción:", opcionValue);
                    if (horasOcupadas.includes(opcionValue)) {
                        console.log("Deshabilitando opción:", opcionValue);
                        opcion.disabled = true;
                    } else {
                        opcion.disabled = false;
                    }
                }
            } catch (error) {
                console.error("Error al obtener horas ocupadas:", error);
            }
        }
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
