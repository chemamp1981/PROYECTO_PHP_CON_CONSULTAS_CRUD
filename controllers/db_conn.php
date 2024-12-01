<?php
//PDO-->MYSQL, POSRGRESQL, SQLITE
//mysqli --> MYSQL SERVER (MARIADB)

#Incluir/vincular los parámetros de conexión
require_once '.env.php';
require_once __DIR__. '/../config/config.php';

#Definimos una función para realizar la conexión a la BBDD
function connectToDatabase() {
    #Crear una variable de conexión
    Static $mysqli_conn = null; // con la palabra Static mantendra el ultimo valor con las diferentes llamadas a esta función.
    
    if($mysqli_conn === null){
        try{
            #Crear la conexion a la BBDD
            $mysqli_conn = new mysqli(SERVER_HOST,USER,PASSWORD,DATABASE_NAME);

            #Comprobar que la conxión se haya realizado correctamente
            if($mysqli_conn -> connect_errno){
                #Registrar el error en un archivo log
                error_log("Fallo al conectar a la base de datos. Error: " . $mysqli_conn -> connect_error);
                return null;
            }else{
                echo "La conexión ha funcionado correctamente";
            }

        }catch(Exception $e){
            #Registrar la exepción en el archivo log
            error_log("Error de conexión a la base de datos: " . $e->getMessage());
            return null;
        }
    }

    return $mysqli_conn;

}

$mysqli_connection = connectToDatabase(); //$mysqli_conn o null

if($mysqli_connection === null){
    $_SESSION['mensaje_error'] = "Error de la conexión";
    header('Location:../views/errors/error500.php');
}

