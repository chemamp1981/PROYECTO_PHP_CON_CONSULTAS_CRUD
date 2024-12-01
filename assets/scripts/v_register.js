//Validación del formulario de registro

const nombre = document.getElementById("fname");
const surname = document.getElementById("fsurname");
const email = document.getElementById("femail");
const phone = document.getElementById("fphone");
const birthday = document.getElementById("fbirthday");
const address = document.getElementById("faddress");
const gender = document.getElementById("fgender");
const user = document.getElementById("fuser");
const password = document.getElementById("fpassword");
const btn = document.getElementById("enviar");


let valida ={
    nombre: false,
    surname: false,
    email: false,
    phone: false,
    birthday: false,
    address: false,
    gender: false,
    user: false,
    password: false,
    myTerms: false
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

// validar nombre
nombre.addEventListener("blur",()=>{
    let name_re = /^[a-zA-Z ]{2,45}$/;

    if(nombre.value == ""|| nombre.value == null){
        valida.nombre = false;
        setErrorFor(nombre,"No se puede dejar el nombre vacio.");
    }else{
        if(!name_re.exec(nombre.value)){
            valida.nombre = false;
            setErrorFor(nombre, "El nombre tiene que tener entre 2 y 45 letras.");
        }else{
            valida.nombre = true;
            setSuccesFor(nombre);
        }
    }
});

// validar apellidos
surname.addEventListener("blur",()=>{
    let surname_re = /^[a-zA-Z ]{2,45}$/;

    if(surname.value == ""|| surname.value == null){
        valida.surname = false;
        setErrorFor(surname,"No se pueden dejar los apellidos vacios.");
    }else{
        if(!surname_re.exec(surname.value)){
            valida.surname = false;
            setErrorFor(surname, "El nombre tiene que tener entre 2 y 45 letras.");
        }else{
            valida.surname = true;
            setSuccesFor(surname);
        }
    }
});

// validar email
email.addEventListener("blur",()=>{
    let email_re = /^[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*@[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,5}$/;

    if(email.value == ""|| email.value == null){
        valida.email = false;
        setErrorFor(email,"No se puede dejar el correo vacio.");
    }else{
        if(!email_re.exec(email.value)){
            valida.email = false;
            setErrorFor(email, "El correo tiene que tener formato correcto.");
        }else{
            valida.email = true;
            setSuccesFor(email);
        }
    }
});

// validar telefono
phone.addEventListener("blur",()=>{
    let phone_re =/^(\+|00)([0-9]{2,3})+([ ])*(6|7)([ ])*(\d[ ]*){8}$/;//Expresión regular de numeros teléfonos mobiles internacionales.

    if(phone.value == ""|| phone.value == null){
        valida.phone = false;
        setErrorFor(phone,"No se puede dejar el teléfono vacio.");
    }else{
        if(!phone_re.exec(phone.value)){
            valida.phone = false;
            setErrorFor(phone, "El formato del teléfono no es válido, debe comenzar con (+) o (00) con el prefijo del país, seguidamente solo acepta el número (6) 0 (7) y después puede contener espacios y como maximo 8 digitos.");
        }else{
            valida.phone = true;
            setSuccesFor(phone);
        }
    }
});

// validar date
birthday.addEventListener("blur",()=>{
    let birthday_re = /^\d{4}-\d{2}-\d{2}$/;

    if(birthday.value == ""|| birthday.value == null){
        valida.birthday = false;
        setErrorFor(birthday,"No se puede dejar la fecha vacia.");
    }else{
        if(!birthday_re.exec(birthday.value)){
            valida.birthday = false;
            setErrorFor(birthday, "El formato de fecha no es correcto.");
        }else{
            valida.birthday = true;
            setSuccesFor(birthday);
        }
    }
});

// validar Domicilio
address.addEventListener("blur",()=>{
    let address_re = /^[a-zA-Z0-9 ]+$/;

    if(address.value == ""|| address.value == null){
        valida.address = false;
        setErrorFor(address,"No se puede dejar el domicilio vacio.");
    }else{
        if(!address_re.exec(address.value)){
            valida.address = false;
            setErrorFor(address, "El formato del domicilio no es correcto.");
        }else{
            valida.address = true;
            setSuccesFor(address);
        }
    }
});

// validar Genero
gender.addEventListener("blur",()=>{
    let gender_re = /^[a-zA-Z ]{2,45}$/;

    if(gender.value == ""|| gender.value == null){
        valida.gender = false;
        setErrorFor(gender,"No se puede dejar el genero vacio.");
    }else{
        if(!gender_re.exec(gender.value)){
            valida.gender = false;
            setErrorFor(gender, "El genero debe de contener entre 2 y 45 letras.");
        }else{
            valida.gender = true;
            setSuccesFor(gender);
        }
    }
});

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



btn.addEventListener("click", ()=>{
    const myTerms = document.getElementById("fterms");
     if(!myTerms.checked){
            valida.myTerms = false;
            setErrorFor(myTerms, "Tiene que aceptar las condiciones de privaciadad.");
            }else{
                valida.myTerms = true;
                setSuccesFor(myTerms);
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
