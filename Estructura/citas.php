<?php
session_start();
// Verifica que el idUsuario esté en la sesión

if (!isset($_SESSION['administrador']) && !isset($_SESSION['usuario'])) {
    die("Error: No hay ningún usuario autenticado.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pa' La Barber Shop - Citas</title>
    <link rel="stylesheet" href="../Diseno/estiloCitas.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Ga+Maamli&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="../scripts/cita.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            
        <a class="navbar-brand me-auto" href="#"><img src="../Imagenes/logo.png" alt="Logo de Barber Shop"></a></a>                
                <!-- Enlace de login, aparece antes de la hamburguesa en pantallas pequeñas -->
                <?php
                    echo '<a href="salir.php"" class="login-button order-lg-2">Salir</a>';
                ?>

                <!-- Botón de hamburguesa -->
                <button class="navbar-toggler order-lg-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                    aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar"
                    aria-labelledby="offcanvasNavbarLabel">
                    <div class="offcanvas-header">
                    <a class="navbar-brand" href="#">
                 <img src="../Imagenes/logo.png" alt="Logo de Barber Shop" style="height: 3rem;">
                    </a>
                 <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-center flex-grow-1 pe-3">
                            <li class="nav-item">
                                <a class="nav-link mx-lg-2" aria-current="page" href="cortes.php">Cortes</a>
                            </li>            
                            <li class="nav-item">
                                <a class="nav-link mx-lg-2" href="usuario.php">Usuarios</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mx-lg-2 active" href="citas.php">Citas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mx-lg-2" href="agendar.php">Agendar</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mx-lg-2" href="descansos.php">Descansos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mx-lg-2" href="horarios.php">Horarios</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mx-lg-2" href="barberos.php">Barberos</a>
                            </li>
                        </ul>
                    </div>
                </div>
        </div>
    </nav>

    <main>
        <div class="alto"></div>
        <div class="bg-image h-100" style="background-color: #c1ed63;">
            <div class="mask d-flex align-items-center h-100">
            <div class="container">
                <div class="row justify-content-center">
                <div class="col-12">
                    <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive table-scroll" data-mdb-perfect-scrollbar="true" style="position: relative; height: 700px">
                        <table class="table table-striped mb-0" id="tablaCitas">
                            <thead style="background-color: #002d72;">
                            <tr>
                                <th colspan="2" style="text-align: center; vertical-align: middle;">
                                    <label for="filtroFecha" style="color: white;">Filtrar la información</label>
                                </th>
                                <th colspan="4" class="text-center">
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        <form action="" method="post" id="formFiltro" class="d-flex justify-content-center align-items-center gap-2">
                                            <!-- Selección de campo -->
                                            <div id="opcion" class="d-flex align-items-center gap-2">
                                                <select name="campo" id="campo" class="form-select form-select-sm bg-light text-success w-auto" style="min-width: 200px;" onchange="mostrarInputOpcion(this)">
                                                    <option value="">Seleccione un campo</option>
                                                    <option value="fecha">Fecha</option>
                                                    <option value="hora">Hora</option>
                                                    <option value="servicio">Servicio</option>
                                                    <option value="Cliente">Cliente</option>
                                                    <option value="Barbero">Barbero</option>
                                                </select>
                                            </div>

                                            <!-- Input manual (visible solo si es necesario) -->
                                            <div id="inputContainer" class="d-flex align-items-center gap-2">
                                                <input type="text" id="inputManual" name="inputManual" class="form-control" placeholder="Escriba su opción" style="height: auto; font-size: 13px; display: none;">
                                            </div>

                                            <!-- Filtro de fecha (visible solo cuando se selecciona 'fecha') -->
                                            <div id="fecha" class="d-flex align-items-center gap-2">
                                                <select name="fecha" id="filtroFecha" class="form-select form-select-sm bg-light text-success w-auto" style="min-width: 200px; display: none;">
                                                    <option value="">Seleccione una opción</option>
                                                    <option value="<?php echo date('Y-m-d'); ?>">Citas de hoy</option>
                                                    <option value="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">Citas de mañana</option>
                                                    <option value="all">Todas</option>
                                                    <option value="custom">Seleccionar</option>
                                                </select>
                                            </div>

                                            <!-- Input de fecha personalizado (visible solo si se selecciona 'custom' en el filtro de fecha) -->
                                            <input type="date" id="fechaPersonalizada" name="fechaPersonalizada" class="form-control w-auto" style="display: none; font-size: 13px;">
                                        </form>

                                        <script>
                                            function mostrarInputOpcion(select) {
                                                var inputContainer = document.getElementById('inputManual');
                                                var fechaSelect = document.getElementById('filtroFecha');
                                                var fechaPersonalizada = document.getElementById('fechaPersonalizada');

                                                // Ocultar todos los campos por defecto
                                                inputContainer.style.display = 'none';
                                                fechaSelect.style.display = 'none';
                                                fechaPersonalizada.style.display = 'none';

                                                // Mostrar el campo de texto si la opción es Cliente, Barbero, o Servicio
                                                if (select.value == 'hora' || select.value === 'Cliente' || select.value === 'Barbero' || select.value === 'servicio') {
                                                    inputContainer.style.display = 'flex';
                                                }
                                                
                                                // Mostrar el campo de fecha si la opción es 'fecha'
                                                if (select.value === 'fecha') {
                                                    fechaSelect.style.display = 'flex';
                                                }

                                                // Control de la selección de fecha personalizada
                                                document.getElementById('filtroFecha').addEventListener('change', function() {
                                                    if (this.value === 'custom') {
                                                        fechaPersonalizada.style.display = 'block';
                                                    } else {
                                                        fechaPersonalizada.style.display = 'none';
                                                    }
                                                });
                                            }

                                            function enviarFormulario() {
                                                const formFiltroData = new FormData(document.getElementById('formFiltro'));

                                                fetch('./includes/actuVista.php', {
                                                    method: 'POST',
                                                    body: formFiltroData
                                                })
                                                .then(response => response.text())
                                                .then(data => {
                                                    console.log('Formulario enviado:', data);
                                                    actualizarCitas(); // Refrescar las citas después de enviar
                                                })
                                                .catch(error => console.error('Error:', error));
                                            }

                                            $(document).ready(function() {
                                                $('#botonBuscar').click(function() {
                                                    var campoR = $(campo).val();
                                                    var valorI = $(inputManual).val();
                                                    var fechaR = $(filtroFecha).val();
                                                    var perso = $(fechaPersonalizada).val();
                                                    
                                                    var valorR;

                                                    if(campoR == 'fecha'){
                                                        valorI = '';
                                                        if(fechaR == 'custom'){
                                                            fechaR = '';
                                                        }else{
                                                            perso = '';
                                                        }
                                                    }else{
                                                        fechaR = '';
                                                        perso = '';
                                                    }

                                                    if(valorI != ''){
                                                        valorR = valorI;
                                                    }else{
                                                        if(fechaR != ''){
                                                            valorR = fechaR;
                                                        }else {
                                                            if(perso != ''){
                                                                valorR = perso;
                                                            }
                                                        }
                                                    }
                                                    
                                                    console.log("campo: "+campoR);
                                                    console.log("input manual: "+valorI);
                                                    console.log("fecha: "+fechaR);
                                                    console.log("perso: "+perso);
                                                    console.log("****** Valor *****: "+valorR);

                                                    // Realizar una llamada AJAX a un script PHP
                                                    
                                                    $.ajax({
                                                        url: './actuVista.php',
                                                        type: 'POST',
                                                        data: { campo: campoR, valor: valorR },
                                                        success: function(response) {
                                                            console.log(response);
                                                            if (response.success) {
                                                                console.log('resultado:', response.resultado);
                                                                actualizarCitas(response.resultado);
                                                            } else {
                                                                console.error('Error al obtener las horas:', response.message || 'Mensaje no definido');
                                                            }
                                                        },
                                                        error: function(xhr, status, error) {
                                                            console.error('Error en la llamada AJAX:', error);
                                                        }
                                                    });
                                                });
                                            });

                                        </script>
                                        <button class="btn btn-info btn-sm" id="botonBuscar">Buscar</button>
                                    </div>
                                </th>
                            </tr>

                            <tr class="encabezados">
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Servicio</th>
                                <th>Cliente</th>
                                <th>Barbero</th>
                                <th>Cancelar</th>
                             </tr>
                            </thead>
                            <tbody id="citasBody">
                                
                            </tbody>
                        </table>
                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                        <script>
                            function actualizarCitas(data) {
                                const tbody = document.querySelector('#tablaCitas tbody');
                                
                                // Limpiar el contenido del tbody antes de agregar nuevas filas
                                tbody.innerHTML = '';

                                if (data.length > 0) {
                                    data.forEach(renglon => {
                                        const fila = document.createElement('tr');

                                        fila.innerHTML = `
                                            <td>${renglon.fecha}</td>
                                            <td>${renglon.hora}</td>
                                            <td>${renglon.servicio}</td>
                                            <td>${renglon.nombre}</td>
                                            <td>${renglon.nombreBarbero}</td>
                                            <td><button onclick="cancelarCita(${renglon.idCita})" class="btn btn-danger btn-sm">Cancelar</button></td>
                                        `;

                                        // Añadir la fila al cuerpo de la tabla
                                        tbody.appendChild(fila);
                                    });
                                } else {
                                    // Si no hay citas, mostrar un mensaje
                                    const fila = document.createElement('tr');
                                    fila.innerHTML = `<td colspan="6">No se encontraron citas con la información especificada.</td>`;
                                    tbody.appendChild(fila);
                                }
                            }
                        </script>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; Todos los derechos reservados</p>
    </footer>

</body>
</html>

