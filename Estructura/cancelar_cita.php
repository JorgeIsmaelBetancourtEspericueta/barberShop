<?php
include('includes/utilerias.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idCita = isset($_POST['idCita']) ? intval($_POST['idCita']) : 0;

    if ($idCita > 0) {
        // Conectar a la base de datos
        $conexion = conectar();

        // Preparar y ejecutar la consulta de eliminación
        $sql = "DELETE FROM citas WHERE idCita = ?";
        $stmt = mysqli_prepare($conexion, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'i', $idCita);
            $resultado = mysqli_stmt_execute($stmt);

            // if ($resultado) {
                //redireccionar("Cita cancelada exitosamente.", "citas.php");
            // } else {
            //    redireccionar("Error al cancelar la cita.", "agendar.php");
            // }
            
            mysqli_stmt_close($stmt);
        } else {
            echo "Error en la preparación de la consulta: " . mysqli_error($conexion);
        }

        mysqli_close($conexion);
    } else {
        echo "ID de cita inválido.";
    }
}
?>
