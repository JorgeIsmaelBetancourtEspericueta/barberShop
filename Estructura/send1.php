<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';

include('includes/utilerias.php');

$mail = new PHPMailer(true);

if (empty($_POST)) {
    redireccionar('Acceso prohibido', 'index.php');
    return;
}

$email = validar($_POST['email']);

if ($email == '') {
    redireccionar('Información no válida', 'login.php');
    return;
}

$conexion = conectar();

if (!$conexion) {
    redireccionar('Error en la conexión', 'login.php');
    return;
}

// Verificar si el correo está registrado en la tabla de usuarios
$stmt = $conexion->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Si no se encuentra el correo, redirige con un mensaje de error
    redireccionar('El correo electrónico no está registrado', 'recuperarContra.php');
    return;
}

// Generar un código aleatorio de verificación
$codigo = rand(100000, 999999);

try {
    // Preparar la consulta de inserción o actualización
    // Aquí se usa ON DUPLICATE KEY UPDATE para actualizar el código si ya existe un registro con el mismo email
    $insertStmt = $conexion->prepare("INSERT INTO codigos (codigo, email) VALUES (?, ?) ON DUPLICATE KEY UPDATE codigo = ?");
    $insertStmt->bind_param("iss", $codigo, $email, $codigo);
    $insertStmt->execute();

    // Guardar el correo en la sesión para usarlo en la verificación
    $_SESSION['email_recuperacion'] = $email;

    // Configuración del servidor SMTP para enviar el correo
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'palabarbershop@gmail.com'; // Tu correo de Gmail
    $mail->Password = 'wizmgqoeovpmwvcm'; // Tu contraseña de aplicación de Gmail
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    // Configuración del remitente y destinatario
    $mail->setFrom('palabarbershop@gmail.com', 'Soporte de Recuperación');
    $mail->addAddress($email);

    // Configuración del contenido del correo
    $mail->AddEmbeddedImage('../Imagenes/logo.png', 'logo_empresa');
    $mail->isHTML(true);
    $mail->Subject = 'Recuperación de Contraseña - Código de Verificación';
    $mail->Body = "
        <div style='background-color: #f4f4f4; padding: 20px; font-family: Arial, sans-serif;'>
            <h2 style='color: #333;'>Recuperación de Contraseña</h2>
            <p style='font-size: 16px; color: #555;'>
                Hemos recibido una solicitud para recuperar la contraseña de tu cuenta. 
                Por favor, utiliza el siguiente código de verificación para completar el proceso de recuperación:
                <span style='color: #e74c3c; font-weight: bold;'>$codigo</span>
            </p>
            <div style='text-align: center;'>
                <img src='cid:logo_empresa' alt='Logo de la Empresa' style='width: 150px;'>
            </div>
            <p style='font-size: 14px; color: #999;'>
                Si no solicitaste la recuperación de tu contraseña, ignora este mensaje o contacta a soporte.
            </p>
        </div>
    ";

    // Enviar el correo electrónico
    $mail->send();

    // Establece una variable de sesión para activar la vista de "Ingresar el Código" en recuperarContra.php
    $_SESSION['mostrar_verificar_codigo'] = true;

    // Redirige de nuevo a recuperarContra.php
    header("Location: recuperarContra.php");
    exit();
} catch (Exception $e) {
    echo "<script>alert('El correo no pudo ser enviado. Error: {$mail->ErrorInfo}'); window.location.href = 'recuperarContra.php';</script>";
}

$stmt->close();
$insertStmt->close();
$conexion->close();
?>
