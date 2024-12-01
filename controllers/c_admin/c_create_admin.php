<?php
require_once '../db_conn.php';
require_once '../db_functions.php';
require_once '../../config/config.php';
require_once __DIR__. '/../validations/v_inputData.php';

#Comprobamos si existe una sesion activa y en caso de que no sea asi la creamos
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

#Comprobamos si la información llega a través del método POST y del formulario con submit "registrarse"
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create'])){

     #En primer lugar obtenemos los datos del formulario saneados
     $name = htmlspecialchars($_POST['fname']);
     $surname = htmlspecialchars($_POST['fsurname']);
     $email =  filter_input(INPUT_POST,'femail', FILTER_SANITIZE_EMAIL);
     $phone = htmlspecialchars($_POST['fphone']);
     $birthday = htmlspecialchars($_POST['fbirthday']);
     $address = htmlspecialchars($_POST['faddress']);
     $gender = htmlspecialchars($_POST['fgender']);
     $user = htmlspecialchars($_POST['fuser']);
     $rol = htmlspecialchars($_POST['frol']);
     $pass = htmlspecialchars($_POST['fpassword']);

      #Validar el formulario a través de la función validar_registro()
      $errores_validacion = validar_registro($name, $surname, $email, $phone, $birthday, $address, $gender, $user, $pass);

      #Comprobar si se han generado errores de validacion o no
      if(!empty($errores_validacion)){
          # Si hay errores de validación vamos a guardarlos en una cadena para mostrarselos al usuario
          $mensaje_error = "";

          #Recorremos el array de errores para concatenar los mensajes en la variable $mensaje_error
          foreach($errores_validacion as $clave => $mensaje){
              $mensaje_error .= $mensaje . "<br>";
          }

          #Asignamos la cadena de errores a $_SESSION['mensaje_error']
          $_SESSION['mensaje_error'] = $mensaje_error;

          header("Location:../../views/admin/admin_users.php");
          exit();
      }
      $contrasena = password_hash($pass, PASSWORD_BCRYPT);

      #Relizar la creación del usuario
      try{
          # Declaramos la variable que registrará si se ha producido una excepción durante el proceso que
          # comprueba si el usuario que se está intentando crear YA existe en la base de datos.
          $exception_error = false;
         # SI el resultado de algunos de los  check_user es TRUE (ya existe el usaurio con el mismo email o con el mismo nombre de usuario)
         if((check_user_email($email, $mysqli_connection, $exception_error) == true) || (check_user_usuario($user, $mysqli_connection, $exception_error) == true )){
              
            if(check_user_email($email, $mysqli_connection, $exception_error) == true){

                #Establecemos un mensaje de error en la sesión
                $_SESSION['mensaje_error'] = "ERROR: El usuario ya existe con este email en la base de datos";
                # Redirigimos al usuario a la página de registro
                header("Location:../views/registro.php");
                exit();
            }

            if(check_user_usuario($user, $mysqli_connection, $exception_error) == true){

                #Establecemos un mensaje de error en la sesión
                $_SESSION['mensaje_error'] = "ERROR: El usuario ya existe con este nombre de usuario en la base de datos";
                # Redirigimos al usuario a la página de registro
                header("Location:../views/registro.php");
                exit();
            }

              # SI el resultado de check_user() es FALSE  
          }else{
              # SI se produjo una excepción durante el proceso de comprobación
              if($exception_error == true){
                  # Se redirige al usuario a la página de error 500
                  header('Location:../../views/errors/error500.php');
                  exit(); 
              # SI el usuario NO existe
              }else{
                  # Se prepara la sentecia SQL para realizar la inserción
                  $insert_stmt = $mysqli_connection -> prepare("INSERT INTO users_data(nombre, apellidos, email, telefono, fecha_nacimiento, direccion, sexo) VALUES (?, ?, ?, ?, ?, ?, ?)");

                  # SI la sentencia NO se ha podido preparar
                  if(!$insert_stmt){
                      # Se guarda el error de preparación de la sentencia
                      error_log("No se pudo preparar la sentencia " . $mysqli_connection -> error);
                      
                      # Se redirige al usuario a la página de error 500
                      header('Location:../../views/errors/error500.php');
                      exit();
                  # SI la sentencia se ha podido preparar   
                  }else{
                      # Vinculamos los valores introducidos por el usuario a los valores de la sentencia de inserción
                      $insert_stmt->bind_param("sssssss", $name, $surname, $email, $phone, $birthday, $address, $gender);

                      # SI la sentencia se ha podido ejecutar
                      if($insert_stmt -> execute()){

                          ## DESDE AQUI REALIZO LA INSERCCION DE LA SEGUNDA TABLA
                          
                              #realizamos una consulta de seleccion con idUser para la inserción de la tabla user_login.
                              $select_stmt_data = $mysqli_connection -> prepare("SELECT idUser FROM users_data WHERE email = ?");
                              # SI la sentencia NO se ha podido preparar
                              if(!$select_stmt_data){
                                  # Se guarda el error de preparación de la sentencia
                                  error_log("No se pudo preparar la sentencia " . $mysqli_connection -> error);
                                  
                                  # Se redirige al usuario a la página de error 500
                                  header('Location:../../views/errors/error500.php');
                                  exit();
                                  # SI la sentencia se ha podido preparar   
                                }else{
                                          # Vinculamos los valores introducidos por el usuario a los valores de la sentencia de inserción
                                          $select_stmt_data->bind_param("s", $email);
                                          # SI la sentencia se ha podido ejecutar
                                          if($select_stmt_data -> execute()){
                                              # si se puede ejecutatar cogemos el resultado.
                                              $result = $select_stmt_data -> get_result();
                                              while($row = $result -> fetch_assoc()){
                                                  $idUser = $row['idUser'];
                                              }
                                       
                                              #Realizamos la insercion de la segunda tabla
                                              $insert_stmt_login = $mysqli_connection -> prepare("INSERT INTO users_login(idUser, usuario, password, rol) VALUES (?, ?, ?, ?)");
                                              # SI la sentencia NO se ha podido preparar
                                              if(!$insert_stmt_login){
                                                  # Se guarda el error de preparación de la sentencia
                                                  error_log("No se pudo preparar la sentencia " . $mysqli_connection -> error);
                                                  
                                                  # Se redirige al usuario a la página de error 500
                                                  header('Location:../../views/errors/error500.php');
                                                  exit();
                                                    # SI la sentencia se ha podido preparar   
                                                }else{
                                                  # Vinculamos los valores introducidos por el usuario a los valores de la sentencia de inserción
                                                  $insert_stmt_login->bind_param("ssss", $idUser, $user, $contrasena, $rol);

                                                  # SI la sentencia se ha podido ejecutar
                                                  if($insert_stmt_login -> execute()){
                                                      # Cerramos la sentencia
                                                      $insert_stmt_login -> close();
                                                      # Configuramos un mensaje de éxito para el usuario y le redirigimos a la página de admin_users.php.
                                                      $_SESSION['mensaje_exito'] = "EXITO: El usuario se ha creado correctamente";
                                                      header("Location:../../controllers/c_admin/c_read_admin.php");
                                                      exit();
                                                        # SI NO se ha podido ejecutar la sentencia    
                                                    }else{
                                                      # Se guarda el error de ejecución en el error_log
                                                      error_log("Error: " . $insert_stmt_login -> error);

                                                      #REALIZAMOS EL BORRADO DE LA PRIMERA TABLA

                                                      $delete_stmt = $mysqli_connection -> prepare("DELETE * FROM users_data WHERE idUser = ?");
                                                      # SI la sentencia NO se ha podido preparar
                                                      if(!$delete_stmt){
                                                          # Se guarda el error de preparación de la sentencia
                                                          error_log("No se pudo preparar la sentencia " . $mysqli_connection -> error);
                                                          # Se redirige al usuario a la página de error 500
                                                          header('Location:../../views/errors/error500.php');
                                                          exit();
                                                        }else{
                                                          # Vinculamos los valores introducidos por el usuario a los valores de la sentencia de eliminación
                                                          $delete_stmt->bind_param("s", $idUser);

                                                          # SI la sentencia se ha podido ejecutar
                                                          if($delete_stmt -> execute()){
                                                              # Cerramos la sentencia
                                                              $delete_stmt -> close();
                                                              #Establecemos un mensaje de error en la sesión
                                                              $_SESSION['mensaje_error'] = "ERROR: El usuario no se ha creado correctamente";
                                                              # Redirigimos al usuario a la página de admin_users.php
                                                              header('Location:../../views/admin/admin_users.php');
                                                              exit();
                                                            }
                                                          
                                                          #FIN DEL REGISTRO DE LA SEGUNDA TABLA

                                                        }
                                                  
                                                    }
                                                }   
                                            }
                                }
                          
                          
                              
                            #SI NO se ha podido ejecutar la sentencia    
                        }else{
                          # Se guarda el error de ejecución en el error_log
                          error_log("Error: " . $insert_stmt -> error);
                          # Redirigimos al usuario a la página de admin_users.php
                          header("Location:../../views/admin/admin_users.php");
                          exit();
                        }
                  }
              }

          }
      # SI durante el proceso surge una excepción    
      }catch(Exception $e){
          # Registramos la excepción en el error_log
          error_log("Error en c_create_admin.php" . $e -> getMessage());
          # Redirigimos al usuario a la página de error 500
          header('Location:../../views/errors/error500.php');
      
      # Independientemente de si se genera una excepción o no al final siempre se realizará el siguiente código
      }finally{
          # Cerramos la consulta si aún sigue abierta
          if(isset($insert_stmt) && ($insert_stmt)){
              $insert_stmt -> close();
          }

          # Cerramos la consulta si aún sigue abierta
          if(isset($select_stmt_data) && ($select_stmt_data)){
              $select_stmt_data -> close();
          }
          

          # Cerramos la conexión a la base de datos si aún sigue abierta
          if(isset($mysqli_connection) && ($mysqli_connection)){
              $mysqli_connection -> close();
          }

      }

}
