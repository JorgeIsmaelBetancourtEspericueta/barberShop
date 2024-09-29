<?php
  // Iniciar la sesión para almacenar valores persistentes
  ob_start();
  session_start();
   $campo = '';
   $valor = '';
   
   if ($_SERVER["REQUEST_METHOD"] == "POST") {
       // Obtener los datos del formulario
       $uno = isset($_POST['campo']) ? $_POST['campo'] : '';
       if ($uno != '') {
           // Procesar los datos y guardarlos en la sesión
           echo "Formulario Campo recibido con el valor: " . htmlspecialchars($uno);
           setCampo(htmlspecialchars($uno));
       }
   
       $dos = isset($_POST['inputManual']) ? $_POST['inputManual'] : '';
       if ($dos != '') {
           echo "Formulario InputManual recibido con el valor: " . htmlspecialchars($dos);
           setValor(htmlspecialchars($dos));
       }

       $tres = isset($_POST['fecha']) ? $_POST['fecha'] : '';
       if ($tres != '') {
            echo "Formulario fitro fecha recibido con el valor: " . htmlspecialchars($tres);
            if($tres!='custom'){
                setValor(htmlspecialchars($tres));
            }
        }

        $cuatro = isset($_POST['fechaPersonalizada']) ? $_POST['fechaPersonalizada'] : '';
        if ($cuatro != '') {
            echo "*****Formulario fechaP recibido con el valor: " . htmlspecialchars($cuatro);
            setValor(htmlspecialchars($cuatro));
        }

       getCampo();
       getValor();
   }
   
   function setCampo($c) {
       if (!isset($_SESSION['campo'])) {
           $_SESSION['campo'] = $c;  // Almacenar el valor en la sesión
       }
   }
   
   function getCampo() {
       if (isset($_SESSION['campo'])) {
           echo " Este es el valor de campo desde utilerias: " . $_SESSION['campo'];
       }
   }
   
   function setValor($v) {
       if (!isset($_SESSION['valor'])) {
           $_SESSION['valor'] = $v;  // Almacenar el valor en la sesión
       }
   }
   
   function getValor() {
       if (isset($_SESSION['valor'])) {
           echo " Este es el valor de valor desde utilerias: " . $_SESSION['valor'];
       }
   }
   

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

    function ver_citas($conexion) {
        echo "Entre aqui    ";
        echo "   *Desde ver citas*    ";
        if(!isset($_SESSION['valor'])){
            $_SESSION['valor']='';
        }
        $tablaC = '';
        if($_SESSION['campo']=='Cliente'){
            $tablaC='usuarios.nombre';
        }elseif($_SESSION['campo']=='Barbero'){
            $tablaC='barbero.nombre';
        }else{
            $tablaC = 'citas.' . $_SESSION['campo'];
        }
        if(($_SESSION['campo']=='fecha' && $_SESSION['valor']=='all') || $_SESSION['valor']==''){
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
                usuarios ON citas.idUsuario = usuarios.idUsuario;";
        }else{
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
                $tablaC LIKE " . "'" . $_SESSION['valor'] . "%'" . ";";
        }
        
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
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, 
                $params["path"], $params["domain"], 
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }
?>
