<?php
// Incluir el archivo que define la función conectar
include('includes/utilerias.php');

// Conectar a la base de datos
$conexion = conectar();

// Llamar a la función que muestra las citas
ver_citas($conexion);

// Cerrar la conexión
mysqli_close($conexion);
?>

