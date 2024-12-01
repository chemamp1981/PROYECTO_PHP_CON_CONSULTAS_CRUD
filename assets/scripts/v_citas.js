//Validación del formulario del perfil

const appoimentDate = document.getElementById("fappoimentdate");
const text = document.getElementById("freason");

const btn = document.getElementById("crear");


let valida ={
    appoimentDate: false,
    text: false
}

// Mensaje de error
function setErrorFor(input, message) {
    // recibe como parametro input y mensaje
    const register = input.parentElement;
    register.classList.add('input_error');
    register.classList.remove('input_success');
    const small = register.querySelector("small");
    small.innerText = message;
}

// si todo esta correcto
function setSuccesFor(input) {
    const register = input.parentElement;
    register.classList.add('input_success');
    register.classList.remove('input_error');
}


// validar date
appoimentDate.addEventListener("blur",()=>{
    let appoimentDate_re = /^\d{4}-\d{2}-\d{2}$/;

    if(appoimentDate.value == ""|| appoimentDate.value == null){
        valida.appoimentDate = false;
        setErrorFor(appoimentDate,"No se puede dejar la fecha vacia.");
    }else{
        if(!appoimentDate_re.exec(appoimentDate.value)){
            valida.appoimentDate = false;
            setErrorFor(appoimentDate, "El formato de fecha no es correcto.");
        }else{
            valida.appoimentDate = true;
            setSuccesFor(appoimentDate);
        }
    }
});

// validar texto
text.addEventListener("blur",()=>{
    let text_re = /^[^$%&|<>#]{4,150}$/;

    if(text.value == ""|| text.value == null){
        valida.text = false;
        setErrorFor(text,"No se puede dejar el campo del texto vacio.");
    }else{
        if(!text_re.exec(text.value)){
            valida.text = false;
            setErrorFor(text, "El texto no permite los siguientes caracteres(^$%&|<>#).");
        }else{
            valida.text = true;
            setSuccesFor(text);
        }
    }
});



btn.addEventListener("submit",(data)=>{
    data.preventDefault();

    let errorV = false;

    for(const property in valida){
        if(valida[property] == false){
            errorV = true;
        }
    }
   if(!errorV){
    btn.submit();
   }

    
});
