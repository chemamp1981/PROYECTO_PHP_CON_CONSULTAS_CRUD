<?php

require_once '../db_conn.php';
require_once '../db_functions.php';
require_once '../../config/config.php';
require_once '../validations/v_inputData.php';


#Comprobamos si existe una sesion activa y en caso de que no sea asi la creamos
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

#Comprobar si el dato se recibe.
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edNews'])){
    #Recoger el dato en la siguiente variable.
    $idNews = htmlspecialchars($_POST['idNews']);


    #LEER CADA NOTICIA A TRAVES DEL IDNOTICIA
    #Intento la lectura de la tabla noticias cogiendolo desde idNoticia.
    try{
        #Inicializo una variable para guardar los errores de excepción posibles.
         $exception_error = false;

        #Extraer los datos de noticias a traves de la siguiente función.
        $eachNews = get_news_by_idNoticia($idNews, $mysqli_connection, $exception_error);

        #Comprobar si se ha capturado alguna excepción.
        if($exception_error){
            #Redirigijo a la pagina de error que tengo configurada.
            $_SESSION['mensaje_error'] = "Error durante el proceso de extración de los datos.";
            header('location:../../views/errors/error500.php');
            exit();
        }

        #Comprobar la variable de extracion de los datos.
        if(is_array($eachNews) && !empty($eachNews)){
            #Creo una sesión para esta variable.
            $_SESSION['each_news'] = $eachNews;
            header('location:../../views/admin/admin_noticias.php?update_news=ok&idN='.$eachNews[0]['idNoticia']);
            exit();
        }
        #Si se ha capturado una excepción mostrarlo con un error_log.
    }catch(Exception $e){
        error_log("Error durante el proceso de extración de los datos de noticias. ".$e->getMessage());
        header("location:../../views/errors/error500.php");
    }finally{
        #Comprobar si la conexión esta cerrada y si no lo esta cerrarla.
        if(isset($mysqli_connection) && ($mysqli_connection)){
            $mysqli_connection->close();
        }
    }


}


