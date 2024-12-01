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
    $idUser = htmlspecialchars($_POST['idUser']);
    
  #Intentamos el borrado de cada usuario.
  try{
    # Inicializamos una variable para guardar los errores de excepcion posibles
    $exception_error = false;
    $deleteUsers = delete_user_by_id($idUser, $mysqli_connection, $exception_error);
    # Comprobar si se ha capturado alguna excepción
    if($exception_error){
        # Redirigimos a la página de error que tengamos configurada
        $_SESSION['mensaje_error'] = "Error al extraer los datos de borrado. Inténtelo de nuevo más tarde o si le sigue sucediendo contacte con el equipo de soporte";
        header("Location:../../views/errors/error500.php");
        exit();
    }

    #Comprobamos la variable de borrado.
    if($deleteUsers){
        //como o que condicion se deberia hacer para que cuando se elimine el usuario de sesion me redirija a logout.php y a su vez destruiria todas la sesiones abiertas.
    
        //if(isset($_SESSION['all_data'])){

            #creamos variable de sesión
            $_SESSION['delete_users'] = $deleteUsers;
            #Mensaje de exito 
            $_SESSION['mensaje_exito'] = "El usuario se ha Borrado correctamente.";

            #redirecionamiento a c_read_admin.php donde se leen todos los usuarios, para visualizar de inmediato el borrado.
            header("Location:../../controllers/c_admin/c_read_admin.php");
            exit();
       /* }else{
            #creamos variable de sesión
            $_SESSION['delete_users'] = $deleteUsers;
            #Mensaje de exito 
            $_SESSION['mensaje_exito'] = "El usuario se ha Borrado correctamente.";

            #redirecionamiento a c_read_admin.php donde se leen todos los usuarios, para visualizar de inmediato el borrado.
            header("Location:../../controllers/c_admin/c_read_admin.php");
            exit();
        }*/
 
    }else{
        # Si no hay usuarios para leer mensaje de Advertencia.
        $_SESSION['mensaje_error'] = "El borrado no se ha realizado correctamente.";
        header("Location:../../controllers/c_admin/c_read_admin.php");
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