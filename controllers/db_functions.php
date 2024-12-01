<?php

#Vincular los archivos necesarios
require_once __DIR__ . '/../config/config.php';

#Definir una función para comprobar si existe un usuario en la BBDD
function check_user_email($email, $mysqli_connection, &$exception_error)
{
    # Declaramos la sentencia $select_stmt como nula y luego trabajamos sobre ella
    # para prevenir errores y gestionar de forma más correcta la gestión de excepciones
    $select_stmt = null;

    try {
        #Preparar la sentencia para buscar el email en la BBDD
        $select_stmt = $mysqli_connection->prepare('SELECT email FROM users_data WHERE email = ?');

        #Comprobamos si la sentencia se ha podido preparar correctamente
        if ($select_stmt === false) {
            error_log("No se pudo ejecutar la sentencia " . $mysqli_connection->error);
            $exception_error = true;
            return false;
        }

        #Vinculamos el email a la sentencia
        $select_stmt->bind_param('s', $email);

        #Comprobar si se puede ejecutar la sentencia una vez preparada
        if (!$select_stmt->execute()) {
            error_log("No se pudo ejecutar la sentencia: " . $select_stmt->error);
            $exception_error = true;
            return false;
        }

        #Ejecutamos la consulta
        $select_stmt->execute();

        #Guardamos el resultado de la sentencia tras su ejecutión 
        $select_stmt->store_result();

        #Comprobamos el resultado generado para saber si el email existe en la BBDD
        $result_exist = $select_stmt->num_rows > 0;
        return $result_exist;

    }catch(Exception $e){
        #Añadir la excepción producida al log 
        error_log("Error en la función check_user_email(): " . $e->getMessage());
        $exception_error = true;
        return false;

    } finally {
        if ($select_stmt !== null) {
            $select_stmt->close();
        }
    }
}

function check_user_usuario($user, $mysqli_connection, &$exception_error)
{
    # Declaramos la sentencia $select_stmt como nula y luego trabajamos sobre ella
    # para prevenir errores y gestionar de forma más correcta la gestión de excepciones
    $select_stmt = null;

    try {
        #Preparar la sentencia para buscar el email en la BBDD
        $select_stmt = $mysqli_connection->prepare('SELECT usuario FROM users_login WHERE usuario = ?');

        #Comprobamos si la sentencia se ha podido preparar correctamente
        if ($select_stmt === false) {
            error_log("No se pudo ejecutar la sentencia " . $mysqli_connection->error);
            $exception_error = true;
            return false;
        }

        #Vinculamos el email a la sentencia
        $select_stmt->bind_param('s', $user);

        #Comprobar si se puede ejecutar la sentencia una vez preparada
        if (!$select_stmt->execute()) {
            error_log("No se pudo ejecutar la sentencia: " . $select_stmt->error);
            $exception_error = true;
            return false;
        }

        #Ejecutamos la consulta
        $select_stmt->execute();

        #Guardamos el resultado de la sentencia tras su ejecutión 
        $select_stmt->store_result();

        #Comprobamos el resultado generado para saber si el email existe en la BBDD
        $result_exist = $select_stmt->num_rows > 0;
        print_r($result_exist);
        return $result_exist;
        
    }catch(Exception $e){
        #Añadir la excepción producida al log 
        error_log("Error en la función check_user_usuario(): " . $e->getMessage());
        $exception_error = true;
        return false;

    } finally {
        if ($select_stmt !== null) {
            $select_stmt->close();
        }
    }
}


# Función para coger los datos de la tabla user_login a traves de user
function get_userLogin_by_user($usuario, $mysqli_connection, &$exception_error)
{
    # Inicializar la sentencia de selección como nula
    $select_stmt = null;
    # Inicializamos la variable de error asumiendo que inicialmente no hay ningún error
    $exception_error = false;

    try {
        # Preparar la sentencia SQL necesaria para buscar los datos del login a través del usuario. 
        $query = "SELECT * FROM users_login WHERE usuario = ? ";
        
        $select_stmt = $mysqli_connection->prepare($query);
     

        if ($select_stmt === false) {
            error_log("No se pudo preparar la sentencia " . $mysqli_connection->error);
            $exception_error = true;
            return false;
        }

        # Vincular el idUser a la sentencia
        $select_stmt->bind_param('s', $usuario);

        # Intentar ejecutar la sentencia de selección
        if (!$select_stmt->execute()) {
            error_log("No se puede ejecutar la sentencia " . $mysqli_connection->error);
            $exception_error = true;
            return false;
        }

        # Obtener el resultado de la consulta
        $result = $select_stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc(); # fetch_assoc() nos permite obtener los datos del resultado como un array asociativo (clave: valor)

            return $user;
        } else {
            // Si no se encuentra el usuario o no existe
            return false;
        }

    } catch (Exception $e) {
        error_log("Error al ejecutar la función get_userLogin_by_user(): " . $e->getMessage());
        $exception_error = true;
        return false;

    } finally {
        // Nos aseguramos de cerrar la sentencia si existe
        if ($select_stmt !== null) {
            $select_stmt->close();
        }

    }

}
 #Función para coger los datos de la tabla user_data y users_login a través de idUser
