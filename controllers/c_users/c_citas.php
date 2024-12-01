<?php

require_once '../db_conn.php';
require_once '../db_functions.php';
require_once '../../config/config.php';


#Comprobamos si existe una sesion activa y en caso de que no sea asi la creamos
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

#EDITAR LAS CITAS

#Mostrar la siguiente sección con GET y redirigir con una variable. 
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edCita'])){
    #Recogemos la variable de editar cita.
    $idCita = htmlspecialchars($_POST['idCita']);

    #Intentamos la comprobación
    try{
        #Inicializamos una variable par guardar los errores de exception.
        $exception_error = false;
        #Extraemos en una variable los datos de la tabla citas.
        $cita = get_cita_by_idCita($idCita, $mysqli_connection, $exception_error);
        #Comprobar si se ha capturado alguna excepción
        if($exception_error){
            #Redirigimos a la página de error que tengamos configurada
            $_SESSION['mensaje_error'] = "Error al extraer los datos de cita. Inténtelo de nuevo más tarde o si le sigue sucediendo contacte con el equipo de soporte";
            header("location:../../views/errors/error500.php");
            exit();
        }
        # Comprobamos si los datos se han extraido.
        if($cita){
            $_SESSION['cita'] = $cita;
            header('location:../../views/users/citaciones.php?edCita=ok&id='.$cita[0]['idCita']);
            exit();
        }else{
            # Si no hay citas, mensaje de error. if(isset($_SESSION['data_cita']))
            $_SESSION['mensaje_error'] = "Actualmente no tiene ninguna cita reservada.";
            header("Location:../../views/users/citaciones.php");
            exit();
        }
    }catch(Exception $e){
        error_log("Error durante el proceso de extración de los datos de cita. " . $e -> getMessage());
        header("Location:../../views/errors/error500.php");
        exit();
    }finally{
        # Cerrar la conexión a la base de datos si aún sigue abierta
        if(isset($mysqli_connection) && ($mysqli_connection)){
            $mysqli_connection -> close();
          }
      }
  
}

#LEER LAS CITAS

#Comprobar si existe la sesion para coger el idUser
if(isset($_SESSION['all_data'])){
    $idUser = $_SESSION['all_data']['idUser'];
}else{
    $_SESSION['mensaje_error'] = "Lo sentimos debes iniciar sesión primero";
    header('location:../../views/login.php');
}

#Intentamos comprobar la lectura de los datos de las citas.
try{
    # Inicializamos una variable para guardar los errores de excepcion posibles
    $exception_error = false;

    #Extraer los datos de las citas a traves de la siguiente función:
    $citas = get_citas_by_idUser($idUser, $mysqli_connection, $exception_error);
                    
    # Comprobar si se ha capturado alguna excepción
    if($exception_error){
        # Redirigimos a la página de error que tengamos configurada
        $_SESSION['mensaje_error'] = "Error al extraer los datos de citas. Inténtelo de nuevo más tarde o si le sigue sucediendo contacte con el equipo de soporte";
        header("location:../../views/errors/error500.php");
        exit();
    }

    // Vacíamos la variable de sesión "data_citas"
    unset($_SESSION['data_citas']);

   // Comprobamos si existen citas para el usuario registrado
    if($citas){
        $_SESSION['data_citas'] = $citas;
        header("Location:../../views/users/citaciones.php");
        exit();
    }else{
        if(isset($_SESSION['mensaje_exito'])) {
            $mensaje = $_SESSION['mensaje_exito'];
            unset($_SESSION['mensaje_exito']);
            $_SESSION['mensaje_error'] = $mensaje . " Actualmente no hay ninguna cita reservada.";
            header('location:../../views/users/citaciones.php');
            exit();
        }else{
            $_SESSION['mensaje_error'] = "Actualmente no hay ninguna cita reservada.";
            header('location:../../views/users/citaciones.php');
            exit();
        }

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
?>