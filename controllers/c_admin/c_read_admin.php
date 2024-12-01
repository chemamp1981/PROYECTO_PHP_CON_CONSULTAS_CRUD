<?php
require_once '../db_conn.php';
require_once '../db_functions.php';
require_once '../../config/config.php';


#Comprobamos si existe una sesion activa y en caso de que no sea asi la creamos
if(session_status() == PHP_SESSION_NONE){
    session_start();
}


  #EDITAR CADA USUARIO
    #Se coge el id del usuario 
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edUser'])){
        $idUser = $_POST['idUser'];
            #Intentamos comprobar la lectura de los datos de cada usuario.
        try{
            # Inicializamos una variable para guardar los errores de excepcion posibles
            $exception_error = false;

            #Extraer los datos de los usuarios a traves de la siguiente función:
            $usuario = get_userData_and_userLogin_by_id($idUser, $mysqli_connection, $exception_error);
                            
            # Comprobar si se ha capturado alguna excepción
            if($exception_error){
                # Redirigimos a la página de error que tengamos configurada
                $_SESSION['mensaje_error'] = "Error al extraer los datos de todos los usuarios. Inténtelo de nuevo más tarde o si le sigue sucediendo contacte con el equipo de soporte";
                header("location:../../views/errors/error500.php");
                exit();
            }

            //vaciamos la variable de sesión que se extrae de cada usuario
            unset($_SESSION['eachUser']); 
            # Comprobamos  la extración de los datos de cada usuario
            if($usuario){
                $_SESSION['eachUser'] = $usuario;
                header("Location:../../views/admin/admin_users.php?modificar=ok&id=".$usuario['idUser']);
                exit();
            }else{
                if(isset($_SESSION['mensaje_exito'])) {
                    $mensaje = $_SESSION['mensaje_exito'];
                    unset($_SESSION['mensaje_exito']);
                    $_SESSION['mensaje_error'] = $mensaje . " Actualmente no hay ningun usuario creado.";
                    header('Location:../../views/admin/admin_users.php');
                    exit();
                }else{
                    $_SESSION['mensaje_error'] = "Actualmente no hay ningun usuario creado.";
                    header('Location:../../views/admin/admin_users.php');
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
    }   


#LEER LOS USUARIOS
#Intentamos comprobar la lectura de los datos de todos los usuarios.
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
        // Imprimir valores inmediatamente después de la asignación
        //echo "Inmediatamente después de la asignación:\n";
        //var_dump($usuarios); # print_r($usuarios);
    
        // Extra: Mostramos el tipo de dato asociado a $usuarios y su contenido
        //echo "Tipo de \$usuarios: " . gettype($usuarios) . "\n";
        // echo "Contenido de \$usuarios: " . json_encode($usuarios) . "\n";

        unset($_SESSION['all_users']);
        # Comprobamos si $usuarios es un array con contenido
        if(is_array($usuarios) && !empty($usuarios)) {

            unset($_SESSION['all_users']);

            $_SESSION['all_users'] = $usuarios;
            header("Location:../../views/admin/admin_users.php");
            exit();
        }else{
            if(isset($_SESSION['mensaje_exito'])) {

                $mensaje = $_SESSION['mensaje_exito'];
                unset($_SESSION['mensaje_exito']);

                $_SESSION['mensaje_error'] = $mensaje . " Actualmente no hay ningún usuario registrado, se ha eliminado el usuario de sesión.a";
                header("Location:../../controllers/c_logout.php");
                exit();
            
            }else{
                $_SESSION['mensaje_error'] = " Actualmente no hay ningún usuario registrado, se ha eliminado el usuario de sesión.b";
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


  
?>