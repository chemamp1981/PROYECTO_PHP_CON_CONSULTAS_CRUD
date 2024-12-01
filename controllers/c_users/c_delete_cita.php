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
    
  #Intentamos el borrado de la cita.
  try{
    # Inicializamos una variable para guardar los errores de excepcion posibles
    $exception_error = false;
    $delete = delete_citas_by_idCita($idCita, $mysqli_connection, $exception_error);
    # Comprobar si se ha capturado alguna excepción
    if($exception_error){
        # Redirigimos a la página de error que tengamos configurada
        $_SESSION['mensaje_error'] = "Error al extraer los datos de borrado. Inténtelo de nuevo más tarde o si le sigue sucediendo contacte con el equipo de soporte";
        header("Location:../../views/errors/error500.php");
        exit();
    }

    #Comprobamos la variable de borrado.
    if($delete){
        #creamos variable de sesión
        $_SESSION['delete'] = $delete;
        #Mensaje de exito 
        $_SESSION['mensaje_exito'] = "La cita se ha Borrado correctamente.";

        #redirecionamiento a c_citas donde se leen las citas, para visualizar de inmediato el borrado.
        header("Location:./c_citas.php");
        exit();
    }else{
        # Si no hay citas para leer mensaje de Advertencia.
        $_SESSION['mensaje_error'] = "El borrado no se ha realizado correctamente.";
        header("Location:./c_citas.php");
        exit();
    }
}catch(Exception $e){
    error_log("Error durante el proceso de borrado de la cita. " . $e -> getMessage());
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