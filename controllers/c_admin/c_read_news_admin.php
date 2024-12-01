<?php
require_once '../db_conn.php';
require_once '../db_functions.php';
require_once '../../config/config.php';
require_once '../validations/v_inputData.php';

#Comprobamos si existe una sesion activa y en caso de que no sea asi la creo.
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

#VER LAS NOTICIAS DE CADA USUARIO
#Comprobar si recibo los datos del formulario.
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['read_news'])){
    #Recuperar el id del usuario.
    $idUser = htmlspecialchars($_POST['idUser']);

    #Extraer los datos de noticias a taves del id
    try{
        #Declarar la variable para los posibles excepciones.
        $exception_error = false;
        
        #Declarar la variable para su extracción.
        $newsData = get_news_and_alldata_by_idUser($idUser, $mysqli_connection, $exception_error);

        #Comprobar las excepciones.
        if($exception_error){
            #Mensaje de error.
            $_SESSION['mensaje_error'] = "No se puede extraer los datos de la tabla noticias. Contacte con el soporte tecnico.";
            #Redirigir a la pagina de error500.
            header('location:../../views/errors/error500.php');
            exit();
        }
        
        #Comprobar la variable de extración de los datos
        if($newsData){
           
            #Guardar la variable de la extracción de noticias en una sesión.
            $_SESSION['data_news'] =  $newsData;
            

            #redirigir la variable a la pagina admin_noticias
            header('location:../../views/admin/admin_noticias.php?news=ok&id='.$idUser);
            exit();
            
        }else{
            #Mensaje de error y redirigir a la pagina admin_noticias.php
            $_SESSION['mensaje_error'] = " Actualmente no hay ninguna noticia";
            header('location:../../views/admin/admin_noticias.php');
            exit();
        }

    }catch(Exception $e){
        #Si se ha capturado un error guardarlo en el errorlog del servidor y redirigiendo el mensaje a la pagina error500.
        error_log("No se realizo la extracción de los datos de noticias". $e->getMessage());
        $_SESSION['mensaje_error'] = "Actualmente no hay ninguna noticia";
        header('location:../../views/errors/error500.php');
        exit();
    }finally{
        #Cerrar la conexion de la base de datos si aún sigue abierta.
        if(isset($mysqli_connection) && ($mysqli_connection)){
            $mysqli_connection->close();
        }

    }

}


#VER  TODOS LOS USUARIOS EN LA PAGINA DE ADMNISTRACION DE NOTICIAS
#Intento comprobar la lectura de los datos de todos los usuarios.
try{
    # Inicializamos una variable para guardar los errores de excepcion posibles
    $exception_error = false;

    #Extraer los datos de los usuarios a traves de la siguiente función:
    $usuarios = get_users($mysqli_connection, $exception_error);
                    
    # Comprobar si se ha capturado alguna excepción
    if($exception_error){
        # Redirigimos a la página de error que tengamos configurada
        $_SESSION['mensaje_error'] = "Error durante el proceso de extración de todos los datos de los usuarios. Inténtelo de nuevo más tarde o si le sigue sucediendo contacte con el equipo de soporte";
        header("location:../../views/errors/error500.php");
        exit();
    }

    
    # Comprobamos si $usuarios es un array con contenido
    if(is_array($usuarios) && !empty($usuarios)) {
        
        $_SESSION['all_users'] = $usuarios;
        header("Location:../../views/admin/admin_noticias.php");
        exit();

    }else{
        if(isset($_SESSION['mensaje_exito'])) {

            $mensaje = $_SESSION['mensaje_exito'];
            unset($_SESSION['mensaje_exito']);

            $_SESSION['mensaje_error'] = $mensaje . " Actualmente no hay ningún usuario registrado, se ha eliminado el usuario de sesión.";
            header("Location:../../controllers/c_logout.php");
            exit();
        
        }else{
            $_SESSION['mensaje_error'] = " Actualmente no hay ningún usuario registrado, se ha eliminado el usuario de sesión.";
            header("Location:../../controllers/c_logout.php");
            exit();
        }

    }

}catch(Exception $e){
    error_log("Error durante el proceso de extración de todos los datos de los usuarios. " . $e -> getMessage());
    header("Location:../../views/errors/error500.php");
    exit();
}finally{
    # Cerrar la conexión a la base de datos si aún sigue abierta
    if(isset($mysqli_connection) && ($mysqli_connection)){
        $mysqli_connection -> close();
    }
}


