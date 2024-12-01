<?php
require_once '../db_conn.php';
require_once '../db_functions.php';
require_once '../../config/config.php';


#Comprobamos si existe una sesion activa y en caso de que no sea asi la creamos
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

#LEER LAS NOTICIAS CREADAS POR LOS ADMINISTRADORES

#Leer todas las noticias
  #Iniciar una variable para guardar todas las excepciones posibles.
  $exception_error = false;

try{
  
    #Estraer todas las noticias a traves de la siguiente función.
    $seeNews = get_news($mysqli_connection, $exception_error);

    #comprobar si se ha capturado algún error.
    if($exception_error){
        #Redirigir el error a la pagina error500.
        $_SESSION['mensaje_error'] = "La extración de las noticias no es posible.";
        header('location:../../views/errors/error500.php');
        exit();
    }
    
    if(is_array($seeNews) && !empty($seeNews)){
        #Comprobar la extración de la función y guardarla en una variable de sesión.
        $_SESSION['see_news'] = $seeNews;
        #Redirigir la variable de sesión a la pagina de noticias.
        header('location:../../views/noticias.php?news=ok');
        exit();
    }else{
        #si hay algún mensaje de exito se coloca junto al mensaje de error.
        if(isset($_SESSION['mensaje_exito'])){
            #Mensaje exitoso
            $mensaje = $_SESSION['mensaje_exito'];
            unset($_SESSION['mensaje_exito']);
            #Mensaje de error
            $_SESSION['mensaje_error'] = $mensaje." Actualmente no hay ninguna noticia publicada.";
            header('location:../../views/noticias.php');
            exit();
            
            #Si no hay ningún mensaje de exito se coloca solo el de error.
        }else{
            #Mensaje error.
            $_SESSION['mensaje_error'] = " Actualmente no hay ninguna noticia publicada.";
            header('location:../../views/noticias.php');
            exit();
        }
    }
    
    #Capturar la excepción
}catch(Exception $e){
    #Guardar el error_log en el servidor
    error_log("Error durante el proceso de extración  de los datos de las noticias. ". $e->getMessage());
    header('loction:../../views/errors/error500.php');
    exit();
}finally{
    #Cerrar la conexión a la base de datos si aú sigue abierta.
    if(isset($mysqli_connection) && ($mysqli_connection)){
        $mysqli_connection->close();
    }
}
