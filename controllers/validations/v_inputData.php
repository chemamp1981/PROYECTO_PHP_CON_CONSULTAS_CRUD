<?php


# Declaramos como constantes las expresiones regulares que van a filtrar o comprobar los datos
define("NAME_REGEX", "/^[a-zA-ZÑñÁáÉéÍíÓóÚúÜüÈèÀàÄäÖö ]{3,50}$/");
define("SURNAME_REGEX", "/^[a-zA-ZÑñÁáÉéÍíÓóÚúÜüÈèÀàÄäÖö ]{3,70}$/");
define("EMAIL_REGEX", "/^[a-zA-Z0-9_-]+([.][a-zA-Z0-9_-]+)*@[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,5}$/");
define("PHONE_REGEX", "/^(\+|00)([0-9]{2,3})+([ ])*(6|7)([ ])*(\d[ ]*){8}$/");//Expresión regular de numeros teléfonos mobiles internacionales.
define("DATE_REGEX",  "/^\d{4}-\d{2}-\d{2}$/");
define("ADDRESS_REGEX", "/^[a-zA-Z0-9 ]+$/");
define("GENDER_REGEX", "/^[a-zA-Z ]{2,45}$/");
define("USER_REGEX", "/^[a-z0-9_-]{3,50}$/"); 
define("PASSWORD_REGEX",  "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[$@!%*?&#.,_-§])[a-zA-Z\d$@!%*?&#.,_-§]{8,}$/");
define("TEXT_REGEX", "/^[^$%&|<>#]{4,150}$/");
define("TEXTAREA_REGEX", "/^[^$%&|<>#]{150,}$/");
define("TITLE_REGEX", "/^[^$%&|<>#]{3,255}$/");
//define("TEXTAREA_REGEX", "/^[\w \-\.\:\p{L}\p{M}]{4,255}+$/i");

# Definimos la función validar_registro()
function validar_registro($name, $surname, $email, $phone, $birthday, $address, $gender, $user, $pass){
    # Declarar un array asociativo
    $errores = [];

    # Validación del nombre haciendo uso de la constante NAME_REGEX
    if(!preg_match(NAME_REGEX, $name)){
        $errores['name'] = "El nombre deberá contener entre 3 y 50 caracteres, minusculas o mayusculas y se podrá hacer uso de espacio en caso de introducir un nombre compuesto.";
    }

    # Validación del apellido haciendo uso de la constante SURNAME_REGEX
    if(!preg_match(SURNAME_REGEX, $surname)){
        $errores['surname'] = "Los apellidos deberá contener entre 3 y 70 caracteres, minusculas o mayusculas y se podrá hacer uso de espacio en caso de introducir un apellido compuesto.";
    }

    # Validación del correo eléctronico.
    if(!preg_match(EMAIL_REGEX, $email)){
        $errores['email'] = "El formato del correo electrónico no es válido. Ej. xxxxx(.-_)xxxx(.-_)@xxxxxx.(com, es, de, etc)";
    }

    # Validación del teléfono haciendo uso de la constante PHONE_REGEX
    if(!preg_match(PHONE_REGEX, $phone)){
        $errores['phone'] = "El formato del teléfono no es válido, debe comenzar con (+) o (00) con el prefijo del país, seguidamente solo acepta el número (6) 0 (7) y después puede contener espacios y como maximo 8 digitos.";
    }

    # Validación de la fecha de nacimiento haciendo uso de la constante BIRTHDAY_REGEX
    if(!preg_match(DATE_REGEX, $birthday)){
        $errores['birthday'] = "El formato de la fecha de nacimiento no es válido.";
    }

    # Validación del domicilio haciendo uso de la constante ADDRESS_REGEX
    if(!preg_match(ADDRESS_REGEX, $address)){
        $errores['address'] = "El formato del domicilio no es válido.";
    }

    # Validación del genero haciendo uso de la constante GENDER_REGEX
    if(!preg_match(GENDER_REGEX, $gender)){
        $errores['gender'] = "El genero deberá contener entre 2 y 45 caracteres y se podrá hacer uso de espacios en caso de introducir un genero compuesto.";
    }

     # Validación del genero haciendo uso de la constante GENDER_REGEX
     if(!preg_match(USER_REGEX, $user)){
        $errores['user'] = "El usuario deberá contener entre 3 y 16 caracteres y NO se podrá hacer uso de espacios, pero si de guion bajo, guion medio (_-), letras minusculas y numeros.";

    }
    # Validación de la contraseña haciendo uso de la constante PASSWORD_REGEX
    if(!preg_match(PASSWORD_REGEX, $pass)){
        $errores['password'] = "La contraseña deberá contener minimo 8 caracteres e incluir como minimo una mayuscula, una minuscula, un numero y almenos uno de estos caracteres especiales ($@!%*?&#.,_-§).";
    }

    return $errores;

}

