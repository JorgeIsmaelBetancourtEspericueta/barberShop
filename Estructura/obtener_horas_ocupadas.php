<?php
session_start();
include('includes/utilerias.php');

$conn = conectar();
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$horasOcupadas = [];

if (isset($_GET['fecha']) && isset($_GET['barbero'])) {
    $fecha = $conn->real_escape_string($_GET['fecha']);
    $idBarbero = $conn->real_escape_string($_GET['barbero']);

    $sql = "SELECT hora FROM citas WHERE fecha = '$fecha' AND idBarbero = '$idBarbero'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $horasOcupadas[] = $row['hora'];
        }
    }
}

echo json_encode($horasOcupadas);
$conn->close();
?>
