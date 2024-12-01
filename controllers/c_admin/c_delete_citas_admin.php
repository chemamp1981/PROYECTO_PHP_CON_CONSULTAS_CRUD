<?php

require_once '../db_conn.php';
require_once '../db_functions.php';
require_once '../../config/config.php';


#Comprobamos si existe una sesion activa y en caso de que no sea asi la creamos
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

# Compruebo si recibo los datos del formulario para su Borrado.
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['borrar'])){
    #Recogemos la variable y la filtramos. 
    $idCita = htmlspecialchars($_POST['idCita']);
    $idUser = htmlspecialchars($_POST['idUser']);
  #Intentamos el borrado de cada usuario.
  try{
    # Inicializamos una variable para guardar los errores de excepcion posibles
    $exception_error = false;
    #Guardamos en una variable el borrado de la tabla citas, con la siguiente función.
    $deleteCitas = delete_citas_by_idCita($idCita, $mysqli_connection, $exception_error);
   
    # Comprobar si se ha capturado alguna excepción
    if($exception_error){
        # Redirigimos a la página de error que tengamos configurada
        $_SESSION['mensaje_error'] = "Error al extraer los datos de borrado. Inténtelo de nuevo más tarde o si le sigue sucediendo contacte con el equipo de soporte";
        header("Location:../../views/errors/error500.php");
        exit();
    }

    #Comprobamos la variable de borrado.
    if($deleteCitas){

            #creamos variable de sesión
            $_SESSION['delete_users'] = $deleteCita;
            #Mensaje de exito 
            $_SESSION['mensaje_exito'] = " La cita se ha Borrado correctamente. ";

            #Extraer los datos de los usuarios a traves de la siguiente función:
            $verCitas = get_citas_and_alldata_by_idUser($idUser, $mysqli_connection, $exception_error);

                        
            # Comprobar si se ha capturado alguna excepción
            if($exception_error){
                # Redirigimos a la página de error que tengamos configurada
                $_SESSION['mensaje_error'] = "Error durante el proceso de extración de todos los datos de citas y los datos del usuario. Inténtelo de nuevo más tarde o si le sigue sucediendo contacte con el equipo de soporte";
                header("location:../../views/errors/error500.php");
                exit();
            }
            unset($_SESSION['ver_citas']);
            #Comprobamos si exite la varialble $verCitas
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
        # Si no se ha podido borrar la cita, mensaje de error.
        $_SESSION['mensaje_error'] = "El borrado no se ha realizado correctamente.";
        header("Location:../../views/admin/admin_citas.php");
        exit();
    }
}catch(Exception $e){
    error_log("Error durante el proceso de borrado del usuario. " . $e -> getMessage());
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