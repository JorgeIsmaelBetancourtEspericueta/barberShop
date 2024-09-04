
const nombre = document.getElementById('usuario');
const contrasenia = document.getElementById('password')

nombre.addEventListener('blur',()=>{
    if(nombre.value == ""){
        nombre.style.border = "3px solid red";
    }else{
        nombre.style.border = "3px solid black";
    }
})

contrasenia.addEventListener('blur',()=>{
    if(contrasenia.value == ""){
        contrasenia.style.border = "3px solid red";
    }else{
        contrasenia.style.border = "3px solid black";
    }
})