<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pa' La Barber Shop - Usuarios Registrados</title>
    <link rel="stylesheet" href="../Diseno/estiloCitas.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Ga+Maamli&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Usuarios Registrados</h1>
    </header>

    <main>
        <table>
            <tr class="encabezados">           
                <th>Nombre</th>
                <th>Teléfono</th>
                <th></th>
            </tr>

            <?php
            // Incluye el archivo que define la función conectar()
            include('includes/utilerias.php');

            // Conectar a la base de datos
            $conexion = conectar();

            // Llamar a la función ver_usuarios
            ver_usuarios($conexion);

            // Cerrar la conexión
            mysqli_close($conexion);
            ?>
        </table>
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
function ver_usuarios($conexion) {
    $sql = "SELECT idUsuario, nombre, telefono FROM usuarios WHERE es_admin = 0";
    $resultado = mysqli_query($conexion, $sql);

    if (!$resultado) {
        echo "<tr><td colspan='3'>Error en la consulta: " . mysqli_error($conexion) . "</td></tr>";
        return;
    }

    if (mysqli_num_rows($resultado) > 0) {
        while ($renglon = mysqli_fetch_assoc($resultado)) {
            $idUsuario = $renglon['idUsuario'];
            $nombre = $renglon['nombre'];
            $telefono = $renglon['telefono'];

            echo
            "<tr>
                <td>$nombre</td>
                <td>$telefono</td>
                <td>
                    <form method='POST' action='eliminar_usuario.php' style='display:inline;' onsubmit='confirmarEliminacion(event)'>
                        <input type='hidden' name='idUsuario' value='$idUsuario'>
                        <button type='submit'>Eliminar</button>
                    </form>
                </td>
            </tr>"; 
        }
    } else {
        echo "<tr><td colspan='3'>No se encontraron usuarios registrados.</td></tr>";
    }
}
?>