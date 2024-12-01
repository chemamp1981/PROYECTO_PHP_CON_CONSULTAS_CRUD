
const mensaje = document.getElementById('msg')
const diferencia = document.getElementById('diferencia');
const ocultarEditar = document.getElementById('edCita');
const ocultarBorrar = document.getElementById('borrarCita');


// Mensaje de error
function setErrorFor(input, elemento, msg) {
    // recibe como parametro mensaje
   
    const register = input.parentElement;
    const padre = elemento.parentElement; 
    register.classList.add('input_error');
    register.classList.remove('input_success');
    padre.classList.add('msg_error');
    padre.classList.remove('msg_success');
    const small = mensaje;
    small.textContent = msg;
}


// si todo esta correcto
function setSuccesFor(input, elemento) {
    
    const register = input.parentElement;
    const padre = elemento.parentElement; 
    register.classList.add('input_success');
    register.classList.remove('input_error');
    padre.classList.add('msg_success');
    padre.classList.remove('msg_error');

   
}


function diferenciaFecha(){

    if(diferencia.textContent < 0){
   
        ocultarEditar.style.visibility = "hidden";
        ocultarBorrar.style.visibility = "hidden";
        setErrorFor(ocultarEditar, mensaje, "La cita ha caducado");

        

    }else{

        ocultarEditar.style.visibility = "visible";
        ocultarBorrar.style.visibility = "visible";
        setSuccesFor(ocultarBorrar, mensaje);
    }
}

diferenciaFecha();