# Definimos la función validar_login()
function validar_login($user, $pass){
    # Declarar un array asociativo
    $errores = [];

    # Validación del correo electrónico
    if(!preg_match(USER_REGEX, $user)){
        $errores['user'] = "El usuario deberá contener entre 3 y 16 caracteres y NO se podrá hacer uso de espacios, pero si de guion bajo, guion medio (_-), letras minusculas y numeros.";
    }

    # Validación de la contraseña haciendo uso de la constante CONTRASENA_REGEX
    if(!preg_match(PASSWORD_REGEX, $pass)){
        $errores['password'] = "La contraseña deberá contener minimo 8 caracteres e incluir como minimo una mayuscula, una minuscula, un numero y almenos uno de estos caracteres especiales ($@!%*?&#.,_-§).";
    }

    return $errores;
}


# Definimos la función validar_perfil()
function validar_perfil($name, $surname, $email, $phone, $birthday, $address, $gender, $user, $pass){
    # Declarar un array asociativo
    $errores = [];

    # Validación del nombre haciendo uso de la constante NAME_REGEX
    if(!preg_match(NAME_REGEX, $name)){
        $errores['name'] = "El nombre deberá contener entre 2 y 45 letras minusculas o mayusculas y se podrá hacer uso de un único espacio en caso de introducir un nombre compuesto.";
    }

    # Validación del apellido haciendo uso de la constante SURNAME_REGEX
    if(!preg_match(SURNAME_REGEX, $surname)){
        $errores['surname'] = "Los apellidos deberá contener entre 2 y 45 letras minusculas o mayusculas y se podrá hacer uso de un único espacio en caso de introducir un apellido compuesto.";
    }

    # Validación del correo eléctronico.
    if(!preg_match(EMAIL_REGEX, $email)){
        $errores['email'] = "El formato del correo electrónico no es válido.";
    }

    # Validación del teléfono haciendo uso de la constante PHONE_REGEX
    if(!preg_match(PHONE_REGEX, $phone)){
        $errores['phone'] = "El formato del teléfono no es válido, debe de empezar por (0,6,7,8,9) y contener un minimo de 9 digitos y un maxiomo de 10 digitos.";
    }

    # Validación de la fecha de nacimiento haciendo uso de la constante BIRTHDAY_REGEX
    if(!preg_match(DATE_REGEX, $birthday)){
        $errores['birthday'] = "El formato de la fecha de nacimiento no es válido.";
    }

    # Validación del domicilio haciendo uso de la constante ADDRESS_REGEX
    if(!preg_match(ADDRESS_REGEX, $address)){
        $errores['address'] = "El formato del domicilio no es válido.";
    }

    # Validación del genero haciendo uso de la constante GENDER_REGEX
    if(!preg_match(GENDER_REGEX, $gender)){
        $errores['gender'] = "El genero deberá contener entre 2 y 45 letras y se podrá hacer uso de un único espacio en caso de introducir un genero compuesto.";
    }

     # Validación del genero haciendo uso de la constante GENDER_REGEX
     if(!preg_match(USER_REGEX, $user)){
        $errores['user'] = "El usuario deberá contener entre 3 y 16 caracteres y NO se podrá hacer uso de espacios, pero si de guion bajo, guion medio (_-), letras minusculas y numeros.";

    }
    # Validación de la contraseña haciendo uso de la constante PASSWORD_REGEX
    if(!preg_match(PASSWORD_REGEX, $pass)){
        $errores['password'] = "La contraseña deberá contener minimo 8 caracteres e incluir como minimo una mayuscula, una minuscula, un numero y almenos uno de estos caracteres especiales ($@!%*?&#.,_-§).";
    }

    return $errores;
}