#PREPARO EL SCRIPT PARA LA ACTUALIZACIÓN DE NOTICIAS
#Compruebo si recibo la variable de los datos del formulario de actualización.
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_news'])){

    #PARA ACTUALIZAR LOS DATOS DE NOTICIAS DIVIDO EL CODIGO EN DOS PARTES, SI EL FICHERO EXISTE:
    
        #Recoger los datos y filtrarlos para su actualización.
        $title = htmlspecialchars($_POST['ftitle']);
        $text = htmlspecialchars($_POST['ftext']);
        $create_date = htmlspecialchars($_POST['fdate']);
        $idNoticia = htmlspecialchars($_POST['idNoticia']);
        $idUser = htmlspecialchars($_POST['idUser']);

         #Recoger el dato de File y prepararlo para su actualización.
         $img = $_FILES['fimage'];
         $origen = $img['tmp_name'];
         $nombreFichero = basename($img['name']);
         $destino =  "../../assets/images/sql_img/" . $nombreFichero;
    

        #Validar los datos a traves de la siguiente función.
        $errores_validacion = validar_noticias($title, $img, $text, $create_date);

        #Compruebo si se han generado errores de validación.
        if(!empty($errores_validacion)){
            #Si hay errores los guardo en una cadena de caracteres que se mostrara al usuario.
            $mensaje_error = "";
            foreach($errores_validacion as $clave => $mensaje){
                $mensaje_error .= $mensaje . "<br>"; 
            }

            #Asigno a la cadena una variable de sesión.
            $_SESSION['mensaje_error'] = $mensaje_error;
            header('location:../../views/admin/admin_noticias.php?update_news=ok');
            exit();
        }
        
    
        #Comprobar si se ha seleccionado un archivo 
        if(isset($img['error']) && $img['error'] !== 4){      

            #Se comprueba los formatos de los ficheros admitidos.
            if(isset($img['type']) && $img['type'] == "image/jpeg" || $img['type'] == "image/jpg" || $img['type'] == "image/png"){
            
                #comprobar que no hay ningun error.
                if(isset($img['error']) && $img['error'] == 0){
                    #Si no hay errores al recibir el archivo, se guardan en la carpeta de destino.
                    if(move_uploaded_file($origen, $destino)){

                        #Preparar el borrado de la imagen en la carpeta sql_img.
                        #Definir la actualización con la siguiente consulta SQL.
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
                                        
                                    #Extraer los datos actualizados en una variable a traves de la siguiente función.
                                    $updateNews = update_news_by_idNoticia($title, $nombreFichero, $text, $create_date, $idNoticia, $mysqli_connection, $exception_error);
                                
                                    #Comprobar si se ha capturado alguna excepción.
                                    if($exception_error){
                                        $_SESSION['mensaje_error'] = "No se pudo extraer los datos de actualización noticias.";
                                        header('location:../../views/errors/error500.php');
                                        exit();
                                    }
                                    
                                    #Compruebo la variable de extración de los datos y la guardo en una variable de sesión.
                                    if($updateNews){
                                        #Actualiza los datos de la sesión.
                                        $_SESSION['news']['titulo_news'] = $title;
                                        $_SESSION['news']['imagen_news'] = $nombreFichero;
                                        $_SESSION['news']['texto_news'] = $text;
                                        $_SESSION['news']['fecha_news'] = $create_date;

                                        #Mensaje de exito
                                        $_SESSION['mensaje_exito'] = "Los datos se han actualizado correctamente";
                                        //header('location:../../views/admin/admin_noticias.php?update_news=ok');
                                        //exit();
                                        
                                        #EXTRAER TODOS LOS DATOS DE NOTICIAS PARA SU VISUALIZACIÓN
                                        #Extraer los datos de la siguiente función.
                                        $newsData = get_news_and_alldata_by_idUser($idUser, $mysqli_connection, $exception_error);

                                        #Comprobar si se ha capturado alguna excepción
                                        if($exception_error){
                                            #Mensaje error y redirigir a la pagina error500.
                                            $_SESSION['error_mensaje'] = "Error al extraer los datos";
                                            header('location:../../views/errors/error500.php');
                                            exit();
                                        }
                                        #Comprobar la variable de extración de los datos y guardarla en una variable de sesión.
                                        if($newsData){
                                            $_SESSION['data_news'] = $newsData;
                                            header('location:../../views/admin/admin_noticias.php?news=ok&id='.$newsData[0]['idUser']);
                                            exit();

                                            #Si los datos no se han podido extraer.
                                        }else{
                                            #Mensaje error.
                                            $_SESSION['mensaje_error'] = "Actualmente no tiene niguna noticia publicada.";
                                            header('location:../../views/admin/admin_noticias.php?news=ok');
                                            exit();
                                        }

                                        #Si no se actualizán los datos por algun motivo, mensaje de error.
                                    }else{
                                        #Mensaje de error.
                                        $_SESSION['mensaje_error'] = "Error al extraer los datos de actualización";
                                        header('location:../../views/admin/admin_noticias.php?news=ok');
                                        exit();
                                    }
                                }
                            #Comprobar si se ha captuarado alguna excepción.
                        }catch(Exception $e){
                            #Mostrar mensaje  de error en el servidor.
                            error_log("Error al extraer los datos pra su actualización." . $e->getMessage());
                            header('location:../../views/errors/error500.php');
                            exit();
                        }finally{
                            #Si la conexion de la base de datos esta abierta, cerrar la conexión.
                            if(isset($mysqli_connection) && ($mysqli_connection)){
                                $mysqli_connection->close();
                            }

                            #Si la sentencia sigue abierta, cerrar la sentencia.
                            if(isset($select_stmt) && ($select_stmt)){
                                $select_stmt->close();
                            }
                        }
                        
                    }
                }
            }
            
        }else{

                #Definino la actualización con la siguiente consulta SQL.
                try{
                    #Definno una variable para guardar los posibles errores de excepción.
                    $exception_error = false;

                    #Extraer los datos actualizados en una variable a traves de la siguiente función.
                    $updateNews = update_news_whitout_image_by_idNoticia($title,$text, $create_date, $idNoticia, $mysqli_connection, $exception_error);
                
                    #Comprobar si se ha capturado alguna excepción.
                    if($exception_error){
                        $_SESSION['mensaje_error'] = "No se pudo extraer los datos de actualización noticias.";
                        header('location:../../views/errors/error500.php');
                        exit();
                    }
                    
                    #Compruebo la variable de extración de los datos y la guardo en una variable de sesión.
                    if($updateNews){
                        #Actualiza los datos de la sesión.
                        $_SESSION['news']['titulo_news'] = $title;
                        $_SESSION['news']['texto_news'] = $text;
                        $_SESSION['news']['fecha_news'] = $create_date;

                        #Mensaje de exito
                        $_SESSION['mensaje_exito'] = "Los datos se han actualizado correctamente";
                        //header('location:../../views/admin/admin_noticias.php?update_news=ok');
                        //exit();
                        
                        #EXTRAER TODOS LOS DATOS DE NOTICIAS PARA SU VISUALIZACIÓN
                        #Extraer los datos de la siguiente función.
                        $newsData = get_news_and_alldata_by_idUser($idUser, $mysqli_connection, $exception_error);

                        #Comprobar si se ha capturado alguna excepción
                        if($exception_error){
                            #Mensaje error y redirigir a la pagina error500.
                            $_SESSION['error_mensaje'] = "Error al extraer los datos";
                            header('location:../../views/errors/error500.php');
                            exit();
                        }
                        #Comprobar la variable de extración de los datos y guardarla en una variable de sesión.
                        if($newsData){
                            $_SESSION['data_news'] = $newsData;
                            header('location:../../views/admin/admin_noticias.php?news=ok&id='.$newsData[0]['idUser']);
                            exit();

                            #Si los datos no se han podido extraer.
                        }else{
                            #Mensaje error.
                            $_SESSION['mensaje_error'] = "Actualmente no tiene niguna noticia publicada.";
                            header('location:../../views/admin/admin_noticias.php?news=ok');
                            exit();
                        }

                        #Si no se actualizán los datos por algun motivo, mensaje de error.
                    }else{
                        #Mensaje de error.
                        $_SESSION['mensaje_error'] = "Error al extraer los datos de actualización";
                        header('location:../../views/admin/admin_noticias.php?news=ok');
                        exit();
                    }
                    #Comprobar si se ha captuarado alguna excepción.
                }catch(Exception $e){
                    #Mostrar mensaje  de error en el servidor.
                    error_log("Error al extraer los datos pra su actualización." . $e->getMessage());
                    header('location:../../views/errors/error500.php');
                    exit();
                }finally{
                    #Si la conexion de la base de datos esta abierta, cerrar la conexión.
                    if(isset($mysqli_connection) && ($mysqli_connection)){
                        $mysqli_connection->close();
                    }
                }

        }
                 
}