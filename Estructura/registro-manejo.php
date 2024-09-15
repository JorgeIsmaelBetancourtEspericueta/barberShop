<?php
include('includes/utilerias.php');

if (empty($_POST)) {
    redireccionar('Prohibido', 'index.php');
    return;
}

// Obtener los valores ingresados por el usuario
$nombre = validar($_POST['nombre']);
$codigo = validar($_POST['codigo']);
$contrasenia = validar($_POST['password']);
$confirmar = validar($_POST['confirmar']);

// Verificar si alguno de los campos está vacío
if ($nombre == '' || $codigo == '' || $contrasenia == '' || $confirmar == '') {
    redireccionar('Llene todos los campos', 'registro.php');
    return;
}

// Verificar que las contraseñas coincidan
if ($contrasenia !== $confirmar) {
    redireccionar('Las contraseñas no coinciden', 'registro.php');
    return;
}

// Conectar a la base de datos
$conexion = conectar();

if (!$conexion) {
    redireccionar('Error en la conexión', 'registro.php');
    return;
}




// Verificar si el código es válido y obtener el email correspondiente
$sql_codigo = "SELECT email FROM codigos WHERE codigo = '$codigo'";
$resultado_codigo = mysqli_query($conexion, $sql_codigo);

if (mysqli_num_rows($resultado_codigo) == 0) {
    redireccionar('El código es incorrecto', 'registro.php');
    mysqli_close($conexion);
    return;
}

// Obtener el email del resultado de la consulta
$fila = mysqli_fetch_assoc($resultado_codigo);
$email = $fila['email'];

// Verificar si el correo ya está registrado
$sql = "SELECT idUsuario FROM usuarios WHERE email = '$email'";
$resultado = mysqli_query($conexion, $sql);

if (mysqli_num_rows($resultado) > 0) {
    redireccionar('El correo ya está registrado', 'login.php');
    mysqli_close($conexion);
    return;
}

// Verificar si el nombre ya está registrado en la tabla 'usuarios'
$sql_usuario = "SELECT idUsuario FROM usuarios WHERE nombre = '$nombre'";
$resultado_usuario = mysqli_query($conexion, $sql_usuario);

if (mysqli_num_rows($resultado_usuario) > 0) {
    redireccionar('El nombre ya está registrado', 'registro.php');
    mysqli_close($conexion);
    return;
}

// Encriptar la contraseña
$hashed_password = password_hash($contrasenia, PASSWORD_DEFAULT);

// Insertar los datos en la tabla 'usuarios'
$sql_insert = "INSERT INTO usuarios (nombre, password, email) VALUES ('$nombre', '$hashed_password', '$email')";

$resultado_insert = mysqli_query($conexion, $sql_insert);

if ($resultado_insert) {
    // Borrar todos los registros de la tabla 'codigos'
    $sql_borrar_codigos = "DELETE FROM codigos";
    mysqli_query($conexion, $sql_borrar_codigos);
    redireccionar('Datos guardados exitosamente', 'login.php');
} else {
    redireccionar('Error: ' . mysqli_error($conexion), 'registro.php');
}

// Cerrar la conexión
mysqli_close($conexion);
?>