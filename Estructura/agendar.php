<?php
session_start();
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
$usuario = $_SESSION['usuario'];
$mensajeCita = '';
$sql = "SELECT citas.idCita, citas.fecha, citas.hora, citas.servicio, citas.idUsuario, barbero.nombre AS nombreBarbero
        FROM citas
        INNER JOIN usuarios ON citas.idUsuarios = usuarios.idUsuario
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
    $sql = "INSERT INTO citas (fecha, hora, servicio, idUsuario, idBarbero, idUsuarios) VALUES ('$fecha', '$hora', '$servicio', '$nombre', '$idBarbero', '$usuario')";

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
</head>
<body>
    <header>
        <div class="titulo">
            <h1>AGENDAR</h1><br>
        </div>
        <div class="menu">
            <a href="#" class="a1" onclick="mostrarDiv('agendar', this)">Agendar</a>
            <a href="#" class="a2" onclick="mostrarDiv('miCita', this)">Mi cita</a>
        </div>
    </header>
    <main>
        <!-- Div de agendar -->
        <div id="agendar" class="contenedor">
            <form id="agendarForm" action="agendar.php" method="post" onsubmit="return validarFormulario()">
                <label for="nombre">Nombre</label><br>
                <input id="nombre" name="nombre" type="text" placeholder="Escribe tu nombre" required><br>

                <label for="barbero">Barbero</label><br>
                <select id="barbero" name="barbero" required onchange="actualizarHorasDisponibles()">
                    <option value="">Selecciona un barbero</option>
                    <option value="1">Alex</option>
                    <option value="2">Erick</option>
                </select><br>
                
                <label for="fecha">Fecha</label><br>
                <input id="fecha" name="fecha" type="date" min="2024-01-01" required onchange="actualizarHorasDisponibles()"><br>
                
                <label for="hora">Seleccionar hora</label><br>
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
                </select><br>
                
                <label for="servicio">Servicio</label><br>
                <select id="servicio" name="servicio" required>
                    <option value="">Selecciona una opción</option>
                    <option value="Corte y barba">Corte y barba</option>
                    <option value="Solo corte">Solo corte</option>
                    <option value="Solo barba">Solo barba</option>
                </select><br>
                
                
                
                <button type="submit">Agendar</button>
            </form>
        </div>
        
        <!-- Div de mi cita -->
        <div id="miCita" class="contenedor2" style="display: none">
            <?php if (!empty($citas)): ?>
                <?php foreach ($citas as $cita): ?>
                    <h4>Cita a nombre de:</h4>
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
                <p><?php echo htmlspecialchars($mensajeCita); ?></p>
            <?php endif; ?>
        </div>
    </main>

    <script>
        function mostrarDiv(divId, element) {
            document.getElementById('agendar').style.display = 'none';
            document.getElementById('miCita').style.display = 'none';
            document.getElementById('barbero').style.display = 'none';
            
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
</body>
</html>
