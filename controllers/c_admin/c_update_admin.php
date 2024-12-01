<?php

require_once '../db_conn.php';
require_once '../db_functions.php';
require_once '../../config/config.php';
require_once '../validations/v_inputData.php';

#Comprobamos si existe una sesion activa y en caso de que no sea asi la creamos
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

# Compruebo si recibo los datos del formulario para su actualización.
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['modificar'])){
    #Recoger los datos y filtrarlos para su actualización 
    $name = htmlspecialchars($_POST['fname']);
    $surname = htmlspecialchars($_POST['fsurname']);
    $email =  filter_input(INPUT_POST,'femail', FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars($_POST['fphone']);
    $birthday = htmlspecialchars($_POST['fbirthday']);
    $address = htmlspecialchars($_POST['faddress']);
    $gender = htmlspecialchars($_POST['fgender']);
    $user = htmlspecialchars($_POST['fuser']);
    $rol = htmlspecialchars($_POST['frol']);
    $idUser = htmlspecialchars($_POST['idUser']);
    
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
                header('location:../../views/admin/admin_users.php?modificar=ok');
                exit();
            }

            #Intentamos la actualización
            try{

                # Inicializamos una variable para guardar los errores de excepcion posibles
                $exception_error = false;

                # Actualización de todos los datos de cada usuario-administración.
                $resultado = update_user_admin_alldata($name, $surname, $email, $phone, $birthday, $address, $gender, $user, $pass, $rol, $idUser, $mysqli_connection, $exception_error);
                
                # Comprobar si se ha capturado alguna excepción
                if($exception_error){
                    # Redirigimos a la página de error que tengamos configurada
                    $_SESSION['mensaje_error'] = "Error durante el proceso de actualización. Inténtelo de nuevo más tarde o si le sigue sucediendo contacte con el equipo de soporte 1";
                    header("Location:../../views/errors/error500.php");
                    exit();
                }

                if($resultado){
                    #Actualizar los datos de la sesión.
                    $_SESSION['eachUser']['nombre'] = $name;
                    $_SESSION['eachUser']['apellidos'] = $surname;
                    $_SESSION['eachUser']['email'] = $email;
                    $_SESSION['eachUser']['telefono'] = $phone;
                    $_SESSION['eachUser']['fecha_nacimiento'] = $birthday;
                    $_SESSION['eachUser']['direccion'] = $address;
                    $_SESSION['eachUser']['sexo'] = $gender;
                    $_SESSION['eachUser']['usuario'] = $user;
                    $_SESSION['eachUser']['password'] = $pass;
                    $_SESSION['eachUser']['rol'] = $rol;

                    $_SESSION['mensaje_exito'] = "¡Los datos se han actualizado correctamente!";
                    header('location:../../controllers/c_admin/c_read_admin.php');
                    exit();
                }else{
                    $_SESSION['mensaje_error'] = "¡Hubo un error al actualizar los datos!";
                    header('location:../../views/admin/admin_users.php?modificar=ok');
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
                header('location:../../views/admin/admin_users.php?modificar=ok');
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
                header('location:../../views/admin/admin_users.php?modificar=ok');
                exit();
            }

            # Inicializamos una variable para guardar los errores de excepcion posibles
            $exception_error = false;

            # Actualización de los datos de cada usuario de usuarios-administración, menos el password.
            $result = update_user_admin_without_password($name, $surname, $email, $phone, $birthday, $address, $gender, $user, $rol, $idUser, $mysqli_connection, $exception_error);
            
            # Comprobar si se ha capturado alguna excepción
            if($exception_error){
                # Redirigimos a la página de error que tengamos configurada
                $_SESSION['mensaje_error'] = "Error durante el proceso de actualización. Inténtelo de nuevo más tarde o si le sigue sucediendo contacte con el equipo de soporte";
                header("Location:../../views/errors/error500.php");
                exit();
            }

            
            if($result){
                
                #Actualizar los datos de la sesión.
                $_SESSION['eachUser']['nombre'] = $name;
                $_SESSION['eachUser']['apellidos'] = $surname;
                $_SESSION['eachUser']['email'] = $email;
                $_SESSION['eachUser']['telefono'] = $phone;
                $_SESSION['eachUser']['fecha_nacimiento'] = $birthday;
                $_SESSION['eachUser']['direccion'] = $address;
                $_SESSION['eachUser']['sexo'] = $gender;
                $_SESSION['eachUser']['usuario'] = $user;
                $_SESSION['eachUser']['rol'] = $rol;
               

                $_SESSION['mensaje_exito'] = "¡Los datos se han actualizado correctamente!";
                header('location:../../controllers/c_admin/c_read_admin.php');
                exit();
            }else{
                
                $_SESSION['mensaje_error'] = "¡Hubo un error al actualizar los datos!";
                header('location:../../views/admin/admin_users.php?modificar=ok');
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
?>