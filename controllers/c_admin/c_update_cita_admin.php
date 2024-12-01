<?php

require_once '../db_conn.php';
require_once '../db_functions.php';
require_once '../../config/config.php';
require_once '../validations/v_inputData.php';

#Comprobamos si existe una sesion activa y en caso de que no sea asi la creamos
if(session_status() == PHP_SESSION_NONE){
    session_start();
}


#redirigir a la modificación de la cita.
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edCita'])){
$idCita = htmlspecialchars($_POST['idCita']);

    #LEER EL USUARIO DE CITAS
    #Intentamos comprobar la lectura de los datos de cita a traves del idCita.
    try{
        # Inicializamos una variable para guardar los errores de excepcion posibles
        $exception_error = false;

        #Extraer los datos de citas por el id a traves de la siguiente función:
        $eachCita = get_cita_by_idCita($idCita, $mysqli_connection, $exception_error);
                        
        # Comprobar si se ha capturado alguna excepción
        if($exception_error){
            # Redirigimos a la página de error que tengamos configurada
            $_SESSION['mensaje_error'] = "Error durante el proceso de extacion de cita. Inténtelo de nuevo más tarde o si le sigue sucediendo contacte con el equipo de soporte";
            header("location:../../views/errors/error500.php");
            exit();
        }

        # Comprobamos $eachCita
        if($eachCita){

            $_SESSION['each_cita'] = $eachCita;
            header('location:../../views/admin/admin_citas.php?modify=ok&id='.$eachCita[0]['idUser']);
            exit();
        
        }else{
            $_SESSION['mensaje_error'] = " Actualmente no hay ningúna cita para este usuario";
            header('location:../../views/admin/admin_citas.php');
            exit();
        }

    }catch(Exception $e){
        error_log("Error durante el proceso de extración de los datos de citas. " . $e -> getMessage());
        header("Location:../../views/errors/error500.php");
        exit();
    }finally{
        # Cerrar la conexión a la base de datos si aún sigue abierta
        if(isset($mysqli_connection) && ($mysqli_connection)){
            $mysqli_connection -> close();
        }
    }

}  


# Compruebo si recibo los datos del formulario para su actualización.
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['modificar'])){
    #Recoger los datos y filtrarlos para su actualización 
    $dateCita = htmlspecialchars($_POST['fappoimentdate']);
    $motivoCita = htmlspecialchars($_POST['freason']);
    $idCita = htmlspecialchars($_POST['idCita']);
    $idUser = htmlspecialchars($_POST['idUser']);
    
    #validar el formulario a traves de la función validar_citas().
    $errores_validacion = validar_citas($dateCita, $motivoCita);

    #Comprobar si se han generado errores de validacion o no.
    if(!empty($errores_validacion)){

        # Si hay errores los guardamos en una cadena de caracteres que mostraremos al usuario
        $mensaje_error = "";

        foreach($errores_validacion as $clave => $mensaje){
            $mensaje_error .= $mensaje . "<br>";
        }

        # Asignamos la cadena de caracteres con los errores a $_SESSION['mensaje_error']
        $_SESSION['mensaje_error'] = $mensaje_error;
        header('location:../../views/admin/admin_citas.php?modify=ok');
        exit();
    }

    #Definimos la actualización
    try{
        
        #Inicializamos una variable para guardar los errores de excepción.
        $exception_error = false;
        
        #Extraer en una variable los datos actualizados a través de la siguiente función.
        $newCita = update_citas_by_idCita($dateCita, $motivoCita, $idCita, $mysqli_connection, $exception_error);
        
        #Comprobar si se ha capturado alguna excepción
        if($exception_error){
            #Redirigimos a la Pgína de error que tenemos configurada.
            $_SESSION['mensaje_error'] = "Error durante el proceso de actualización. Inténtelo de nuevo más tarde o si le sigue sucediendo contacte con el equipo de soporte";
            header("location:../../views/errors/error500.php");
            exit();
        }
        #comprobación de la actualizacón.
        if($newCita){
            #Actualizar los datos de la sesión.
            $_SESSION['cita']['fecha_cita'] = $dateCita;
            $_SESSION['cita']['motivo_cita'] = $motivoCita;
            
            #Mensaje de exito.
            $_SESSION['mensaje_exito'] = "Los datos se han actualizado correctamente";

            #Extraer los datos de los usuarios a traves de la siguiente función:
            $verCitas = get_citas_and_alldata_by_idUser($idUser, $mysqli_connection, $exception_error);

                        
            # Comprobar si se ha capturado alguna excepción
            if($exception_error){
                # Redirigimos a la página de error que tengamos configurada
                $_SESSION['mensaje_error'] = "Error durante el proceso de extración de todos los datos de citas y los datos del usuario. Inténtelo de nuevo más tarde o si le sigue sucediendo contacte con el equipo de soporte";
                header("location:../../views/errors/error500.php");
                exit();
            }
        
            if($verCitas){
                $_SESSION['ver_citas'] =  $verCitas;
                header('location:../../views/admin/admin_citas.php?citas=ok&id='.$verCitas[0]['idUser']);
                exit();
            }else{
            
                    $_SESSION['mensaje_error'] = " Actualmente no hay ninguna cita.";
                    header("Location:../../views/admin/admin_citas.php");
                    exit();
                }

          

        }else{

            #Mensaje de error.
            $_SESSION['$mensaje_error'] = "¡Hubo un error al actualizar los datos!";
            #Redirijo a la pagina admin_citas.php.
            header('location:../../views/admin/admin_citas.php?citas=ok');
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

?>