function get_userData_and_userLogin_by_id($idUser, $mysqli_connection, &$exception_error)
{
    # Inicializar la sentencia de selección como nula
    $select_stmt = null;
    # Inicializamos la variable de error asumiendo que inicialmente no hay ningún error
    $exception_error = false;

    try {
        # Preparar la sentencia SQL necesaria para buscar al usuario a través de su idUser
        $query = "SELECT * FROM users_data ud JOIN users_login ul ON (ud.idUser = ul.idUser) WHERE ud.idUser = ?";

        $select_stmt = $mysqli_connection->prepare($query);


        if ($select_stmt === false) {
            error_log("No se pudo preparar la sentencia " . $mysqli_connection->error);
            $exception_error = true;
            return false;
        }

        # Vincular el idUser a la sentencia
        $select_stmt->bind_param('s', $idUser);

        # Intentar ejecutar la sentencia de selección
        if (!$select_stmt->execute()) {
            error_log("No se puede ejecutar la sentencia " . $mysqli_connection->error);
            $exception_error = true;
            return false;
        }

       # Obtener el resultado de la consulta
        $result = $select_stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc(); # fetch_assoc() nos permite obtener los datos del resultado como un array asociativo (clave: valor)

            return $user;
    
        } else {
            // Si no se encuentra el id o no existe
            return false;
        }

    } catch (Exception $e) {
        error_log("Error al ejecutar la función get_userData_und_userLogin_by_id(): " . $e->getMessage());
        $exception_error = true;
        return false;

    } finally {
        // Nos aseguramos de cerrar la sentencia si existe
        if ($select_stmt !== null) {
            $select_stmt->close();
        }

    }

}


