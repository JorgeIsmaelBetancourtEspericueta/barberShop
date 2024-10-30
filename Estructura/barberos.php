<?php
include('includes/utilerias.php');
session_start();

// Verifica que el usuario esté autenticado
if (!isset($_SESSION['administrador']) && !isset($_SESSION['usuario'])) {
    die("Error: No hay ningún usuario autenticado.");
}

$conexion = conectar();

// Agregar barbero
if (isset($_POST['accion']) && $_POST['accion'] == 'agregar') {
    if (isset($_POST['nombre']) && isset($_POST['telefono'])) {
        $nombre = $_POST['nombre'];
        $telefono = $_POST['telefono'];

        // Validar longitud del teléfono
        if (strlen($telefono) !== 10) {
            $_SESSION['error'] = "El número de teléfono debe tener 10 dígitos.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            // Consultas para verificar si el barbero ya existe por nombre o teléfono
            $checkNombreQuery = "SELECT * FROM barbero WHERE nombre='$nombre'";
            $checkTelefonoQuery = "SELECT * FROM barbero WHERE telefono='$telefono'";
            
            $nombreResult = mysqli_query($conexion, $checkNombreQuery);
            $telefonoResult = mysqli_query($conexion, $checkTelefonoQuery);

            if (mysqli_num_rows($nombreResult) > 0 && mysqli_num_rows($telefonoResult) > 0) {
                $_SESSION['error'] = "El barbero ya existe en la base de datos (por nombre y teléfono).";
            } elseif (mysqli_num_rows($nombreResult) > 0) {
                $_SESSION['error'] = "El nombre del barbero ya existe en la base de datos.";
            } elseif (mysqli_num_rows($telefonoResult) > 0) {
                $_SESSION['error'] = "El teléfono del barbero ya existe en la base de datos.";
            } else {
                // Consulta SQL para insertar en la base de datos
                $query = "INSERT INTO barbero (nombre, telefono) VALUES ('$nombre', '$telefono')";
                if (mysqli_query($conexion, $query)) {
                    // Redireccionar para evitar reenvío del formulario
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();
                } else {
                    $_SESSION['error'] = "Error al insertar el barbero.";
                }
            }
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    } else {
        $_SESSION['error'] = "Error: Los campos 'nombre' y/o 'telefono' no están definidos.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}


// Editar barbero
if (isset($_POST['accion']) && $_POST['accion'] == 'editar') {
    if (isset($_POST['idBarbero']) && isset($_POST['nombre']) && isset($_POST['telefono'])) {
        $idBarbero = $_POST['idBarbero'];
        $nombre = $_POST['nombre'];
        $telefono = $_POST['telefono'];

        // Validar que los campos no estén vacíos
        if (empty($nombre) || empty($telefono)) {
            $error = "Los campos 'nombre' y 'teléfono' no pueden estar vacíos.";
            echo json_encode(['success' => false, 'error' => $error]);
            exit();
        } else if (strlen($telefono) !== 10) {
            $error = "El número de teléfono debe tener 10 dígitos.";
            echo json_encode(['success' => false, 'error' => $error]);
            exit();
        } else {
            // Consulta SQL para verificar si el barbero ya existe
            $checkQuery = "SELECT * FROM barbero WHERE (nombre='$nombre' OR telefono='$telefono') AND idBarbero != '$idBarbero'";
            $result = mysqli_query($conexion, $checkQuery);
            if (mysqli_num_rows($result) == 0) {
                // Consulta SQL para actualizar en la base de datos
                $query = "UPDATE barbero SET nombre='$nombre', telefono='$telefono' WHERE idBarbero='$idBarbero'";
                if (mysqli_query($conexion, $query)) {
                    // Respuesta exitosa
                    echo json_encode(['success' => true]);
                } else {
                    // Error al ejecutar la consulta
                    echo json_encode(['success' => false, 'error' => 'Error al actualizar el barbero.']);
                }
            } else {
                // Duplicado encontrado
                echo json_encode(['success' => false, 'error' => "Ya existe un barbero con ese nombre o teléfono."]);
            }
        }
    } else {
        echo json_encode(['success' => false, 'error' => "Error: Los campos requeridos no están definidos."]);
    }
    exit(); // Asegúrate de salir después de procesar la solicitud
}


// Eliminar barbero
if (isset($_POST['accion']) && $_POST['accion'] == 'eliminar') {
    if (isset($_POST['idBarbero'])) {
        $idBarbero = $_POST['idBarbero'];

        // Consulta SQL para eliminar de la base de datos
        $query = "DELETE FROM barbero WHERE idBarbero='$idBarbero'";
        mysqli_query($conexion, $query);
    } else {
        echo "Error: El campo 'idBarbero' no está definido.";
    }
}


// Obtiene los barberos existentes
$barberos = mysqli_query($conexion, "SELECT * FROM barbero");

if (isset($_SESSION['error'])) {
    echo "<script>alert('" . $_SESSION['error'] . "');</script>";
    unset($_SESSION['error']); // Eliminar el mensaje después de mostrarlo
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Barberos</title>
    <style>
        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 10px;
        }

        table {
            width: 100%;
            margin-top: 20px;
        }

        th {
            background-color: #f2f2f2;
        }

        .btn {
            cursor: pointer;
            padding: 5px 10px;
            margin: 2px;
        }
    </style>
</head>

<body>
    <?php if (isset($error)): ?>
        <script>
            alert("<?php echo $error; ?>");
        </script>
    <?php endif; ?>

    <h2>Formulario para agregar barbero</h2>
    <form action="" method="POST" id="barberoForm">
        <label for="nombre">Nombre del Barbero:</label>
        <input type="text" id="nombre" name="nombre" required>
        <label for="telefono">Teléfono:</label>
        <input type="tel" id="telefono" name="telefono" required>
        <button type="submit" name="accion" value="agregar">Agregar Barbero</button>
    </form>

    <h3>Lista de Barberos</h3>
    <table id="tablaBarberos">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Modificaciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($barberos)): ?>
                <tr data-id="<?php echo $row['idBarbero']; ?>">
                    <td><?php echo $row['nombre']; ?></td>
                    <td><?php echo $row['telefono']; ?></td>
                    <td>
                        <button class="btn" onclick="editarBarbero(<?php echo $row['idBarbero']; ?>)">Editar</button>
                        <button class="btn" onclick="eliminarBarbero(<?php echo $row['idBarbero']; ?>)">Eliminar</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <script>
        function editarBarbero(id) {
            const fila = document.querySelector(`tr[data-id='${id}']`);
            const nombreActual = fila.cells[0].textContent;
            const telefonoActual = fila.cells[1].textContent;

            const nombreNuevo = prompt("Nuevo nombre:", nombreActual);
            const telefonoNuevo = prompt("Nuevo teléfono:", telefonoActual);

            if (nombreNuevo && telefonoNuevo) {
                // Realiza una solicitud POST al servidor
                const formData = new FormData();
                formData.append('accion', 'editar');
                formData.append('idBarbero', id);
                formData.append('nombre', nombreNuevo);
                formData.append('telefono', telefonoNuevo);

                fetch('', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json()) // Asegúrate de que la respuesta se maneje como JSON
                    .then(data => {
                        if (data.success) {
                            // Solo actualiza la visualización si la operación fue exitosa
                            fila.cells[0].textContent = nombreNuevo;
                            fila.cells[1].textContent = telefonoNuevo;
                        } else {
                            alert(data.error); // Muestra el mensaje de error recibido
                            // No actualices la visualización en caso de error
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        }


        function eliminarBarbero(id) {
            if (confirm("¿Estás seguro de que deseas eliminar este barbero?")) {
                // Realiza una solicitud POST al servidor
                const formData = new FormData();
                formData.append('accion', 'eliminar');
                formData.append('idBarbero', id);

                fetch('', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => {
                        if (response.ok) {
                            const fila = document.querySelector(`tr[data-id='${id}']`);
                            fila.parentNode.removeChild(fila);
                        } else {
                            alert('Error al eliminar el barbero.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        }
    </script>
</body>

</html>