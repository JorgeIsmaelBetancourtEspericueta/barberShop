<?php

include('includes/utilerias.php');
session_start();  // Asegúrate de que la sesión se inicia aquí

if(empty($_POST)){
    redireccionar('Prohibido', 'index.php');
    return;
}

$usuario = $_POST['usuario'];
$password = $_POST['password'];

// Validar campos
if($usuario == '' || $password == ''){
    redireccionar('Información no válida', 'login.php');
    return;
}

$conexion = conectar();

if (!$conexion) {
    redireccionar('Error en la conexión', 'login.php');
    return;
}

// Escapar el nombre para evitar inyección SQL
$usuario_escapado = mysqli_real_escape_string($conexion, $usuario);

// Verificar si el usuario existe y obtener la contraseña
$sql = "SELECT idUsuario, password, es_admin, nombre FROM usuarios WHERE email = '$usuario_escapado'";
$resultado = mysqli_query($conexion, $sql);

// Manejar errores de consulta
if (!$resultado) {
    echo 'Error en la consulta SQL: ' . mysqli_error($conexion);
    mysqli_close($conexion);
    return;
}

// Verificar si se encontraron usuarios
if (mysqli_num_rows($resultado) > 0) {
    // Verificar la contraseña para cada usuario encontrado
    $usuario_encontrado = false;
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $hashed_password = $fila['password'];
        $idCliente = $fila['idUsuario'];
        $es_admin = $fila['es_admin'];
        $nombre = $fila['nombre'];

        // Comparar la contraseña ingresada con la almacenada
        if (password_verify($password, $hashed_password)) {
            // Redirigir basado en si el usuario es administrador o no
            if ($es_admin) {
                $_SESSION['administrador'] = $es_admin;
                redireccionar('Bienvenido administrador, ' . $usuario, 'inicioAdmon.php');
            } else {
                $_SESSION['usuario'] = $idCliente;  // Puedes almacenar más información si es necesario
                $_SESSION['nombre'] = $usuario_escapado;
                redireccionar('Bienvenido, ' . $nombre, 'index.php');
            }

            $usuario_encontrado = true;
            break;
        } else {
        }
    }

    if (!$usuario_encontrado) {
        redireccionar('Usuario no encontrado', 'login.php');
    }
} else {
    redireccionar('Datos incorrectos', 'login.php');
}

mysqli_close($conexion);
?>
