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
        if (strlen($telefono) !== 10 || !ctype_digit($telefono)) {
            $error = "El número de teléfono debe tener 10 dígitos.";
            redireccionar("El número de teléfono debe tener 10 dígitos.", "barberos.php");
            exit();
        } else {
            // Consultas para verificar si el barbero ya existe por nombre o teléfono
            $checkNombreQuery = "SELECT * FROM barbero WHERE nombre='$nombre'";
            $checkTelefonoQuery = "SELECT * FROM barbero WHERE telefono='$telefono'";

            $nombreResult = mysqli_query($conexion, $checkNombreQuery);
            $telefonoResult = mysqli_query($conexion, $checkTelefonoQuery);

            if (mysqli_num_rows($nombreResult) > 0 && mysqli_num_rows($telefonoResult) > 0) {
                redireccionar("El barbero ya existe en la base de datos (por nombre y teléfono).", "barberos.php");
            } elseif (mysqli_num_rows($nombreResult) > 0) {
                $error = "El nombre del barbero ya existe en la base de datos.";
                redireccionar($error, "barberos.php");
            } elseif (mysqli_num_rows($telefonoResult) > 0) {
                $error = "El teléfono del barbero ya existe en la base de datos.";
                redireccionar($error, "barberos.php");
            } else {
                // Consulta SQL para insertar en la base de datos
                $query = "INSERT INTO barbero (nombre, telefono) VALUES ('$nombre', '$telefono')";
                if (mysqli_query($conexion, $query)) {
                    // Redireccionar si la inserción fue exitosa
                    redireccionar("Barbero agregado exitosamente", "barberos.php");
                } else {
                    $error = "Error al insertar el barbero.";
                    redireccionar($error, "barberos.php");
                }
            }
        }

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
            redireccionar("Los campos 'nombre' y 'teléfono' no pueden estar vacíos.", "barberos.php");
            exit();
        } else if (strlen($telefono) !== 10 || !ctype_digit($telefono)) {
            $error = "El número de teléfono debe tener 10 dígitos.";
            redireccionar($error, "barberos.php");
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
                    redireccionar("Información actualizada correctamente", "barberos.php");
                } else {
                    redireccionar('Error al actualizar el barbero.', "barberos.php");
                }
            } else {
                // Duplicado encontrado
                redireccionar("Ya existe un barbero con ese nombre o teléfono.", "barberos.php");
            }
        }
    } else {
        redireccionar("Los campos requeridos no están definidos.", "barberos.php");
    }
}

// Obtiene los barberos existentes
$barberos = mysqli_query($conexion, "SELECT * FROM barbero");


if (isset($_POST['accion']) && $_POST['accion'] == 'eliminar') {
    if (isset($_POST['idBarbero'])) {
        $idBarbero = $_POST['idBarbero'];
        $query = "DELETE FROM barbero WHERE idBarbero='$idBarbero'";
        if (mysqli_query($conexion, $query)) {
            echo json_encode([
                "success" => true,
                "message" => "Barbero eliminado exitosamente"
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Error al eliminar en la base de datos."
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Error al eliminar el barbero"
        ]);
    }
    exit();
}





if (isset($_SESSION['error'])) {
    echo "<script>alert('" . $_SESSION['error'] . "');</script>";
    unset($_SESSION['error']); // Eliminar el mensaje después de mostrarlo
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">



</head>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Barberos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../Diseno/estiloPrincipal.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../Diseno/estiloBarberos.css">

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
            <button class="navbar-toggler order-lg-3" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
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
                                        <h2>Nuestros barberos</h2>
                                        <form action="" method="POST" id="barberoForm">
                                            <label for="nombre">Nombre del Barbero:</label>
                                            <input type="text" id="nombre" name="nombre" required>
                                            <label for="telefono">Teléfono:</label>
                                            <input type="tel" id="telefono" name="telefono" required>
                                            <input type="hidden" id="idBarbero" name="idBarbero">
                                            <button type="submit" id="botonForm" name="accion" value="agregar">Agregar
                                                Barbero</button>
                                        </form>

                                        <h3>Lista de Barberos</h3>
                                        <table id="tablaBarberos">
                                            <thead>
                                                <tr>
                                                    <th>Nombre</th>
                                                    <th>Teléfono</th>
                                                    <th>Modificar</th>
                                                    <th>Eliminar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($row = mysqli_fetch_assoc($barberos)): ?>
                                                    <tr data-id="<?php echo $row['idBarbero']; ?>">
                                                        <td><?php echo $row['nombre']; ?></td>
                                                        <td><?php echo $row['telefono']; ?></td>
                                                        <td>
                                                            <button class="btn btn-editar"
                                                                onclick="editarBarbero(<?php echo $row['idBarbero']; ?>)">Editar</button>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-eliminar"
                                                                onclick="eliminarBarbero(<?php echo $row['idBarbero']; ?>)">Eliminar</button>
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

                                                // Rellena el formulario con los datos actuales
                                                document.getElementById('nombre').value = nombreActual;
                                                document.getElementById('telefono').value = telefonoActual;
                                                document.getElementById('idBarbero').value = id;

                                                // Cambia el botón de 'Agregar' a 'Guardar'
                                                document.getElementById('botonForm').innerText = 'Guardar';
                                                document.getElementById('botonForm').value = 'editar';
                                            }

                                            function eliminarBarbero(id) {
                                                if (confirm("¿Estás seguro de que deseas eliminar este barbero?")) {
                                                    const formData = new FormData();
                                                    formData.append('accion', 'eliminar');
                                                    formData.append('idBarbero', id);

                                                    fetch('', {
                                                        method: 'POST',
                                                        body: formData
                                                    })
                                                        .then(response => response.text()) // Cambia a .text() temporalmente para depurar
                                                        .then(text => {
                                                            console.log("Respuesta del servidor:", text); // Verifica que es JSON válido
                                                            return JSON.parse(text); // Convertir manualmente a JSON
                                                        })
                                                        .then(data => {
                                                            if (data.success) {
                                                                const fila = document.querySelector(`tr[data-id='${id}']`);
                                                                if (fila) {
                                                                    fila.parentNode.removeChild(fila); // Elimina la fila visualmente
                                                                }
                                                                redireccionar("Barbero eliminado exitosamente", "barberos.php")
                                                            } else {
                                                                redireccionar("No se puede eliminar el barbero, tiene citas registradas", "barberos.php");
                                                            }
                                                        })
                                                        .catch(error => console.error('Error:', error));
                                                }
                                            }



                                            function redireccionar(mensaje, dir) {
                                                Swal.fire({
                                                    title: "Mensaje",
                                                    text: mensaje,
                                                    icon: "info",
                                                    confirmButtonColor: "#4CAF50",
                                                    confirmButtonText: "OK"
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        console.log("Redireccionando a: " + dir); // Para depurar
                                                        window.location.href = dir;
                                                    }
                                                });
                                            }


                                        </script>
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
</body>

</html>