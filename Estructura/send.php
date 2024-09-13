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
    $mail->Username = 'jismaelbetan@gmail.com';
    $mail->Password = 'ivoejiegttdwsdgy';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    // Remitente y destinatario
    $mail->setFrom('jismaelbetan@gmail.com');
    $mail->addAddress($_POST["email"]);

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Tu codigo de validacion';
    $mail->Body = "Tu código de validación es: <b>$codigo</b>";

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
            document.location.href = 'registro.php';
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