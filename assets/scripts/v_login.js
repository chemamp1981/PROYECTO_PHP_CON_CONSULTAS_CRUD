//Validación del formulario de login

const user = document.getElementById("fuser");
const password = document.getElementById("fpassword");
const btn = document.getElementById("enviar");


let valida ={
    user: false,
    password: false
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


// validar Usuario
user.addEventListener("blur",()=>{
    let user_re = /^[a-z0-9_-]{3,16}$/;

    if(user.value == ""|| user.value == null){
        valida.user = false;
        setErrorFor(user,"No se puede dejar el usuario vacio.");
    }else{
        if(!user_re.exec(user.value)){
            valida.user = false;
            setErrorFor(user, "El usuario debe de contener entre 3 y 16 letras incluido los siguientes caracteres (_-).");
        }else{
            valida.user = true;
            setSuccesFor(user);
        }
    }
});

// validar password
password.addEventListener("blur",()=>{
    let password_re = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[$@!%*?&#.,_-§])[a-zA-Z\d$@!%*?&#.,_-§]{8,}$/;

    if(password.value == ""|| password.value == null){
        valida.password = false;
        setErrorFor(password,"No se puede dejar el password vacio.");
    }else{
        if(!password_re.exec(password.value)){
            valida.password = false;
            setErrorFor(password, "El password deberá contener minimo 8 caracteres e incluir como minimo una mayuscula, una minuscula, un numero y almenos uno de estos caracteres especiales ($@!%*?&#.,_-§).");
        }else{
            valida.password = true;
            setSuccesFor(password);
        }
    }
});

// Enviar el formulario
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
