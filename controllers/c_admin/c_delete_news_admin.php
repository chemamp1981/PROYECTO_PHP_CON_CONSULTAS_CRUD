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
    $idNoticia = htmlspecialchars($_POST['idNoticia']);
    $idUser = htmlspecialchars($_POST['idUser']);
  
  
    #Intentamos el borrado de cada noticia.
    #Realizo una consulta para extraer el nombre de la imagen y guardarla en una variable, para poder eliminarla en el directorio.

  try{
        
        #Inicializo con la siguiente variable.
        $select_stmt = null;
        # Inicializamos una variable para guardar los errores de excepcion posibles
        $exception_error = false;
        #Preparar la sentencia SQL para recogen el nombre de la imagen.
        $query = "SELECT imagen FROM noticias WHERE idNoticia = ?";

        #Preparo la sentencia
        $select_stmt = $mysqli_connection->prepare($query);

        #comprobar posible error
        if($select_stmt === false){
            error_log("No se pudo preparar la sentencia" . $mysqli_connection->error);
            $exception_error = true;
            return false;
        }

        #Vincular idNoticia a la sentencia.
        $select_stmt->bind_param('s', $idNoticia);

        #Intentar ejecutar la sentencia de selección
        if(!$select_stmt->execute()){
            error_log("La sentencia no se podido ejecutar" . $mysqli_connection->error);
            $exception_error = true;
            return false;
        }else{
             #Obtener el resultados de la consulta
               # si se puede ejecutatar cogemos el resultado.
               $result = $select_stmt -> get_result();
               while($row = $result -> fetch_assoc()){
                   $image = $row['imagen'];
               }  
                    #Procedemos al borrado de la imagen en carpeta sql_img.
                    $ruta_img ="../../assets/images/sql_img/";
                    if ( !is_writable($ruta_img) ) { 
                         chmod($ruta_img, 0777);
                         if(file_exists($ruta_img)){
                            unlink($ruta_img.$image);
                         }
                    } else { 
                         if(file_exists($ruta_img)){
                            unlink($ruta_img.$image);
                         }
                    } 
              
            
            #Guardamos en una variable el borrado de la tabla noticias, con la siguiente función.
            $deleteNews = delete_news_by_idNoticia($idNoticia, $mysqli_connection, $exception_error);
        
            # Comprobar si se ha capturado alguna excepción
            if($exception_error){
                # Redirigimos a la página de error que tengamos configurada
                $_SESSION['mensaje_error'] = "Error al extraer los datos de borrado.";
                header("Location:../../views/errors/error500.php");
                exit();
            }

            #Comprobamos la variable de borrado.
            if($deleteNews){
             
                #creamos variable de sesión
               // $_SESSION['delete_news'] = $deleteNews;
                
                #Mensaje de exito 
                $_SESSION['mensaje_exito'] = " La noticia se ha Borrado correctamente.";

                #Extraer los datos de las noticias a traves de la siguiente función:
                $newsData = get_news_and_alldata_by_idUser($idUser, $mysqli_connection, $exception_error);

                                
                # Comprobar si se ha capturado alguna excepción
                if($exception_error){
                        # Redirigimos a la página de error que tengamos configurada
                        $_SESSION['mensaje_error'] = "Error durante el proceso de extración de todos los datos de noticias y los datos del usuario.";
                        header("location:../../views/errors/error500.php");
                        exit();
                }
                unset($_SESSION['data_news']);
                #Comprobar la variable de extración de los datos y guardarla en una variable de sesión.
                if($newsData){
                    $_SESSION['data_news'] = $newsData;
                    header('location:../../views/admin/admin_noticias.php?news=ok&id='.$newsData[0]['idUser']);
                    exit();

                        #Si los datos no se han podido extraer.
                }else{
                    if(isset($_SESSION['mensaje_exito'])){
                        $mensaje = $_SESSION['mensaje_exito'];
                        unset($_SESSION['mensaje_exito']);
                        $_SESSION['mensaje_error'] = $mensaje . " Actualmente no hay ninguna noticia publicada.";
                    }else{

                        #Mensaje de advertencia.
                        $_SESSION['mensaje_error'] = " Actualmente no tiene niguna noticia publicada.";

                    }
                    #Mensaje de advertencia y redirigira la usuario a la pagina admin_noticias.php.
                    $_SESSION['mensaje_error'] = " Actualmente no tiene niguna noticia publicada.";
                    header('location:../../views/admin/admin_noticias.php');
                    exit();
                }

            }else{
                # Si no hay Noticias para leer mensaje de Advertencia.
                $_SESSION['mensaje_error'] = "El borrado no se ha realizado correctamente.";
                header('location:../../views/admin/admin_noticias.php?news=ok');
                exit();
            }
        }

    }catch(Exception $e){
        error_log("Error durante el proceso de borrado del usuario. " . $e -> getMessage());
        header("Location:../../views/errors/error500.php");
        exit();
    }finally{
         # Cerramos la consulta si aún sigue abierta
         if(isset($select_stmt) && ($select_stmt)){
            $select_stmt -> close();
        }
        # Cerrar la conexión a la base de datos si aún sigue abierta
        if(isset($mysqli_connection) && ($mysqli_connection)){
            $mysqli_connection -> close();
        }
    }
}