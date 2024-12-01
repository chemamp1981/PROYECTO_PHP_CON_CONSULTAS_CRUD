
//Validación del formulario del perfil


const title = document.getElementById("ftitle");
const file = document.getElementById("fimage");
const text = document.getElementById("ftext");
const date = document.getElementById("fdate");
const btn = document.getElementById("crear");

let valida ={
    title: false,
    file: false,
    text: false,
    date: false
    
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

// validar titulo
title.addEventListener("blur",()=>{
    let title_re = /^[^$%&|<>#]{3,255}$/;

    if(title.value == ""|| title.value == null){
        valida.title = false;
        setErrorFor(title,"No se puede dejar el campo del texto vacio.");
    }else{
        if(!title_re.exec(title.value)){
            valida.title = false;
            setErrorFor(title, "El texto no permite los siguientes caracteres(^$%&|<>#).");
        }else{
            valida.title = true;
            setSuccesFor(title);
        }
    }
});

// validar archivo

file.addEventListener('change', () => {
    let file_re = /(.jpeg|.jpg|.png)$/i;
   
    if(file.value == ""){
       
        setErrorFor(file, "Seleccione un archivo");
        valida.file = false;
    }else{
        if(!file_re.exec(file.value)){
           
            setErrorFor(file,'Porfavor suba archivos con una extensión vlálida: jpeg, jpg, png');
            valida.file = false;
        }else{
            valida.file = true;
            setSuccesFor(file)
        }
    }
});

// validar texto
text.addEventListener("blur",()=>{
    let text_re = /^[^$%&|<>#]{150,}$/;

    if(text.value == ""|| text.value == null){
        valida.text = false;
        setErrorFor(text,"No se puede dejar el campo del texto vacio.");
    }else{
        if(!text_re.exec(text.value)){
            valida.text = false;
            setErrorFor(text,"El texto de la noticia como minimo 150 caracteres y no debe de contener ningún de estos caracteres (^$%&|<>#).");
        }else{
            valida.text = true;
            setSuccesFor(text);
        }
    }
});

// validar date
date.addEventListener("blur",()=>{
    let date_re = /^\d{4}-\d{2}-\d{2}$/;

    if(date.value == ""|| date.value == null){
        valida.date = false;
        setErrorFor(date,"No se puede dejar la fecha vacia.");
    }else{
        if(!date_re.exec(date.value)){
            valida.date = false;
            setErrorFor(date, "El formato de fecha no es correcto.");
        }else{
            valida.date = true;
            setSuccesFor(date);
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
