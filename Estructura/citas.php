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
    <script src="../scripts/cita.js" defer></script>

</head>
<body>
    <header>
        <h1>Bienvenido Alexander</h1>
    </header>

    <main>
        <table>
            <tr>
                <th colspan="3">
                    <label for="filtroFecha">Filtrar la información</label>
                </th>
                <th colspan="3">
                    <select name="fecha" id="filtroFecha">
                        <option value="">Seleccione una opción</option>
                        <option value="<?php echo date('Y-m-d'); ?>">Citas de hoy</option>
                        <option value="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">Citas de mañana</option>
                        <option value="all">Todas</option>
                    </select>
                    <button onclick="filtrar()" class="boton">Buscar</button>
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
        </table>
    </main>

    <footer>
        <p>&copy; Todos los derechos reservados</p>
    </footer>

</body>
</html>

