<?php
include('includes/utilerias.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idHorario = validar($_POST['idHorario']);

    if ($idHorario == '') {
        redireccionar('No se pudo completar la eliminación', 'horarios.php');
        return;
    }

    $conexion = conectar();

    if (!$conexion) {
        redireccionar('Error en la conexión', 'horarios.php');
        return;
    }

    $sql = "DELETE FROM horarios WHERE idHorario = '$idHorario'";

    if (mysqli_query($conexion, $sql)) {
        redireccionar('Horario eliminado exitosamente', 'horarios.php');
    } else {
        redireccionar('Error al eliminar: ' . mysqli_error($conexion), 'horarios.php');
    }

    mysqli_close($conexion);
} else {
    redireccionar('Método no permitido', 'horarios.php');
}
?>