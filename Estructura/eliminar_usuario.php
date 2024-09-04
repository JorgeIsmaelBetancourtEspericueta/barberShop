<?php
include('includes/utilerias.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idUsuario = validar($_POST['idUsuario']);

    if ($idUsuario == '') {
        redireccionar('ID de usuario no válido', 'usuario.php');
        return;
    }

    $conexion = conectar();

    if (!$conexion) {
        redireccionar('Error en la conexión', 'usuario.php');
        return;
    }

    $sql = "DELETE FROM usuarios WHERE idUsuario = '$idUsuario'";

    if (mysqli_query($conexion, $sql)) {
        redireccionar('Usuario eliminado exitosamente', 'usuario.php');
    } else {
        redireccionar('Error al eliminar el usuario: ' . mysqli_error($conexion), 'usuario.php');
    }

    mysqli_close($conexion);
} else {
    redireccionar('Método no permitido', 'usuarios.php');
}
?>