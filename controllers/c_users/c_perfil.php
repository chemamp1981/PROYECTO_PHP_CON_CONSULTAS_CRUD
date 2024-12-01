<?php
# Vinculamos los archivos necesarios
require_once '../db_conn.php';
require_once '../db_functions.php';
require_once __DIR__. '/../../config/config.php';
require_once __DIR__. '/../validations/v_inputData.php';

# Comprobamos si existe una sesión activa y en caso de que no sea así la creamos.
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

# Compruebo si he recibido el dato para activar el formulario de editar Perfil.
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])){
    # Creo ruta para la activación de editar perfil por GET.
    header('location:../../views/users/perfil.php?edit=ok');
} 
    # Compruebo si recibo los datos del formulario para su actualización.
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizar'])){
        #Recuperamos los datos del formulario perfil.
        $name = htmlspecialchars($_POST['fname']);
        $surname = htmlspecialchars($_POST['fsurname']);
        $email =  filter_input(INPUT_POST,'femail', FILTER_SANITIZE_EMAIL);
        $phone = htmlspecialchars($_POST['fphone']);
        $birthday = htmlspecialchars($_POST['fbirthday']);
        $address = htmlspecialchars($_POST['faddress']);
        $gender = htmlspecialchars($_POST['fgender']);
        $user = htmlspecialchars($_POST['fuser']);
        $idUser = $_SESSION['all_data']['idUser'];
    
    

        # Comprobamos si esta activada la casilla del checkbox
        if(isset($_POST['fcheck'])){
            # Comprobamos que los dos inputs del password esten rellenos con la nueva contraseña.
            if((!empty($_POST['fpassword'])) && (!empty($_POST['fpasswordConfirm'])) && ($_POST['fpassword'] === $_POST['fpasswordConfirm'])){
                $password = htmlspecialchars($_POST['fpassword']);
                $passconfirm = htmlspecialchars($_POST['fpasswordConfirm']);
                $pass = password_hash($passconfirm, PASSWORD_BCRYPT);

                # Validar el formulario a través de la función validar_perfil()
                $errores_validacion = validar_perfil($name, $surname, $email, $phone, $birthday, $address, $gender, $user, $pass);

                # Comprobar si se han generado errores de validacion o no
                if(!empty($errores_validacion)){
                     # Si hay errores los guardamos en una cadena de caracteres que mostraremos al usuario
                     $mensaje_error = "";
 
                     foreach($errores_validacion as $clave => $mensaje){
                         $mensaje_error .= $mensaje . "<br>";
                     }
 
                     # Asignamos la cadena de caracteres con los errores a $_SESSION['mensaje_error']
                     $_SESSION['mensaje_error'] = $mensaje_error;
                     header('location:../../views/users/perfil.php?edit=ok');
                     exit();
                }

                #Intentamos la actualización
                try{

                    # Inicializamos una variable para guardar los errores de excepcion posibles
                    $exception_error = false;

                    # Actualización de todos los datos del perfil del usuario en la base de datos.
                    $resultado = update_user_alldata($name, $surname, $email, $phone, $birthday, $address, $gender, $user, $pass, $idUser, $mysqli_connection, $exception_error);
                    
                    # Comprobar si se ha capturado alguna excepción
                    if($exception_error){
                        # Redirigimos a la página de error que tengamos configurada
                        $_SESSION['mensaje_error'] = "Error durante el proceso de actualización. Inténtelo de nuevo más tarde o si le sigue sucediendo contacte con el equipo de soporte";
                        header("Location:../../views/errors/error500.php");
                        exit();
                    }

                    if($resultado){
                        #Actualizar los datos de la sesión.
                        $_SESSION['all_data']['nombre'] = $name;
                        $_SESSION['all_data']['apellidos'] = $surname;
                        $_SESSION['all_data']['email'] = $email;
                        $_SESSION['all_data']['telefono'] = $phone;
                        $_SESSION['all_data']['fecha_nacimiento'] = $birthday;
                        $_SESSION['all_data']['direccion'] = $address;
                        $_SESSION['all_data']['sexo'] = $gender;
                        $_SESSION['all_data']['usuario'] = $user;
                        $_SESSION['all_data']['password'] = $pass;
                        
                        $_SESSION['mensaje_exito'] = "¡Los datos se han actualizado correctamente!";
                        header('location:../../views/users/perfil.php?edit=ok');
                        exit();
                    }else{
                        $_SESSION['mensaje_error'] = "¡Hubo un error al actualizar los datos!";
                        header('location:../../views/users/perfil.php?edit=ok');
                        exit();
                    }

                   
                }catch(Exception $e){
                    error_log("Error durante el proceso de actualización" . $e -> getMessage());
                    header("Location:../../views/errors/error500.php");
                    exit();
                }finally{
                    # Cerrar la conexión a la base de datos si aún sigue abierta
                    if(isset($mysqli_connection) && ($mysqli_connection)){
                        $mysqli_connection -> close();
                    }
                }

            }else{
                    $_SESSION['mensaje_error'] = "Los password no coinciden, pruebe de nuevo";
                    header('location:../../views/users/perfil.php?edit=ok');
                    exit();
                }


        #Si no esta activa la casilla del checkbox se actualizarán los datos sin el password.            
        }else{

            #Intentamos la actualización
            try{

                # Validar el formulario a través de la función validar_perfil_without_pass()
                $errores_validacion = validar_perfil_without_pass($name, $surname, $email, $phone, $birthday, $address, $gender, $user);

                # Comprobar si se han generado errores de validacion o no
                if(!empty($errores_validacion)){
                     # Si hay errores los guardamos en una cadena de caracteres que mostraremos al usuario
                     $mensaje_error = "";
 
                     foreach($errores_validacion as $clave => $mensaje){
                         $mensaje_error .= $mensaje . "<br>";
                     }
 
                     # Asignamos la cadena de caracteres con los errores a $_SESSION['mensaje_error']
                     $_SESSION['mensaje_error'] = $mensaje_error;
                     header('location:../../views/users/perfil.php?edit=ok');
                     exit();
                }

                # Inicializamos una variable para guardar los errores de excepcion posibles
                $exception_error = false;

                # Actualización de los datos del perfil del usuario, menos el password en la base de datos.
                $result = update_user_without_password($name, $surname, $email, $phone, $birthday, $address, $gender, $user, $idUser, $mysqli_connection, $exception_error);
                
                # Comprobar si se ha capturado alguna excepción
                if($exception_error){
                    # Redirigimos a la página de error que tengamos configurada
                    $_SESSION['mensaje_error'] = "Error durante el proceso de actualización. Inténtelo de nuevo más tarde o si le sigue sucediendo contacte con el equipo de soporte";
                    header("Location:../../views/errors/error500.php");
                    exit();
                }

                
                if($result){
                    
                    #Actualizar los datos de la sesión.
                    $_SESSION['all_data']['nombre'] = $name;
                    $_SESSION['all_data']['apellidos'] = $surname;
                    $_SESSION['all_data']['email'] = $email;
                    $_SESSION['all_data']['telefono'] = $phone;
                    $_SESSION['all_data']['fecha_nacimiento'] = $birthday;
                    $_SESSION['all_data']['direccion'] = $address;
                    $_SESSION['all_data']['sexo'] = $gender;
                    $_SESSION['all_data']['usuario'] = $user;
                    
                    $_SESSION['mensaje_exito'] = "¡Los datos se han actualizado correctamente!";
                    header('location:../../views/users/perfil.php?edit=ok');
                    exit();
                }else{
                    
                    $_SESSION['mensaje_error'] = "¡Hubo un error al actualizar los datos!";
                    header('location:../../views/users/perfil.php?edit=ok');
                    exit();
                }
            }catch(Exception $e){
                error_log("Error durante el proceso de actualización" . $e -> getMessage());
                header("Location:../../views/errors/error500.php");
                exit();
            }finally{
                # Cerrar la conexión a la base de datos si aún sigue abierta
                if(isset($mysqli_connection) && ($mysqli_connection)){
                    $mysqli_connection -> close();
                }
            }
           
        }        
        
        
    }
