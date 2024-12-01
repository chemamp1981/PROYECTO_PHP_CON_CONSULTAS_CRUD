<?php
require_once '../db_conn.php';
require_once '../db_functions.php';
require_once '../../config/config.php';
require_once '../validations/v_inputData.php';

#Comprobamos si existe una sesion activa y en caso de que no sea asi la creamos
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

#RECUPERAR EL ID DEL USUARIO, PARA CREAR NOTICIA DEL USUARIO SELECIONADO.

#Redirigir al usuario indicado para la creación de Noticias.
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_create'])){
    #Obtener el id del usuario 
    $idUser = htmlspecialchars($_POST['idUser']);

    #EDITAR LAS NOTICIAS POR EL ADMINISTRADOR.
    #Intentamos editar la edición de crear la noticias por medio de cada usuario.
    try{
        # Inicializamos una variable para guardar los errores de excepcion posibles
        $exception_error = false;

        #Extraer los datos de los usuarios por el id a traves de la siguiente función:
        $users = get_users_by_id($idUser, $mysqli_connection, $exception_error);
                        
        # Comprobar si se ha capturado alguna excepción
        if($exception_error){
            # Redirigimos a la página de error que tengamos configurada
            $_SESSION['mensaje_error'] = "Error durante el proceso de extración de los datos de los usuarios. Inténtelo de nuevo más tarde o si le sigue sucediendo contacte con el equipo de soporte";
            header("location:../../views/errors/error500.php");
            exit();
        }
      
        if($users){
            
            #creamos una sesión para la creación de noticias.
            $_SESSION['usuarios'] = $users;
            header('location:../../views/admin/admin_noticias.php?create=ok&id='.$users[0]['idUser']);
            exit();
           
        }else{
            $_SESSION['mensaje_error'] = " Los datos de los usuarios no se han podido extraer. ";
            header('location:../../views/admin/admin_noticias.php');
            exit();
        }

    }catch(Exception $e){
        error_log("Error durante el proceso de extración de los datos de los usuarios. " . $e -> getMessage());
        header("Location:../../views/errors/error500.php");
        exit();
    }finally{
        # Cerrar la conexión a la base de datos si aún sigue abierta
        if(isset($mysqli_connection) && ($mysqli_connection)){
            $mysqli_connection -> close();
        }
    }

}

