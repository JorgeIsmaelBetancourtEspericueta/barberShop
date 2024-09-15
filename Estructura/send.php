<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';

include('includes/utilerias.php');


$mail = new PHPMailer(true);

if(empty($_POST)){
    redireccionar('Prohibido','index.php');
    return;
}

$email = validar($_POST['email']);


if($email == ''){
    redireccionar('Información no válida','login.php');
    return;
}


$conexion = conectar();

if(!$conexion){
    redireccionar('Error en la conexión','login.php');
    return;
} 



try {
    // Generar un código aleatorio
    $codigo = rand(100000, 999999);

    // Guardar el código en una sesión
    $_SESSION['codigo'] = $codigo;

    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'palabarbershop@gmail.com';
    $mail->Password = 'wizmgqoeovpmwvcm';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    // Remitente y destinatario
    $mail->setFrom('palabarbershop@gmail.com');
    $mail->addAddress($_POST["email"]);

    // Contenido del correo
 
    $mail->AddEmbeddedImage('../Imagenes/logo.png', 'logo_empresa');

    // Contenido del correo con HTML, estilos y la imagen embebida
    $mail->isHTML(true);
    $mail->Subject = 'Tu codigo de validacion';
    $mail->Body = "
        <div style='background-color: #f4f4f4; padding: 20px; font-family: Arial, sans-serif;'>
            
            <h2 style='color: #333;'>¡Bienvenido a nuestra plataforma!</h2>
            <p style='font-size: 16px; color: #555;'>
                Tu código de validación es: 
                <span style='color: #e74c3c; font-weight: bold;'>$codigo</span>
            </p>
            <div style='text-align: center;'>
                <img src='cid:logo_empresa' alt='Logo de la Empresa' style='width: 150px;'>
            </div>
            <p style='font-size: 14px; color: #999;'>
                Si no solicitaste este código, por favor ignora este correo.
            </p>
        </div>
    ";


    // Envío del correo
    $mail->send();
    echo "
        <script>
            alert('Correo enviado con el código de validación');
        </script>
        ";
} catch (Exception $e) {
    echo "
        <script>
            alert('El correo no pudo ser enviado. Error: {$mail->ErrorInfo}');
            document.location.href = 'login.php';
        </script>
        ";
}


$sql = "INSERT INTO codigos (codigo, email) VALUES ('$codigo', '$email')";



$resultado = mysqli_query($conexion,$sql);

if($resultado){
    echo "
    <script>
        document.location.href = 'registro.php';
    </script>
    ";
}else{
    redireccionar('Error: '.mysqli_error($conexion), 'login.php');
}

?>