<?php
    include('includes/utilerias.php');

    if(empty($_POST)){
        redireccionar('Prohibido','index.php');
        return;
    }

    $nombre = validar($_POST['nombre']);
    $telefono = validar($_POST['telefono']);
    $contrasenia = validar($_POST['password']);
    $confirmar = validar($_POST['confirmar']);

    if($nombre == '' || $telefono == '' || $contrasenia == '' || $confirmar == ''){
        redireccionar('Información no válida','registro.php');
        return;
    }

    // Verificar que la contraseña y la confirmación coincidan
    if($contrasenia !== $confirmar){
        redireccionar('Las contraseñas no coinciden', 'registro.php');
        return;
    }

    $conexion = conectar();

    if(!$conexion){
        redireccionar('Error en la conexión','registro.php');
        return;
    } 

   // Verificar si el teléfono ya está registrado
    $sql = "SELECT idUsuario FROM usuarios WHERE telefono = '$telefono'";
    $resultado = mysqli_query($conexion, $sql);

    if(mysqli_num_rows($resultado) > 0) {
        redireccionar('El número de teléfono ya está registrado', 'registro.php');
        mysqli_close($conexion);
        return;
    }

    // Encriptar la contraseña
    $hashed_password = password_hash($contrasenia, PASSWORD_DEFAULT);


    $sql = "INSERT INTO usuarios (nombre, password, telefono) VALUES ('$nombre', '$hashed_password', '$telefono')";

    

    $resultado = mysqli_query($conexion,$sql);

    if($resultado){
        redireccionar('Datos guardados exitosamente', 'login.php');
    }else{
        redireccionar('Error: '.mysqli_error($conexion), 'registro.php');
    }

?>