#Funcioón que permite actualizar todo el usuario, incluyendo el password.
function update_user_alldata($name, $surname, $email, $phone, $birthday, $address, $gender, $user, $pass, $idUser, $mysqli_connection, &$exception_error)
{
    # Inicializar la senencia de selección como nula
    $update_stmt = null;

    # Inicializamos la variable de error asumiendo que inicialmente no hay ningún error
    $exception_error = false;

    #Evitar inyecciones SQL
    $newName = $mysqli_connection -> real_escape_string($name);
    $newSurname = $mysqli_connection -> real_escape_string($surname);
    $newEmail = $mysqli_connection -> real_escape_string($email);
    $newPhone = $mysqli_connection -> real_escape_string($phone);
    $newBirthday = $mysqli_connection -> real_escape_string($birthday);
    $newAddress = $mysqli_connection -> real_escape_string($address);
    $NewGender = $mysqli_connection -> real_escape_string($gender);
    $newUser = $mysqli_connection -> real_escape_string($user);
    $newPassword = $mysqli_connection -> real_escape_string($pass);

    try{

        #Crear consulta de actualización
        $query = "UPDATE users_data ud INNER JOIN users_login ul ON (ud.idUser = ul.idUser) SET nombre = ?, apellidos = ?, email = ?, telefono = ?, fecha_nacimiento = ?, direccion = ?, sexo = ?, usuario = ?, password = ?  WHERE ud.idUser = ? AND ul.idUser = ?";

        #Preparar la sentencia SQL
        $update_stmt = $mysqli_connection -> prepare($query);
        if($update_stmt === false){
            error_log("No se pudo preparar la sentencia" . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }

        #Vincular los parametros
        $update_stmt -> bind_param("sssssssssss", $newName, $newSurname, $newEmail, $newPhone, $newBirthday, $newAddress, $NewGender, $newUser, $newPassword, $idUser, $idUser);
        
        #Intentar ejecutar la sentencia de actualización.
        if(!$update_stmt -> execute()){
            error_log("No se puede ejecutar la sentencia " . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }
        #Ejecutar la sentencia de actualización 
        $resultado =  $update_stmt -> execute();

        #comprobar si la actualización se ha realizado bien
        if($resultado){
            return true;
        }else{
            return false;
        }
    }catch(Exception $e){
        error_log("Error al ejecutar la función update_user_alldata(): " . $e -> getMessage());
        $exception_error = true;
        return false;
    }finally{
        // Nos aseguramos de cerrar la sentencia si existe
        if($update_stmt !== null){
            $update_stmt -> close();
        }

    }

    
}



#Funcioón que permite actualizar todo el usuario, incluyendo el password.
function update_user_without_password($name, $surname, $email, $phone, $birthday, $address, $gender, $user, $idUser, $mysqli_connection, &$exception_error)
{
    # Inicializar la senencia de selección como nula
    $update_stmt = null;

    # Inicializamos la variable de error asumiendo que inicialmente no hay ningún error
    $exception_error = false;
 
    #Evitar inyecciones SQL
    $newName = $mysqli_connection -> real_escape_string($name);
    $newSurname = $mysqli_connection -> real_escape_string($surname);
    $newEmail = $mysqli_connection -> real_escape_string($email);
    $newPhone = $mysqli_connection -> real_escape_string($phone);
    $newBirthday = $mysqli_connection -> real_escape_string($birthday);
    $newAddress = $mysqli_connection -> real_escape_string($address);
    $newGender = $mysqli_connection -> real_escape_string($gender);
    $newUser = $mysqli_connection -> real_escape_string($user);
 
    try{

        #Crear consulta de actualización
        $query = "UPDATE users_data ud INNER JOIN users_login ul ON (ud.idUser = ul.idUser) SET nombre = ?, apellidos = ?, email = ?, telefono = ?, fecha_nacimiento = ?, direccion = ?, sexo = ?, usuario = ? WHERE ud.idUser = ? AND ul.idUser = ?";

        #Preparar la sentencia SQL
        $update_stmt = $mysqli_connection -> prepare($query);
        if($update_stmt === false){
            error_log("No se pudo preparar la sentencia" . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }

        #Vincular los parametros
        $update_stmt -> bind_param("ssssssssss", $newName, $newSurname, $newEmail, $newPhone, $newBirthday, $newAddress, $newGender, $newUser, $idUser, $idUser);
        
        #Intentar ejecutar la sentencia de actualización.
        if(!$update_stmt -> execute()){
            error_log("No se puede ejecutar la sentencia " . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }
        #Ejecutar la sentencia de actualización 
        $resultado = $update_stmt -> execute();

        #comprobar si la actualización se ha realizado bien
        if($resultado){
            return true;
        }else{
            return false;
        }
    }catch(Exception $e){
        error_log("Error al ejecutar la función update_user_without_password(): " . $e -> getMessage());
        $exception_error = true;
        return false;
    }finally{
        // Nos aseguramos de cerrar la sentencia si existe
        if($update_stmt !== null){
            $update_stmt -> close();
        }

    }

}


#Función que permite leer los datos de citas.
function get_citas_by_idUser($idUser, $mysqli_connection, &$exception_error){
    #Inicializar la sentencia del selección como nula.
    $select_stmt = null;
    #Inicializamos la variable de error asumiendo que inicialmente no hay ningún error.
    $exception_error = false;

    try{
        #Preparar la sentencia SQL necesaria para coger todos los datos de cita a través del idUser.
        $query = "SELECT idCita, idUser, fecha_cita, motivo_cita, DATEDIFF(fecha_cita, CURRENT_DATE()) AS diferencia FROM citas WHERE idUser = ?";
        $select_stmt = $mysqli_connection -> prepare($query);
        
        #Si la sentencia no se ha realizado
        if(!$select_stmt){
            error_log("No se pudo preparar la sentencia" . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }

        #vincular el idCita a la sentencia.
        $select_stmt -> bind_param('s', $idUser);

        #Ejecutar la sentencia de seleccíon.
        if(!$select_stmt -> execute()){
            error_log("No se puede ejecutar la sentencia " . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }

        #Obtener el resultado de la consulta.
        $result = $select_stmt -> get_result();
        if($result -> num_rows > 0){
            $citas = []; 
            while($fila = $result ->fetch_assoc()){
                $citas[] = $fila;
            }
            #Devuelve todas las filas.
            return $citas;
           
        }else{
            #Si no se encuentra ninguna fila.
            return false;
        }
        
    }catch(Exception $e){
        error_log("Error al ejecutar la función get_citas_by_idUser(): " . $e -> getMessage());
        $exception_error = true;
        return false;
    }finally{
        #Nos aseguramos de cerrar la sentencia si existe.
        if($select_stmt !== null){
            $select_stmt -> close();
        }
    }
}

#Función que permite leer los datos de citas por idCita.
function get_cita_by_idCita($idCita, $mysqli_connection, &$exception_error){
    #Inicializar la sentencia del selección como nula.
    $select_stmt = null;
    #Inicializamos la variable de error asumiendo que inicialmente no hay ningún error.
    $exception_error = false;

    try{
        #Preparar la sentencia SQL necesaria para coger todos los datos de cita a través del idUser.
        $query = "SELECT * FROM citas  WHERE idCita = ?";
        $select_stmt = $mysqli_connection -> prepare($query);
        
        #Si la sentencia no se ha realizado
        if(!$select_stmt){
            error_log("No se pudo preparar la sentencia" . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }

        #vincular el idCita a la sentencia.
        $select_stmt -> bind_param('s', $idCita);

        #Ejecutar la sentencia de seleccíon.
        if(!$select_stmt -> execute()){
            error_log("No se puede ejecutar la sentencia " . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }

        #Obtener el resultado de la consulta.
        $result = $select_stmt -> get_result();
        if($result -> num_rows > 0){
            $cita = []; 
            while($fila = $result ->fetch_assoc()){
                $cita[] = $fila;
            }
            #Devuelve todas las filas.
            return $cita;
           
        }else{
            #Si no se encuentra ninguna fila.
            return false;
        }
        
    }catch(Exception $e){
        error_log("Error al ejecutar la función get_cita_by_idCita(): " . $e -> getMessage());
        $exception_error = true;
        return false;
    }finally{
        #Nos aseguramos de cerrar la sentencia si existe.
        if($select_stmt !== null){
            $select_stmt -> close();
        }
    }
}


#Funcioón que permite actualizar citas creadas por usuario a traves del idCita.
function update_citas_by_idCita($dateCita, $motivoCita, $idCita, $mysqli_connection, &$exception_error)
{
    # Inicializar la senencia de selección como nula
    $update_stmt = null;

    # Inicializamos la variable de error asumiendo que inicialmente no hay ningún error
    $exception_error = false;
 
    #Evitar inyecciones SQL
    $newDateCita = $mysqli_connection -> real_escape_string($dateCita);
    $newMotivoCita = $mysqli_connection -> real_escape_string($motivoCita);
 
    try{

        #Crear consulta de actualización
        $query = "UPDATE citas SET fecha_cita = ?, motivo_cita = ? WHERE idCita = ?";

        #Preparar la sentencia SQL
        $update_stmt = $mysqli_connection -> prepare($query);
        if($update_stmt === false){
            error_log("No se pudo preparar la sentencia" . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }

        #Vincular los parametros
        $update_stmt -> bind_param("sss",  $newDateCita, $newMotivoCita, $idCita);
        
        #Intentar ejecutar la sentencia de actualización.
        if(!$update_stmt -> execute()){
            error_log("No se puede ejecutar la sentencia " . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }
        #Ejecutar la sentencia de actualización 
        $resultado = $update_stmt -> execute();

        #comprobar si la actualización se ha realizado bien
        if($resultado){
            return true;
        }else{
            return false;
        }
    }catch(Exception $e){
        error_log("Error al ejecutar la función update_citas_by_idCita(): " . $e -> getMessage());
        $exception_error = true;
        return false;
    }finally{
        // Nos aseguramos de cerrar la sentencia si existe
        if($update_stmt !== null){
            $update_stmt -> close();
        }

    }

}

#Funcioón que permite el borrado de la cita a traves del idCita.
function delete_citas_by_idCita($idCita, $mysqli_connection, &$exception_error)
{
    # Inicializar la sentencia de eliminación como nula
    $delete_stmt = null;

    # Inicializamos la variable de error asumiendo que inicialmente no hay ningún error
    $exception_error = false;
 

 
    try{

        #Crear consulta de borrado
        $query = "DELETE FROM citas WHERE idCita = ?";

        #Preparar la sentencia SQL
        $delete_stmt = $mysqli_connection -> prepare($query);
        if($delete_stmt === false){
            error_log("No se pudo preparar la sentencia" . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }

        #Vincular los parametros
        $delete_stmt -> bind_param("s", $idCita);
        
        #Intentar ejecutar la sentencia de eliminación.
        if(!$delete_stmt -> execute()){
            error_log("No se puede ejecutar la sentencia " . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }
        #Ejecutar la sentencia de eliminación
        $resultado = $delete_stmt -> execute();

        #comprobar si el borrado se ha realizado bien
        if($resultado){
            return true;
        }else{
            return false;
        }
    }catch(Exception $e){
        error_log("Error al ejecutar la función delete_citas_by_idCita(): " . $e -> getMessage());
        $exception_error = true;
        return false;
    }finally{
        // Nos aseguramos de cerrar la sentencia si existe
        if($delete_stmt !== null){
            $delete_stmt -> close();
        }

    }

}


#Función que permite leer todos los datos de todos los usuarios.
function get_users($mysqli_connection, &$exception_error){
    #Inicializar la sentencia del selección como nula.
    $select_stmt = null;
    #Inicializamos la variable de error asumiendo que inicialmente no hay ningún error.
    $exception_error = false;

    try{
        #Preparar la sentencia SQL necesaria para coger los datos de todos los usarios a través del idUser.
        $query = "SELECT ud.idUser, ud.nombre, ud.apellidos, ud.email, ud.telefono, ud.fecha_nacimiento, ud.direccion, ud.sexo, ul.usuario, ul.password FROM users_data ud JOIN users_login ul ON (ud.idUser = ul.idUser)";
        
        $select_stmt = $mysqli_connection->prepare($query);
        #Si la sentencia no se ha realizado
        if(!$select_stmt){
            error_log("No se pudo preparar la sentencia" . $mysqli_connection -> error);
            $exception_error = true;
            return null;
        }

    
        #Ejecutar la sentencia de seleccíon.
        if(!$select_stmt -> execute()){
            error_log("No se puede ejecutar la sentencia " . $mysqli_connection -> error);
            $exception_error = true;
            return null;
        }

      #Obtener el resultado de la consulta.
      $result = $select_stmt -> get_result();
      if($result -> num_rows > 0){
          $users = []; 
          while($fila = $result ->fetch_assoc()){
              $users[] = $fila;
          }
        //  echo "Ey\n";
        //  print_r($users);
          #Devuelve todas las filas.
          return $users;
        }else{
            #No se encontraron resultados
            return [];
        }

    }catch(Exception $e){
        error_log("Error al ejecutar la función get_users(): " . $e -> getMessage());
        $exception_error = true;
        return null;
    }finally{
        #Nos aseguramos de cerrar la sentencia si existe.
        if($select_stmt !== null){
            $select_stmt -> close();
        }
    }
}

#Funcioón que permite actualizar todos los datos de cada usuario de usuarios-administración, incluyendo el password.
function update_user_admin_alldata($name, $surname, $email, $phone, $birthday, $address, $gender, $user, $pass, $rol, $idUser, $mysqli_connection, &$exception_error)
{
    # Inicializar la senencia de selección como nula
    $update_stmt = null;

    # Inicializamos la variable de error asumiendo que inicialmente no hay ningún error
    $exception_error = false;

    #Evitar inyecciones SQL
    $newName = $mysqli_connection -> real_escape_string($name);
    $newSurname = $mysqli_connection -> real_escape_string($surname);
    $newEmail = $mysqli_connection -> real_escape_string($email);
    $newPhone = $mysqli_connection -> real_escape_string($phone);
    $newBirthday = $mysqli_connection -> real_escape_string($birthday);
    $newAddress = $mysqli_connection -> real_escape_string($address);
    $NewGender = $mysqli_connection -> real_escape_string($gender);
    $newUser = $mysqli_connection -> real_escape_string($user);
    $newPassword = $mysqli_connection -> real_escape_string($pass);
    $newRol = $mysqli_connection -> real_escape_string($rol);

    try{

        #Crear consulta de actualización
        $query = "UPDATE users_data ud INNER JOIN users_login ul ON (ud.idUser = ul.idUser) SET nombre = ?, apellidos = ?, email = ?, telefono = ?, fecha_nacimiento = ?, direccion = ?, sexo = ?, usuario = ?, password = ?, rol = ?  WHERE ud.idUser = ?";

        #Preparar la sentencia SQL
        $update_stmt = $mysqli_connection -> prepare($query);
        if($update_stmt === false){
            error_log("No se pudo preparar la sentencia" . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }

        #Vincular los parametros
        $update_stmt -> bind_param("sssssssssss", $newName, $newSurname, $newEmail, $newPhone, $newBirthday, $newAddress, $NewGender, $newUser, $newPassword, $newRol, $idUser);
        
        #Intentar ejecutar la sentencia de actualización.
        if(!$update_stmt -> execute()){
            error_log("No se puede ejecutar la sentencia " . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }
        #Ejecutar la sentencia de actualización 
        $resultado =  $update_stmt -> execute();

        #comprobar si la actualización se ha realizado bien
        if($resultado){
            return true;
        }else{
            return false;
        }
    }catch(Exception $e){
        error_log("Error al ejecutar la función update_user_admin_alldata(): " . $e -> getMessage());
        $exception_error = true;
        return false;
    }finally{
        // Nos aseguramos de cerrar la sentencia si existe
        if($update_stmt !== null){
            $update_stmt -> close();
        }

    }

    
}



#Funcioón que permite actualizar todo el usuario, incluyendo el password.
function update_user_admin_without_password($name, $surname, $email, $phone, $birthday, $address, $gender, $user, $rol, $idUser, $mysqli_connection, &$exception_error)
{
    # Inicializar la senencia de selección como nula
    $update_stmt = null;

    # Inicializamos la variable de error asumiendo que inicialmente no hay ningún error
    $exception_error = false;
 
    #Evitar inyecciones SQL
    $newName = $mysqli_connection -> real_escape_string($name);
    $newSurname = $mysqli_connection -> real_escape_string($surname);
    $newEmail = $mysqli_connection -> real_escape_string($email);
    $newPhone = $mysqli_connection -> real_escape_string($phone);
    $newBirthday = $mysqli_connection -> real_escape_string($birthday);
    $newAddress = $mysqli_connection -> real_escape_string($address);
    $newGender = $mysqli_connection -> real_escape_string($gender);
    $newUser = $mysqli_connection -> real_escape_string($user);
    $newRol = $mysqli_connection -> real_escape_string($rol);
    try{

        #Crear consulta de actualización
        $query = "UPDATE users_data ud INNER JOIN users_login ul ON (ud.idUser = ul.idUser) SET nombre = ?, apellidos = ?, email = ?, telefono = ?, fecha_nacimiento = ?, direccion = ?, sexo = ?, usuario = ?, rol = ? WHERE ud.idUser = ?";

        #Preparar la sentencia SQL
        $update_stmt = $mysqli_connection -> prepare($query);
        if($update_stmt === false){
            error_log("No se pudo preparar la sentencia" . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }

        #Vincular los parametros
        $update_stmt -> bind_param("ssssssssss", $newName, $newSurname, $newEmail, $newPhone, $newBirthday, $newAddress, $newGender, $newUser, $newRol, $idUser);
        
        #Intentar ejecutar la sentencia de actualización.
        if(!$update_stmt -> execute()){
            error_log("No se puede ejecutar la sentencia " . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }
        #Ejecutar la sentencia de actualización 
        $resultado = $update_stmt -> execute();

        #comprobar si la actualización se ha realizado bien
        if($resultado){
            return true;
        }else{
            return false;
        }
    }catch(Exception $e){
        error_log("Error al ejecutar la función update_user_admin_without_password(): " . $e -> getMessage());
        $exception_error = true;
        return false;
    }finally{
        // Nos aseguramos de cerrar la sentencia si existe
        if($update_stmt !== null){
            $update_stmt -> close();
        }

    }

}


#Funcioón que permite el borrado de cada usuario a traves del id.
function delete_user_by_id($idUser, $mysqli_connection, &$exception_error)
{
    # Inicializar la sentencia de eliminación como nula
    $delete_stmt = null;

    # Inicializamos la variable de error asumiendo que inicialmente no hay ningún error
    $exception_error = false;
 
    try{

        #Crear consulta de borrado
        $query = "DELETE users_login, users_data FROM users_login INNER JOIN users_data ON users_login.idUser = users_data.idUser WHERE users_data.idUser = ?";

        #Preparar la sentencia SQL
        $delete_stmt = $mysqli_connection -> prepare($query);
        if($delete_stmt === false){
            error_log("No se pudo preparar la sentencia" . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }

        #Vincular los parametros
        $delete_stmt -> bind_param("s", $idUser);
        
        #Intentar ejecutar la sentencia de eliminación.
        if(!$delete_stmt -> execute()){
            error_log("No se puede ejecutar la sentencia " . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }
        #Ejecutar la sentencia de eliminación
        $resultado = $delete_stmt -> execute();

        #comprobar si el borrado se ha realizado bien
        if($resultado){
            return true;
        }else{
            return false;
        }
    }catch(Exception $e){
        error_log("Error al ejecutar la función delete_user_by_id(): " . $e -> getMessage());
        $exception_error = true;
        return false;
    }finally{
        // Nos aseguramos de cerrar la sentencia si existe
        if($delete_stmt !== null){
            $delete_stmt -> close();
        }

    }

}

function get_citas_and_alldata_by_idUser($idUser, $mysqli_connection, &$exception_error){
    #Inicializar la sentencia del selección como nula.
    $select_stmt = null;
    #Inicializamos la variable de error asumiendo que inicialmente no hay ningún error.
    $exception_error = false;

    try{
        #Preparar la sentencia SQL necesaria para coger todos los datos de cita a través del idUser.
        $query = "SELECT ci.idCita, ci.idUser, ci.fecha_cita, ci.motivo_cita, ul.usuario, DATEDIFF(ci.fecha_cita, CURRENT_DATE()) AS diferencia  FROM users_data ud JOIN users_login ul ON (ud.idUser = ul.idUser) JOIN citas ci ON (ud.idUser = ci.idUser) WHERE ud.idUser = ?";
        $select_stmt = $mysqli_connection -> prepare($query);
        
        #Si la sentencia no se ha realizado
        if(!$select_stmt){
            error_log("No se pudo preparar la sentencia" . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }

        #vincular el idCita a la sentencia.
        $select_stmt -> bind_param('s', $idUser);

        #Ejecutar la sentencia de seleccíon.
        if(!$select_stmt -> execute()){
            error_log("No se puede ejecutar la sentencia " . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }

        #Obtener el resultado de la consulta.
        $result = $select_stmt -> get_result();
        if($result -> num_rows > 0){
            $citas = []; 
            while($fila = $result ->fetch_assoc()){
                $citas[] = $fila;
            }
            #Devuelve todas las filas.
            return $citas;
           
        }else{
            #Si no se encuentra ninguna fila.
            return false;
        }
        
    }catch(Exception $e){
        error_log("Error al ejecutar la función s_and_alldata_by_idUser(): " . $e -> getMessage());
        $exception_error = true;
        return false;
    }finally{
        #Nos aseguramos de cerrar la sentencia si existe.
        if($select_stmt !== null){
            $select_stmt -> close();
        }
    }
}

#Función que permite leer los usuarios.
function get_users_by_id($idUser, $mysqli_connection, &$exception_error){
    #Inicializar la sentencia del selección como nula.
    $select_stmt = null;
    #Inicializamos la variable de error asumiendo que inicialmente no hay ningún error.
    $exception_error = false;

    try{
        #Preparar la sentencia SQL necesaria para coger los datos de todos los usarios a través del idUser.
        $query = "SELECT ud.idUser, ul.usuario FROM users_data ud JOIN users_login ul ON (ud.idUser = ul.idUser) WHERE ud.idUser = ?";
        
        $select_stmt = $mysqli_connection->prepare($query);
        #Si la sentencia no se ha realizado
        if(!$select_stmt){
            error_log("No se pudo preparar la sentencia" . $mysqli_connection -> error);
            $exception_error = true;
            return null;
        }
            #vincular el idCita a la sentencia.
            $select_stmt -> bind_param('s', $idUser);
            #Ejecutar la sentencia de seleccíon.
            if(!$select_stmt -> execute()){
                error_log("No se puede ejecutar la sentencia " . $mysqli_connection -> error);
                $exception_error = true;
                return null;
            }

      #Obtener el resultado de la consulta.
      $result = $select_stmt -> get_result();
      if($result -> num_rows > 0){
          $users = []; 
          while($fila = $result ->fetch_assoc()){
              $users[] = $fila;
          }
          return $users;
        }else{
            #No se encontraron resultados
            return [];
        }

    }catch(Exception $e){
        error_log("Error al ejecutar la función get_users_by_id(): " . $e -> getMessage());
        $exception_error = true;
        return null;
    }finally{
        #Nos aseguramos de cerrar la sentencia si existe.
        if($select_stmt !== null){
            $select_stmt -> close();
        }
    }
}


#Función que permite leer todos los datos de todos los usuarios.
function get_citas($mysqli_connection, &$exception_error){
    #Inicializar la sentencia del selección como nula.
    $select_stmt = null;
    #Inicializamos la variable de error asumiendo que inicialmente no hay ningún error.
    $exception_error = false;

    try{
        #Preparar la sentencia SQL necesaria para coger los datos de todos los usarios a través del idUser.
        $query = "SELECT * FROM citas";
        
        $select_stmt = $mysqli_connection->prepare($query);
        #Si la sentencia no se ha realizado
        if(!$select_stmt){
            error_log("No se pudo preparar la sentencia" . $mysqli_connection -> error);
            $exception_error = true;
            return null;
        }

    
        #Ejecutar la sentencia de seleccíon.
        if(!$select_stmt -> execute()){
            error_log("No se puede ejecutar la sentencia " . $mysqli_connection -> error);
            $exception_error = true;
            return null;
        }

      #Obtener el resultado de la consulta.
      $result = $select_stmt -> get_result();
      if($result -> num_rows > 0){
          $citas = []; 
          while($fila = $result ->fetch_assoc()){
              $citas[] = $fila;
          }
        //  echo "Ey\n";
         // print_r($citas);
          #Devuelve todas las filas.
          return $citas;
        }else{
            #No se encontraron resultados
            return [];
        }

    }catch(Exception $e){
        error_log("Error al ejecutar la función get_citas(): " . $e -> getMessage());
        $exception_error = true;
        return null;
    }finally{
        #Nos aseguramos de cerrar la sentencia si existe.
        if($select_stmt !== null){
            $select_stmt -> close();
        }
    }
}

#Función que permite leer los datos de Noticias por el id del usuario.
function get_news_by_idUser($idUser, $mysqli_connection, &$exception_error){
    #Inicializar la sentencia del selección como nula.
    $select_stmt = null;
    #Inicializamos la variable de error asumiendo que inicialmente no hay ningún error.
    $exception_error = false;

    try{
        #Preparar la sentencia SQL necesaria para coger todos los datos de noticias a través del idUser.
        $query = "SELECT * FROM noticias  WHERE idUser = ?";
        $select_stmt = $mysqli_connection -> prepare($query);
        
        #Si la sentencia no se ha realizado
        if(!$select_stmt){
            error_log("No se pudo preparar la sentencia" . $mysqli_connection->error);
            $exception_error = true;
            return false;
        }

        #vincular el idCita a la sentencia.
        $select_stmt -> bind_param('s', $idUser);

        #Ejecutar la sentencia de seleccíon.
        if(!$select_stmt -> execute()){
            error_log("No se puede ejecutar la sentencia " . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }

        # Obtener el resultado de la consulta
        $result = $select_stmt->get_result();
        if($result -> num_rows > 0){
            $user = []; 
            while($fila = $result ->fetch_assoc()){# fetch_assoc() nos permite obtener los datos del resultado como un array asociativo (clave: valor).
                $user[] = $fila;
            }
        
            #Devuelve todas las filas.
            return $user;
          }else{
              #No se encontraron resultados
              return [];
          } 

    }catch(Exception $e){
        error_log("Error al ejecutar la función get_news_by_idUser(): " . $e -> getMessage());
        $exception_error = true;
        return false;
    }finally{
        #Nos aseguramos de cerrar la sentencia si existe.
        if($select_stmt !== null){
            $select_stmt -> close();
        }
    }
}

#Función que permite leer todos los datos de las noticias.
function get_news($mysqli_connection, &$exception_error){
    #Iniciar la sentencia de selección como nula.
    $select_stmt = null;
    
    #Iniciar la variable de excepción como false
    $exception_error = false;

    try{
        #Preparar la sentencia de SQL para recoger todos los datos de las noticias.
        $query = "SELECT * FROM noticias";
        
        #Preparar SQL
        $select_stmt = $mysqli_connection->prepare($query);

        #comprobar la sentencia
        if(!$select_stmt){
            #Error en la sentencia
            error_log("No se pudo preparar la sentencia ". $mysqli_connection->error);
            $exception_error = true;
            return false;
        }

        #Ejecutar la sentencia de selección.
        if(!$select_stmt->execute()){
            #Error en ejecutar la sentencia.
            error_log("No se pudo ejecutar la sentencia. ". $mysqli_connection->error);
            $exception_error = true;
            return false;
        }
        #Obtener el resutado de la consulta
        $result = $select_stmt->get_result();
        if($result->num_rows > 0){
            $news = [];
            while($row = $result->fetch_assoc()){
                $news[] = $row;
            }

            #Devuelve todas las filas
            return $news;
        }else{
            #No se encontraron resultados
            return [];
        }

        
        #Capturar la excepción
    }catch(Exception $e){
        #Guardar el error_log en el servidor
        error_log("No se pudo extraer los datos de get_news().". $e->getMessage());
        $exception_error = true;
        return false;

        #Cerrar la sentencia si existe
    }finally{
        #cerrar sentencia
        if($select_stmt !== null){
            $select_stmt->close();
        }

    }
}

#Función que permite ver todos los datos de noticias y toda la data
function get_news_and_alldata_by_idUser($idUser, $mysqli_connection, &$exception_error){
    #Inicializar la sentencia del selección como nula.
    $select_stmt = null;
    #Inicializamos la variable de error asumiendo que inicialmente no hay ningún error.
    $exception_error = false;

    try{
        #Preparar la sentencia SQL necesaria para coger todos los datos de cita a través del idUser.
        $query = "SELECT noti.idNoticia, noti.titulo, noti.imagen, noti.texto, noti.fecha, noti.idUser, ul.usuario FROM users_data ud JOIN users_login ul ON (ud.idUser = ul.idUser) JOIN noticias noti ON (ud.idUser = noti.idUser) WHERE ud.idUser = ?";
        $select_stmt = $mysqli_connection -> prepare($query);
        
        #Si la sentencia no se ha realizado
        if(!$select_stmt){
            error_log("No se pudo preparar la sentencia" . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }

        #vincular el idCita a la sentencia.
        $select_stmt -> bind_param('s', $idUser);

        #Ejecutar la sentencia de seleccíon.
        if(!$select_stmt -> execute()){
            error_log("No se puede ejecutar la sentencia " . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }

        #Obtener el resultado de la consulta.
        $result = $select_stmt -> get_result();
        if($result -> num_rows > 0){
            $news = []; 
            while($fila = $result ->fetch_assoc()){
                $news[] = $fila;
            }
            #Devuelve todas las filas.
            return $news;
           
        }else{
            #Si no se encuentra ninguna fila.
            return false;
        }
        
    }catch(Exception $e){
        error_log("Error al ejecutar la función s_and_alldata_by_idUser(): " . $e -> getMessage());
        $exception_error = true;
        return false;
    }finally{
        #Nos aseguramos de cerrar la sentencia si existe.
        if($select_stmt !== null){
            $select_stmt -> close();
        }
    }
}


#Función que permite leer los datos de Noticias por el idNoticia.
function get_news_by_idNoticia($idNews, $mysqli_connection, &$exception_error){
    #Inicializar la sentencia del selección como nula.
    $select_stmt = null;
    #Inicializamos la variable de error asumiendo que inicialmente no hay ningún error.
    $exception_error = false;

    try{
        #Preparar la sentencia SQL necesaria para coger todos los datos de noticias a través del idUser.
        $query = "SELECT * FROM noticias  WHERE idNoticia = ?";
        $select_stmt = $mysqli_connection -> prepare($query);
        
        #Si la sentencia no se ha realizado
        if(!$select_stmt){
            error_log("No se pudo preparar la sentencia" . $mysqli_connection->error);
            $exception_error = true;
            return false;
        }

        #vincular el idCita a la sentencia.
        $select_stmt -> bind_param('s', $idNews);

        #Ejecutar la sentencia de seleccíon.
        if(!$select_stmt -> execute()){
            error_log("No se puede ejecutar la sentencia " . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }

        # Obtener el resultado de la consulta
        $result = $select_stmt->get_result();
        if($result -> num_rows > 0){
            $news = []; 
            while($fila = $result ->fetch_assoc()){# fetch_assoc() nos permite obtener los datos del resultado como un array asociativo (clave: valor).
                $news[] = $fila;
            }
            //echo "Ey\n";
            //print_r($news);
        
            #Devuelve todas las filas.
            return $news;
          }else{
              #No se encontraron resultados
              return [];
          } 

    }catch(Exception $e){
        error_log("Error al ejecutar la función get_news_by_idUser(): " . $e -> getMessage());
        $exception_error = true;
        return false;
    }finally{
        #Nos aseguramos de cerrar la sentencia si existe.
        if($select_stmt !== null){
            $select_stmt -> close();
        }
    }
}



#Función que permite actualizar noticias creadas por usuario a traves del idNoticia.
function update_news_by_idNoticia($title, $nombreFichero, $text, $create_date, $idNoticia, $mysqli_connection, &$exception_error)
{
    # Inicializar la senencia de selección como nula
    $update_stmt = null;

    # Inicializamos la variable de error asumiendo que inicialmente no hay ningún error
    $exception_error = false;
 
    #Evitar inyecciones SQL
    $newTitle = $mysqli_connection -> real_escape_string($title);
    $newImg = $mysqli_connection -> real_escape_string($nombreFichero);
    $newText = $mysqli_connection -> real_escape_string($text);
    $newCreate_date = $mysqli_connection -> real_escape_string($create_date);
    try{

        #Crear consulta de actualización
        $query = "UPDATE noticias SET titulo = ?, imagen = ?, texto = ?, fecha = ? WHERE idNoticia = ?";

        #Preparar la sentencia SQL
        $update_stmt = $mysqli_connection -> prepare($query);
        if($update_stmt === false){
            error_log("No se pudo preparar la sentencia" . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }

        #Vincular los parametros
        $update_stmt -> bind_param("sssss",  $newTitle, $newImg, $newText, $newCreate_date, $idNoticia);
        
        #Intentar ejecutar la sentencia de actualización.
        if(!$update_stmt -> execute()){
            error_log("No se puede ejecutar la sentencia " . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }
        #Ejecutar la sentencia de actualización 
        $resultado = $update_stmt -> execute();

        #comprobar si la actualización se ha realizado bien
        if($resultado){
            return true;
        }else{
            return false;
        }
        
    }catch(Exception $e){
        error_log("Error al ejecutar la función update_noticias_by_idNoticia(): " . $e -> getMessage());
        $exception_error = true;
        return false;
    }finally{
        // Nos aseguramos de cerrar la sentencia si existe
        if($update_stmt !== null){
            $update_stmt -> close();
        }

    }

}


#Funcioón que permite actualizar noticias sin seleccionar el archivo(imagen)creadas por usuario a traves del idNoticia.
function update_news_whitout_image_by_idNoticia($title, $text, $create_date, $idNoticia, $mysqli_connection, &$exception_error)
{
    # Inicializar la senencia de selección como nula
    $update_stmt = null;

    # Inicializamos la variable de error asumiendo que inicialmente no hay ningún error
    $exception_error = false;
 
    #Evitar inyecciones SQL
    $newTitle = $mysqli_connection -> real_escape_string($title);
    $newText = $mysqli_connection -> real_escape_string($text);
    $newCreate_date = $mysqli_connection -> real_escape_string($create_date);
    try{

        #Crear consulta de actualización
        $query = "UPDATE noticias SET titulo = ?, texto = ?, fecha = ? WHERE idNoticia = ?";

        #Preparar la sentencia SQL
        $update_stmt = $mysqli_connection -> prepare($query);
        if($update_stmt === false){
            error_log("No se pudo preparar la sentencia" . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }

        #Vincular los parametros
        $update_stmt -> bind_param("ssss",  $newTitle, $newText, $newCreate_date, $idNoticia);
        
        #Intentar ejecutar la sentencia de actualización.
        if(!$update_stmt -> execute()){
            error_log("No se puede ejecutar la sentencia " . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }
        #Ejecutar la sentencia de actualización 
        $resultado = $update_stmt -> execute();

        #comprobar si la actualización se ha realizado bien
        if($resultado){
            return true;
        }else{
            return false;
        }
        
    }catch(Exception $e){
        error_log("Error al ejecutar la función update_noticias_by_idNoticia(): " . $e -> getMessage());
        $exception_error = true;
        return false;
    }finally{
        // Nos aseguramos de cerrar la sentencia si existe
        if($update_stmt !== null){
            $update_stmt -> close();
        }

    }
}

#Funcioón que permite el borrado de la cita a traves del idCita.
function delete_news_by_idNoticia($idNoticia, $mysqli_connection, &$exception_error)
{
    # Inicializar la sentencia de eliminación como nula
    $delete_stmt = null;

    # Inicializamos la variable de error asumiendo que inicialmente no hay ningún error
    $exception_error = false;
 

 
    try{

        #Crear consulta de borrado
        $query = "DELETE FROM noticias WHERE idNoticia = ?";

        #Preparar la sentencia SQL
        $delete_stmt = $mysqli_connection -> prepare($query);
        if($delete_stmt === false){
            error_log("No se pudo preparar la sentencia" . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }

        #Vincular los parametros
        $delete_stmt -> bind_param("s", $idNoticia);
        
        #Intentar ejecutar la sentencia de eliminación.
        if(!$delete_stmt -> execute()){
            error_log("No se puede ejecutar la sentencia " . $mysqli_connection -> error);
            $exception_error = true;
            return false;
        }
        #Ejecutar la sentencia de eliminación
        $resultado = $delete_stmt -> execute();

        #comprobar si el borrado se ha realizado bien
        if($resultado){
            return true;
        }else{
            return false;
        }
    }catch(Exception $e){
        error_log("Error al ejecutar la función delete_noticia_by_idNoticia " . $e -> getMessage());
        $exception_error = true;
        return false;
    }finally{
        // Nos aseguramos de cerrar la sentencia si existe
        if($delete_stmt !== null){
            $delete_stmt -> close();
        }

    }

}
