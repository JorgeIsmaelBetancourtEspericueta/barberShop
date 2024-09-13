<?php
    function redireccionar($mensaje, $dir){
        echo '
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Redirección</title>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        </head>
        <body>
            <script type="text/javascript">
                Swal.fire({
                    title: "Mensaje",
                    text: "'.$mensaje.'",
                    icon: "info",
                    confirmButtonColor: "#4CAF50",
                    confirmButtonText: "OK"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "'.$dir.'";
                    }
                });
            </script>
        </body>
        </html>';
    }

    function redireccionar2($dir){
        echo '
            <script type="text/javascript">
                window.location.href = "'.$dir.'";
            </script>
        ';
    }
    

    function validar($texto){
        $texto = trim($texto);
        $texto = stripslashes($texto);
        $texto = htmlspecialchars($texto);
        return $texto;
    }

    function conectar(){
        DEFINE('SERVIDOR','localhost');
        DEFINE('USUARIO','root');
        DEFINE('PASSWORD','28febrero');
        DEFINE('BD','barberia');

        $resultado = mysqli_connect(SERVIDOR,USUARIO,PASSWORD,BD);

        return $resultado;

    }

    function ver_citas($fecha, $conexion) {
        $sql = "
        SELECT 
            citas.idCita,  /* Agregar ID de la cita */
            citas.fecha, 
            citas.hora, 
            citas.servicio, 
            usuarios.nombre, 
            barbero.nombre AS nombreBarbero
        FROM 
            citas
        INNER JOIN 
            barbero ON citas.idBarbero = barbero.idBarbero
        INNER JOIN 
            usuarios ON citas.idUsuario = usuarios.idUsuario
        WHERE 
            citas.fecha = '$fecha' OR '$fecha' = 'all';
        ";
    
        $resultado = mysqli_query($conexion, $sql);
    
        if (!$resultado) {
            // Mostrar el error de la consulta SQL
            echo "<tr><td colspan='6'>Error en la consulta: " . mysqli_error($conexion) . "</td></tr>";
            return;
        }
    
        if (mysqli_num_rows($resultado) > 0) {
            while ($renglon = mysqli_fetch_assoc($resultado)) {
                $idCita = $renglon['idCita'];
                $hora = $renglon['hora'];
                $servicio = $renglon['servicio'];
                $nombreUsuario = $renglon['nombre'];
                $nombreBarbero = $renglon['nombreBarbero'];
    
                echo
                "<tr>
                    <td>{$renglon['fecha']}</td>
                    <td>$hora</td>
                    <td>$servicio</td>
                    <td>$nombreUsuario</td>
                    <td>$nombreBarbero</td>
                    <td><button onclick=\"cancelarCita($idCita)\" class=\"btn btn-danger btn-sm\">Cancelar</button></td>
                </tr>"; 
            }
        } else {
            echo "<tr><td colspan='6'>No se encontraron citas para la fecha especificada.</td></tr>";
        }
    }
?>
