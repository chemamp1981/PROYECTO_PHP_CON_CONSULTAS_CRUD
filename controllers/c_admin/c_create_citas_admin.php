<?php
require_once '../db_conn.php';
require_once '../db_functions.php';
require_once '../../config/config.php';
require_once '../validations/v_inputData.php';

#Comprobamos si existe una sesion activa y en caso de que no sea asi la creamos
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

#Redirigir al usuario indicado para la creación de citas.
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_cita'])){
    #Obtener el id del usuario
    $idUser = htmlspecialchars($_POST['idUser']);
    
    
    #Coger el id del usuario
    #LEER LOS USUARIOS
    #Intentamos comprobar la lectura de los datos de todos los usuarios.
    try{
        # Inicializamos una variable para guardar los errores de excepcion posibles
        $exception_error = false;

        #Extraer los datos de los usuarios por el id a traves de la siguiente función:
        $usuarios = get_users_by_id($idUser, $mysqli_connection, $exception_error);
                        
        # Comprobar si se ha capturado alguna excepción
        if($exception_error){
            # Redirigimos a la página de error que tengamos configurada
            $_SESSION['mensaje_error'] = "Error durante el proceso de extración de todos los datos de los usuarios. Inténtelo de nuevo más tarde o si le sigue sucediendo contacte con el equipo de soporte";
            header("location:../../views/errors/error500.php");
            exit();
        }
        
        # Comprobamos $usuarios 
        if($usuarios){
            #creamos una sesión para estos usuarios 
            $_SESSION['users'] = $usuarios;
            header('location:../../views/admin/admin_citas.php?create=ok&id='.$usuarios[0]['idUser']);
            exit();
           
        }else{
            $_SESSION['mensaje_error'] = " Actualmente no hay ningún usuario";
            header('location:../../views/admin/admin_citas.php');
            exit();
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

#crear la cita de cada usuario
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crear_cita'])){

    #Comprobar si los compos estan vacios.
    if(empty($_POST['fappoimentdate']) || empty($_POST['freason'])){
        $_SESSION['mensaje_error'] = "Los dos campos deben estar rellenos";
        header('location:../../views/admin/admin_citas.php');
        exit();
    }else{
        #Recuperar datos del formulario crear citas.
        $createDate = htmlspecialchars($_POST['fappoimentdate']);
        $textCita = htmlspecialchars($_POST['freason']);
        $idUser = htmlspecialchars($_POST['idUser']);

        #validar el formulario a traves de la función validar_citas().
        $errores_validacion = validar_citas($createDate, $textCita);

        #Comprobar si se han generado errores de validacion o no.
            if(!empty($errores_validacion)){
                # Si hay errores los guardamos en una cadena de caracteres que mostraremos al usuario
                $mensaje_error = "";

                foreach($errores_validacion as $clave => $mensaje){
                    $mensaje_error .= $mensaje . "<br>";
                }

                # Asignamos la cadena de caracteres con los errores a $_SESSION['mensaje_error']
                $_SESSION['mensaje_error'] = $mensaje_error;
                header('location:../../views/admin/admin_citas.php');
                exit();               
            }else{

                 # Inicializar la sentencia de Inserción como nula
                 $insert_stmt = null;

                 #Inicializamos una variable para guardar los errores de excepción  posibles
                 $exception_error = false;
            
                try{
                
                    #Insertar citas creadas
                    $query = "INSERT INTO citas (idUser, fecha_cita, motivo_cita) VALUES (?, ?, ?)";

                    #Preparar la sentencia SQL
                    $insert_stmt = $mysqli_connection -> prepare($query);

                    #Si la sentencia no se ha podido preparar.
                    if(!$insert_stmt){
                        error_log("No se pudo preparar la sentencia" . $mysqli_connection -> error);
                        # Se redirige al usuario a la página de error 500
                        header('location:../../views/errors/error500.php');
                        exit();
                    #Si la sentencia de inserción se ha podido preparar  
                    }else{
                        #Vinculamos los valores introducidos por el usuario.
                        $insert_stmt -> bind_param("sss", $idUser, $createDate, $textCita);
                        
                        #Si la sentencia se ha podido ejecutar
                        if($insert_stmt -> execute()){
                    
                            # Configuramos un mensaje de éxito para el usuario y le redirigimos a la página de admin citas.
                            $_SESSION['mensaje_exito'] = "La cita se ha creado correctamente.";
                            header("Location:../../views/admin/admin_citas.php?citas=ok");
                            exit();
                    
                        }else{
                            # Se guarda el error de ejecución en el error_log
                            error_log("Error: " . $insert_stmt -> error);
                            
                            # Si la cita no se ha creado correctamente un mensaje de error
                            $_SESSION['mensaje_error'] = "Error: La cita no se ha creado.";
                            header("Location:../../views/admin/admin_citas.php");
                            exit();
                        }
                    }   
                    #Si durante en el proceso surge una excepción 
                }catch(Exception $e){
                    #Registramos la excepción en el error_log
                    error_log("Error en c_crear_citas_admin.php " .$e -> getMessage());
                    #Redirigimos al usuario a la pagina de error 500
                    header('location:../../views/errors/error500.php');
                }finally{
                    # Cerramos la consulta si aún sigue abierta
                    if(isset($insert_stmt) && ($insert_stmt)){
                        $insert_stmt -> close();
                    }
                }   
            }
    }
}

