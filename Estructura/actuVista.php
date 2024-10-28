<?php
// Incluir el archivo que define la función conectar
include('includes/utilerias.php');
header('Content-Type: application/json');
// Conectar a la base de datos
$conexion = conectar();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $campo = $_POST['campo'];
    $valor = $_POST['valor'];

    $resultado = ver_citas($conexion,$campo,$valor);

    if ($resultado) {
        // Crear un array para almacenar los resultados
        $citas = [];
    
        // Recorrer los resultados y almacenarlos en el array
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $citas[] = $fila;
        }
    
        // Convertir el array a formato JSON
        echo json_encode(['success' => true, 'resultado' => $citas]);
    } else {
        // Si hubo un error en la consulta, mostrar el error
        echo json_encode(["error" => "No se pudieron obtener las citas."]);
    }
}

// Cerrar la conexión
mysqli_close($conexion);
?>

