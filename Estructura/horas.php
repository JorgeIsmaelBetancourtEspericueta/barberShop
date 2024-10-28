<?php
header('Content-Type: application/json');
include('includes/utilerias.php');
$conn = conectar();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = $_POST['fecha'];
    $idBarbero = $_POST['idBarbero'];
    // Preparar la consulta
    $diaSemanaIngles = date('l', strtotime($fecha));

    $diasEspañol = [
        'Sunday' => 'Domingo',
        'Monday' => 'Lunes',
        'Tuesday' => 'Martes',
        'Wednesday' => 'Miércoles',
        'Thursday' => 'Jueves',
        'Friday' => 'Viernes',
        'Saturday' => 'Sábado'
    ];

    $diaSemanaEspañol = $diasEspañol[$diaSemanaIngles];
    $sql = "SELECT horaInicio,horaFin FROM horarios WHERE idBarbero = ? AND diaSemana = '$diaSemanaEspañol'";
    
    // Preparar la sentencia
    if ($stmt = $conn->prepare($sql)) {
        // Vincular el parámetro
        $stmt->bind_param("i", $idBarbero);
        
        // Ejecutar la sentencia
        $stmt->execute();
        
        // Obtener el resultado
        $result = $stmt->get_result();
        
        $horarios = [];
        while ($row = $result->fetch_assoc()) {
            $horarios[] = ['inicio' => $row['horaInicio'], 'fin' => $row['horaFin']];
        }

        // Devolver los resultados en formato JSON
        echo json_encode(['success' => true, 'horarios' => $horarios]);
        
        // Cerrar la sentencia
        $stmt->close();
    } else {
        // Si la preparación de la consulta falla
        echo json_encode(['success' => false, 'message' => 'Error en la consulta']);
    }

    // Cerrar la conexión a la base de datos
    $conn->close(); // Cambia $conexion por $conn
}
?>