<?php
// Conectar a la base de datos
include('includes/utilerias.php');

$conexion = conectar();

// Obtener el id del descanso desde la solicitud
$idDescanso = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($idDescanso > 0) {
    // Preparar la consulta para obtener el descanso
    $sql = "SELECT idBarbero, fecha, idDescanso FROM descanso WHERE idDescanso = ?";
    
    // Preparar la consulta SQL
    if ($stmt = $conexion->prepare($sql)) {
        // Asignar parámetros
        $stmt->bind_param("i", $idDescanso);
        
        // Ejecutar la consulta
        $stmt->execute();
        
        // Obtener los resultados
        $resultado = $stmt->get_result();
        
        if ($resultado->num_rows > 0) {
            // Convertir los resultados en un arreglo asociativo
            $descanso = $resultado->fetch_assoc();
            
            // Enviar los datos en formato JSON
            echo json_encode($descanso);
        } else {
            echo json_encode(['error' => 'No se encontró el descanso']);
        }
        
        // Cerrar la declaración
        $stmt->close();
    }
}

// Cerrar la conexión
$conexion->close();
?>
