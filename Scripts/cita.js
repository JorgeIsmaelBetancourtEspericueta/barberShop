function filtrar() {
    var fecha = document.getElementById('filtroFecha').value;
    window.location.href = '?fecha=' + fecha;
}


function cancelarCita(idCita) {
    if (confirm('¿Estás seguro de que deseas cancelar esta cita?')) {
        fetch('cancelar_cita.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                'idCita': idCita
            })
        })
        .then(response => response.text())
        .then(data => {
            window.location.reload(); // Recargar la página para actualizar la lista de citas
        })
        .catch(error => console.error('Error al cancelar la cita:', error));
    }
}