# Definimos la función validar_perfil_without_pass()
function validar_perfil_without_pass($name, $surname, $email, $phone, $birthday, $address, $gender, $user){
    # Declarar un array asociativo
    $errores = [];

    # Validación del nombre haciendo uso de la constante NAME_REGEX
    if(!preg_match(NAME_REGEX, $name)){
        $errores['name'] = "El nombre deberá contener entre 2 y 45 letras minusculas o mayusculas y se podrá hacer uso de un único espacio en caso de introducir un nombre compuesto.";
    }

    # Validación del apellido haciendo uso de la constante SURNAME_REGEX
    if(!preg_match(SURNAME_REGEX, $surname)){
        $errores['surname'] = "Los apellidos deberá contener entre 2 y 45 letras minusculas o mayusculas y se podrá hacer uso de un único espacio en caso de introducir un apellido compuesto.";
    }

    # Validación del correo eléctronico.
    if(!preg_match(EMAIL_REGEX, $email)){
        $errores['email'] = "El formato del correo electrónico no es válido.";
    }

    # Validación del teléfono haciendo uso de la constante PHONE_REGEX
    if(!preg_match(PHONE_REGEX, $phone)){
        $errores['phone'] = "El formato del teléfono no es válido, debe de empezar por (0,6,7,8,9) y contener un minimo de 9 digitos y un maxiomo de 10 digitos.";
    }

    # Validación de la fecha de nacimiento haciendo uso de la constante BIRTHDAY_REGEX
    if(!preg_match(DATE_REGEX, $birthday)){
        $errores['birthday'] = "El formato de la fecha de nacimiento no es válido.";
    }

    # Validación del domicilio haciendo uso de la constante ADDRESS_REGEX
    if(!preg_match(ADDRESS_REGEX, $address)){
        $errores['address'] = "El formato del domicilio no es válido.";
    }

    # Validación del genero haciendo uso de la constante GENDER_REGEX
    if(!preg_match(GENDER_REGEX, $gender)){
        $errores['gender'] = "El genero deberá contener entre 2 y 45 letras y se podrá hacer uso de un único espacio en caso de introducir un genero compuesto.";
    }

     # Validación del genero haciendo uso de la constante GENDER_REGEX
     if(!preg_match(USER_REGEX, $user)){
        $errores['user'] = "El usuario deberá contener entre 3 y 16 caracteres y NO se podrá hacer uso de espacios, pero si de guion bajo, guion medio (_-), letras minusculas y numeros.";

    }
    
    return $errores;
}



# Definimos la función validar_citas()
function validar_citas($createDate, $texCita){
    # Declarar un array asociativo
    $errores = [];

    # Validación de la fecha con DATE_REGEX
    if(!preg_match(DATE_REGEX, $createDate)){
        $errores['createDate'] = "El formato de fecha no es valido.";
    }

    # Validación del textarea con TEXTAREA_REGEX
    if(!preg_match(TEXT_REGEX, $texCita)){
        $errores['texCita'] = "El motivo de la cita debe de contener un minimo de 4 caracteres, un maximo de 150 caracteres y NO puede contener los siguientes caracteres (^$%&|<>#).";
    }

    return $errores;
}

#Definimos la función validar_noticias()
function validar_noticias($title, $img, $textarea, $create_date){
    #Declarar una array asociativo
    $errores = [];

    #Validación del titulo con TITLE_REGEX
    if(!preg_match(TITLE_REGEX, $title)){
        $errores['title'] = "El titulo de la noticia como minimo 3 caracteres y como máximo son 200 caracteres y no puede contener ningúno de estos caracteres (^$%&|<>#).";
    }

      #Validación del titulo con $img
      switch ($img['error']){
        case 0:
            // No hace nada, se descargo con exito.
            break;
        case 1:
            $errores['img'] = "ERROR. Tamaño del fichero superior al establecido en el servidor. ";
        case 2:
            $errores['img'] = "ERROR. Tamaño del fichero superior al establecido por el cliente. ";
            break;
        case 3:
            $errores['img'] = "ERROR. El fichero sólo se subió parcialmente. ";
            break;
        case 4:
            // No se hace nada, se anula para poder actualizar cuando el archivo no se ha seleccionado. $errores['img'] = "ERROR. No se ha seleccionado ningún fichero. ";
            break;
        case 6:
            $errores['img'] = "ERROR. No se encuentra la carpeta temporal. ";
            break;
        case 7:
            $errores['img'] = "ERROR. No se pudo escribir en disco. revisa permisos. ";
            break;
        case 8:
            $errores['img'] = "ERROR. Una extensión de php detuvo la subida del fichero. ";
            break;
        default:
            $errores['img'] = "Valor de error desconocido. ";
}

     #Validación del texto con TEXTAREA_REGEX
     if(!preg_match(TEXTAREA_REGEX, $textarea)){
        $errores['textarea'] = "El texto de la noticia como minimo 150 caracteres y no contener ningún de estos caracteres (^$%&|<>#). ";
    }

     #Validación de la fecha con DATE_REGEX
     if(!preg_match(DATE_REGEX, $create_date)){
        $errores['date'] = "El formato de la fecha no es valido. ";
    }

    return $errores;
}

