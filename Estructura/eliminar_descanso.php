<?php
include('includes/utilerias.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idDescanso = validar($_POST['idDescanso']);

    if ($idDescanso == '') {
        redireccionar('No se pudo completar la eliminación', 'descansos.php');
        return;
    }

    $conexion = conectar();

    if (!$conexion) {
        redireccionar('Error en la conexión', 'descansos.php');
        return;
    }

    $sql = "DELETE FROM descanso WHERE idDescanso = '$idDescanso'";

    if (mysqli_query($conexion, $sql)) {
        redireccionar('Descanso eliminado exitosamente', 'descansos.php');
    } else {
        redireccionar('Error al eliminar: ' . mysqli_error($conexion), 'descansos.php');
    }

    mysqli_close($conexion);
} else {
    redireccionar('Método no permitido', 'descansos.php');
}
?>