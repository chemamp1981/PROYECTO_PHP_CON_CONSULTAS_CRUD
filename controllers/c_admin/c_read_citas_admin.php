<?php
require_once '../db_conn.php';
require_once '../db_functions.php';
require_once '../../config/config.php';


#Comprobamos si existe una sesion activa y en caso de que no sea asi la creamos
if(session_status() == PHP_SESSION_NONE){
    session_start();
}


#VISUALIZAR EL FORMULARIO DE VER CITAS DE CADA USUARIO.
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['read_cita'])){
    $idUser = htmlspecialchars($_POST['idUser']);

    #LEER LAS CITAS DE CADA USUARIO
    #Intentamos comprobar las citas por el id.
    try{
        # Inicializamos una variable para guardar los errores de excepcion posibles
        $exception_error = false;

        #Extraer los datos de los usuarios a traves de la siguiente función:
        $verCitas = get_citas_and_alldata_by_idUser($idUser, $mysqli_connection, $exception_error);

                        
        # Comprobar si se ha capturado alguna excepción
        if($exception_error){
            # Redirigimos a la página de error que tengamos configurada
            $_SESSION['mensaje_error'] = "Error durante el proceso de extración de todos los datos de citas y los datos del usuario. Inténtelo de nuevo más tarde o si le sigue sucediendo contacte con el equipo de soporte";
            header("location:../../views/errors/error500.php");
            exit();
        }
         // Imprimir valores inmediatamente después de la asignación
       // echo "Inmediatamente después de la asignación:\n";
       // var_dump($verCitas); # print_r($usuarios);
    
        // Extra: Mostramos el tipo de dato asociado a $usuarios y su contenido
        //echo "Tipo de \$usuarios: " . gettype($usuarios) . "\n";
       // echo "Contenido de \$usuarios: " . json_encode($usuarios) . "\n";
            // Vacíamos la variable de sesión "ver_citas"
            unset($_SESSION['ver_citas']);
            #Creo la sesion de ver_citas
            if($verCitas){
                $_SESSION['ver_citas'] =  $verCitas;
                header('location:../../views/admin/admin_citas.php?citas=ok&id='.$verCitas[0]['idUser']);
                exit();
            }else{
            
                    $_SESSION['mensaje_error'] = " Actualmente no hay ninguna cita. ";
                    header("Location:../../views/admin/admin_citas.php");
                    exit();
                }

        

    }catch(Exception $e){
        error_log("Error durante el proceso de extración de los datos de las citas. " . $e -> getMessage());
        header("Location:../../views/errors/error500.php");
        exit();
    }finally{
        # Cerrar la conexión a la base de datos si aún sigue abierta
        if(isset($mysqli_connection) && ($mysqli_connection)){
            $mysqli_connection -> close();
        }
    }

}

#VEER TODOS LOS USUARIOS EN LA PAGINA DE CITAS
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

    //unset($_SESSION['all_users']);
    # Comprobamos si $usuarios es un array con contenido
    if(is_array($usuarios) && !empty($usuarios)) {
       //unset($_SESSION['all_users']);
        $_SESSION['all_users'] = $usuarios;
        header("Location:../../views/admin/admin_citas.php");
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
    error_log("Error durante el proceso de extración de todos los datos de los usuarios. " . $e->getMessage());
    header("Location:../../views/errors/error500.php");
    exit();
}finally{
    # Cerrar la conexión a la base de datos si aún sigue abierta
    if(isset($mysqli_connection) && ($mysqli_connection)){
        $mysqli_connection -> close();
    }
}


