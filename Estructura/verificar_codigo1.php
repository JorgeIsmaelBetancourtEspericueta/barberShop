<?php
session_start();
include('includes/utilerias.php');

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    redireccionar('Acceso prohibido', 'login.php');
    return;
}

$codigoIngresado = trim($_POST['code']);
$email = $_SESSION['email_recuperacion'];

if ($codigoIngresado == '' || !isset($email)) {
    redireccionar('Información no válida', 'recuperarContra.php');
    return;
}

$conexion = conectar();

if (!$conexion) {
    redireccionar('Error en la conexión', 'recuperarContra.php');
    return;
}

// Comprobar si el código de verificación es correcto
$stmt = $conexion->prepare("SELECT * FROM codigos WHERE email = ? AND codigo = ?");
$stmt->bind_param("ss", $email, $codigoIngresado);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Código válido: eliminar el código de la base de datos
    $deleteStmt = $conexion->prepare("DELETE FROM codigos WHERE email = ?");
    $deleteStmt->bind_param("s", $email);
    $deleteStmt->execute();

    // Redirige al usuario a la página de nueva_contraseña.php
    header("Location: nueva_contraseña.php");
    exit();
} else {
    // Código incorrecto
    redireccionar('Código incorrecto, inténtalo de nuevo.', 'recuperarContra.php');
}

$stmt->close();
$conexion->close();
?>
