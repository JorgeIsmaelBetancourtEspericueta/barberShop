<?php
    include('includes/utilerias.php');

    if(empty($_POST)){
        redireccionar('Prohibido','index.php');
        return;
    }

    $celular = validar($_POST['celular']);
    $contrasenia = validar($_POST['password']);
    $confirmar = validar($_POST['confirmar']);

    if($celular == '' ||  $contrasenia == '' || $confirmar == ''){
        redireccionar('Información no válida','recuperarContra.php');
        return;
    }

    // Verificar que la contraseña y la confirmación coincidan
    if($contrasenia !== $confirmar){
        redireccionar('Las contraseñas no coinciden', 'recuperarContra.php');
        return;
    }

    $conexion = conectar();

    if(!$conexion){
        redireccionar('Error en la conexión','registro.php');
        return;
    } 

   // Verificar si el teléfono ya está registrado
    $sql = "SELECT idUsuario FROM usuarios WHERE telefono = '$celular'";
    $resultado = mysqli_query($conexion, $sql);

    if(mysqli_num_rows($resultado) == 0) {
        redireccionar("No existe una cuenta con el número $celular", 'registro.php');
        mysqli_close($conexion);
        return;
    }

     // Encriptar la contraseña
     $hashed_password = password_hash($contrasenia, PASSWORD_DEFAULT);

     $sql = "UPDATE USUARIOS set password = '$hashed_password' where telefono = '$celular';";
      
 
     $resultado = mysqli_query($conexion,$sql);
 
     if($resultado){
         redireccionar('Datos guardados exitosamente', 'login.php');
     }else{
         redireccionar('Error: '.mysqli_error($conexion), 'recuperarContra.php');
     }

   
?>