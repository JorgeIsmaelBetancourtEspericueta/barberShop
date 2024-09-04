 // Obtener la fecha actual
 const today = new Date();
 const day = String(today.getDate()).padStart(2, '0');
 const month = String(today.getMonth() + 1).padStart(2, '0'); // Los meses empiezan desde 0
 const year = today.getFullYear();
 
 // Formatear la fecha en YYYY-MM-DD
 const currentDate = `${year}-${month}-${day}`;
 
 // Asignar la fecha mínima al input
 document.getElementById('fecha').setAttribute('min', currentDate);