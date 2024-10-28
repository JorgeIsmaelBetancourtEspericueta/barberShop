<?php
header('Content-Type: application/json');
include('includes/utilerias.php');
$conn = conectar();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idBarbero = $_POST['idBarbero'];
    // Preparar la consulta
    $sql = "SELECT fecha FROM descanso WHERE idBarbero = ?";
    
    // Preparar la sentencia
    if ($stmt = $conn->prepare($sql)) {
        // Vincular el parámetro
        $stmt->bind_param("i", $idBarbero);
        
        // Ejecutar la sentencia
        $stmt->execute();
        
        // Obtener el resultado
        $result = $stmt->get_result();
        
        // Inicializar un array para almacenar las fechas
        $fechas = [];
        
        // Recorrer los resultados y almacenarlos en el array
        while ($row = $result->fetch_assoc()) {
            $fechas[] = $row['fecha'];
        }

        // Devolver los resultados en formato JSON
        echo json_encode(['success' => true, 'fechas' => $fechas]);
        
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