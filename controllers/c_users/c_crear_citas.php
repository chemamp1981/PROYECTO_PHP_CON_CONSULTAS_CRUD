<?php
require_once '../db_conn.php';
require_once '../db_functions.php';
require_once '../../config/config.php';
require_once '../validations/v_inputData.php';

#Comprobamos si existe una sesion activa y en caso de que no sea asi la creamos
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

#comprobar si hemos recibido los datos para actualizar el formulario
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['crear_cita'])){

    #Comprobar si los compos estan vacios.
    if(empty($_POST['fappoimentdate']) || empty($_POST['freason'])){
        $_SESSION['mensaje_error'] = "-Los dos campos deben estar rellenos";
        header('location:../../views/users/citaciones.php');
    }else{
        #Recuperar datos del formulario crear citas.
        $createDate = htmlspecialchars($_POST['fappoimentdate']);
        $textCita = htmlspecialchars($_POST['freason']);
        $idUser = $_SESSION['all_data']['idUser'];

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
                header("location:../../views/users/citaciones.php");
                exit();
            }

            
            try{
            
                # Inicializar la sentencia de Inserción como nula
                $insert_stmt = null;

                #Inicializamos una variable para guardar los errores de excepción  posibles
                $exception_error = false;

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
                        # Cerramos la sentencia
                        $insert_stmt -> close();
                        
                        # Configuramos un mensaje de éxito para el usuario y le redirigimos a la página de citaciones.
                        $_SESSION['mensaje_exito'] = "La cita se ha creado correctamente.";
                        header("Location:./c_citas.php");
                        exit();
                
                    }else{
                        # Se guarda el error de ejecución en el error_log
                        error_log("Error: " . $insert_stmt -> error);
                        
                        # Si la cita no se ha creado correctamente un mensaje de error
                        $_SESSION['mensaje_error'] = "Error: La cita no se ha creado.";
                        header("Location:../../views/login.php");
                        exit();
                    }
                }   
                #Si durante en el proceso surge una excepción 
            }catch(Exception $e){
                #Registramos la excepción en el error_log
                error_log("Error en c_crear_citas.php " .$e -> getMessage());
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