#crear la noticia de cada usuario
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_news'])){
   
   #Comprobar si los campos estan vacios.
    if(empty($_POST['ftitle']) || empty($_POST['ftext']) || empty($_POST['fdate'])  ){
       
        $_SESSION['mensaje_error'] = " Todos los campos deben estar completados. ";
        header('location:../../views/admin/admin_noticias.php?create=ok');
        exit();
    }else{

        #Recuperar datos del formulario crear citas.
        $title = htmlspecialchars($_POST['ftitle']);
        $textarea = htmlspecialchars($_POST['ftext']);
        $create_date = htmlspecialchars($_POST['fdate']);
        $idUser = htmlspecialchars($_POST['idUser']);
      
        #Crear las variables del dato del fichero.
        $img = $_FILES['fimage']; //No le he aplicado ningún filtro (htmlspecialchars) ya que me da un error de string. Que filtro se podria utilizar aquí?
        $origen = $img['tmp_name'];
        $nombreFichero = basename($img['name']);//He aplicado el basename(), Según he entendido desde la pagina de PHP oficial, para evitar un ataque transversal.
        $destino = "../../assets/images/sql_img/" . $nombreFichero;

        #validar el formulario a traves de la función validar_noticias().
        $errores_validacion = validar_noticias($title, $img, $textarea, $create_date);

        #Comprobar si se han generado errores de validacion o no.
            if(!empty($errores_validacion)){
                # Si hay errores los guardamos en una cadena de caracteres que mostraremos al usuario
                $mensaje_error = "";

                foreach($errores_validacion as $clave => $mensaje){
                    $mensaje_error .= $mensaje . "<br>";
                }

                # Asignamos la cadena de caracteres con los errores a $_SESSION['mensaje_error']
                 $_SESSION['mensaje_error'] = $mensaje_error;
                 header('location:../../views/admin/admin_noticias.php?create=ok');
                 exit();
                 
            }
              
            #Se comprueba los formatos de los ficheros admitidos.
            if(isset($img['type']) && $img['type'] == "image/jpeg" || $img['type'] == "image/jpg" || $img['type'] == "image/png"){
                
                #comprobar que no hay ningun error.
                if(isset($img['error']) && $img['error'] == 0){
                    #Si no hay errores al recibir el archivo, se guardan en la carpeta de destino.
                    if(move_uploaded_file($origen, $destino)){

                        #Se crea la sentencia de inserción de los datos en la base de datos.
                        try{
                        
                            # Inicializar la sentencia de Inserción como nula.
                            $insert_stmt = null;

                            #Inicializamos una variable para guardar los errores de excepción  posibles.
                            $exception_error = false;

                            #Insertar Noticias creadas
                            $query = "INSERT INTO noticias (titulo, imagen, texto, fecha, idUser) VALUES (?, ?, ?, ?, ?)";

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
                                $insert_stmt -> bind_param("sssss", $title, $nombreFichero, $textarea, $create_date, $idUser);
                                
                                #Si la sentencia se ha podido ejecutar
                                if($insert_stmt -> execute()){

                                    #Extraer los datos de la tabla noticia para su lectura en la pagina de noticias administración.
                                    $newsData = get_news_and_alldata_by_idUser($idUser, $mysqli_connection, $exception_error);
                                    
                                    #Comprobar si se ha capturado alguna excepción.
                                    if($exception_error){

                                        #Redirigimos al usuario a la pagina de error500.
                                        $_SESSION['mensaje_error'] = "Error durante el proceso de extración de los datos en la tabla noticia.";
                                        header('location:../../views/errors/error500.php');
                                        exit();
                                    }

                                        #Reiniciamos la variable de sesión.
                                        
                                        #Si se extraen los datos de la tabla noticias.
                                        if($newsData){
                                           
                                            #Se guardan los datos en una variable de sesión.
                                            $_SESSION['data_news'] = $newsData;
                                            $_SESSION['mensaje_exito'] = " La noticia se ha insertado correctamente. "; 
                                            #Se redirige al usuario a la pagina de admin noticias.
                                            
                                            header('location:../../views/admin/admin_noticias.php?news=ok&id='.$idUser);
                                            exit();
                                        
                                    
                                        #Si no se han extraido los datos de la tabla noticia.
                                        }else{

                                            #Redirigimos al usuario a la pagina de noticias con el siguiente mensaje.
                                            $_SESSION['mensaje_error'] = " Actualmente no hay ninguna noticia.";
                                            header('Location:../../views/admin/admin_noticias.php?news=ok');
                                            exit();
                                        }

                                }else{
                                    # Se guarda el error de ejecución en el error_log
                                    error_log("Error: La sentencia no se ha ejecutado " . $insert_stmt -> error);
                                    
                                    # Si la cita no se ha creado correctamente un mensaje de error
                                    $_SESSION['mensaje_error'] = "Error: La noticia no se ha creado.";
                                    header("Location:../../views/admin/admin_noticias.php");
                                    exit();
                                }
                            }   

                        #Si durante en el proceso surge una excepción 
                        }catch(Exception $e){

                            #Registramos la excepción en el error_log
                            error_log("Error en c_crear_noticias_admin.php " .$e -> getMessage());
                            #Redirigimos al usuario a la pagina de error 500
                            header('location:../../views/errors/error500.php');
                        }finally{
                            # Cerramos la consulta si aún sigue abierta
                            if($insert_stmt !== null){
                                $insert_stmt -> close();
                            }
                        }
                    }       
                } 
            }else{
                #Mensaje de error de formato.
                $_SESSION['mensaje_error'] = " Debe de seleccionar una archivo.";
                header("Location:../../views/admin/admin_noticias.php?create=ok&id='.$idUser");
                exit();
            }
    }
}