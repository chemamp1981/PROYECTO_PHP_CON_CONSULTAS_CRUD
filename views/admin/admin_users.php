<?php
require_once __DIR__."/../../config/config.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(isset($_SESSION['all_data'])){
    $allData = $_SESSION['all_data'];
    
}else{
    $_SESSION['mensaje_error'] = "Lo sentimos debes iniciar sesión primero";
    header('location:../login.php');
}

if(isset($_SESSION['all_users'])){
    $allUsers = $_SESSION['all_users'];
 
}else{
    $_SESSION['$mensaje_error'] = " Error al extraer los datos de todos los usuarios";
}

if(isset($_SESSION['eachUser'])){
    $eachUser = $_SESSION['eachUser'];
}else{
    $_SESSION['$mensaje_error'] = " Error al extraer los datos de cada usuario";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Odontologia Luna</title>

    <link rel="icon" type="image/x-icon" href="../../favicon.ico">
    <link rel="stylesheet" href="../../assets/css/estilos.css">



    <link href="https://fonts.googleapis.com/css2?family=Indie+Flower&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dosis&family=Indie+Flower&display=swap" rel="stylesheet">


</head>
<body>

    <!--Encabezado pagina web-->
    <header>
        <div class="content-menu">
           <div class="web-logo">
                <div class="name-logo">
                    <h1>Odontología Luna</h1>
                </div>
                <div class="logo">
                    <img src="../../assets/images/luna.png" alt="imagen de una luna" width="300" height="302">
                </div>
            </div>

            <!--Menu de navegación-->
            <div class="menu">
                <nav>
                    <ul class="lista">
                        <?php if(isset($_SESSION['all_data']['rol']) && $_SESSION['all_data']['rol'] === 'admin'): ?>
                        <li class="usuario">Usuario: <?php echo $allData['usuario'];?></li>
                        <li><a href="../../index.php" target="_self">Inicio</a></li>
                        <li><a href="../../controllers/c_users/c_read_noticias.php" target="_self">Noticias</a></li>
                        <li><a href="../../controllers/c_admin/c_read_admin.php" target="_self" class="selected">Usuarios-administracíon</a>
                            <ul class="sublista">
                                <li><a href="../../controllers/c_admin/c_read_admin.php">Crear usuario</a></li>
                            </ul>
                        </li>
                        <li><a href="../../controllers/c_admin/c_read_citas_admin.php" target="_self">Citas-administracíon</a></li>
                        <li><a href="../../controllers/c_admin/c_read_news_admin.php" target="_self">Noticias-administracíon</a></li>
                        <li><a href="../users/perfil.php" target="_self">Perfil</a></li>
                        <li><a href="../../controllers/c_logout.php">Cerrar sesión</a></li>
                        <?php else:?>
                        <li><a href="#" target="_self" class="selected">Inicio</a></li>
                        <li><a href="./controllers/c_users/c_read_noticias.php" target="_self">Noticias</a></li>
                        <li><a href="./views/registro.php" target="_self">Registro</a></li>
                        <li><a href="./views/login.php" target="_self">Login</a></li>
                        <?php endif;?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!--Cuerpo de la pagina de inicio-->

    <main>
        <section class="dashboard">
            <div class="aviso_mensajes">
                <?php
                #Comprobar si hay mensajes de error
                if (isset($_SESSION["mensaje_error"])) {
                    echo "<span class='error_message'>" . $_SESSION['mensaje_error'] . "</span>";

                    #Eliminar el mensaje de error
                    unset($_SESSION["mensaje_error"]);
                }


                #Comprobar si hay mensajes de exito
                if (isset($_SESSION["mensaje_exito"])) {
                    echo "<span class='success_message'>" . $_SESSION['mensaje_exito'] . "</span>";

                    #Eliminar el mensaje de exito
                    unset($_SESSION["mensaje_exito"]);
                }

                ?>
            </div>
        </section>
        <section class="dashboard">
            <h2>LISTADO DE USUARIOS</h2>
            <div class="table-color-format">
                <table>
                    <tr>
                        <th>USUARIOS</th>
                    </tr>

                    <?php
                    //VER TODOS LOS USUARIOS
            
                    foreach ($allUsers as $value) {
                        ?>
                        <tr>
                            <td><?php echo $value['usuario']?></td>
                            <td class="button-table">
                                <form id="edUser" action="../../controllers/c_admin/c_read_admin.php" method="post">
                                    <button class="edit" type="submit" name="edUser">Editar</button>
                                    <input type="hidden" name="idUser" value="<?php echo $value['idUser']?>">
                                </form>
                            </td>
                            <td class="button-table">
                                <form id="borrar" action="../../controllers/c_admin/c_delete_admin.php" method="post">
                                    <button class="edit" type="submit" name="borrar" value="">Borrar</button>
                                    <input type="hidden" name="idUser" value="<?php echo $value['idUser']?>">
                                </form>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
            </div>
        </section> 
            <?php 
            $modificar = null;
            if(isset($_GET['modificar'])){
                $modificar = $_GET['modificar'];
            }  
            ?>
        <section class="dashboard">
            <h2><?php echo ($modificar == 'ok') ? 'MODIFICAR USUARIO' : 'CREAR USUARIO NUEVO' ?></h2>
            <form action="<?php echo  ($modificar == 'ok') ? '../../controllers/c_admin/c_update_admin.php' : '../../controllers/c_admin/c_create_admin.php'?> " method="post">
                <div class="form__admin-user"> 
                    <div class="form_options">
                        <label for="fname">Nombre: </label>
                        <div class="input_zone">
                            <input type="text" id="fname" name="fname" value="<?php echo ($modificar == 'ok') ? $eachUser['nombre'] : '' ?>">
                            <small></small>
                        </div>
                    </div>
                    <div class="form_options">
                        <label for="fsurname">Apellidos: </label>
                        <div class="input_zone">
                            <input type="text" id="fsurname" name="fsurname" value="<?php echo ($modificar == 'ok') ?  $eachUser['apellidos'] : '' ?>">
                            <small></small>
                        </div>
                    </div>
                    <div class="form_options">
                        <label for="femail">Email: </label>
                        <div class="input_zone">
                            <input type="email" id="femail" name="femail" value="<?php echo ($modificar == 'ok') ? $eachUser['email'] : '' ?>">
                            <small></small>
                        </div>
                    </div>
                    <div class="form_options">
                        <label for="fphone">Teléfono: </label>
                        <div class="input_zone">
                            <input type="tel" id="fphone" name="fphone" value="<?php echo ($modificar == 'ok') ?  $eachUser['telefono'] : '' ?>">
                            <small></small>
                        </div>
                    </div>
                    <div class="form_options">
                        <label for="fbirthday">Fecha de nacimiento: </label>
                        <div class="input_zone">
                            <input type="date" id="fbirthday" name="fbirthday" value="<?php echo ($modificar == 'ok') ? $eachUser['fecha_nacimiento'] : '' ?>">
                            <small></small>
                        </div>
                    </div>
                    <div class="form_options">
                        <label for="faddress">Dirección: </label>
                        <div class="input_zone">
                            <input type="text" id="faddress" name="faddress" value="<?php echo ($modificar == 'ok') ?  $eachUser['direccion'] : '' ?>">
                            <small></small>
                        </div>
                    </div>
                    <div class="form_options">
                        <label for="fgender">Genero: </label>
                        <div class="input_zone">
                            <input type="text" id="fgender" name="fgender" value="<?php echo ($modificar == 'ok') ?  $eachUser['sexo'] : '' ?>">
                            <small></small>
                        </div>
                    </div>
                    <div class="form_options">
                        <label for="fuser">Usuario: </label>
                        <div class="input_zone">
                            <input type="text" id="fuser" name="fuser" value="<?php echo ($modificar == 'ok') ?  $eachUser['usuario'] : '' ?>">
                            <small></small>
                        </div>
                    </div>
                    <?php if($modificar == 'ok'):?>
                    <div class="form_options">
                        <label for="fcheck">Editar password: </label>
                        <div class="input_zone">
                            <input type="checkbox" class="checkbox" id="fcheck" name="fcheck" value="fcheck_value" checked>
                            <small></small>
                        </div>
                    </div>
                    <div class="form_options" id="content" class="hidden">
                        <label for="fpassword">password: </label>
                        <div class="input_zone">
                            <input type="password" id="fpassword" name="fpassword" value="<?php echo  $eachUser['password']; ?>">
                            <input type="password" id="fpasswordConfirm" name="fpasswordConfirm" placeholder="Confirmar password...">
                            <small></small>
                        </div>
                    </div>
                    <?php else:?>
                    <div class="form_options" id="content" class="hidden">
                        <label for="fpassword">password: </label>
                        <div class="input_zone">
                            <input type="password" id="fpassword" name="fpassword" value="">
                            <small></small>
                        </div>
                    </div>
                    <div class="form_options">
                        <label for="check_password">Mostrar Contraseña</label>
                        <div class="password_show" class="input_zone" class="margin__cero">
                            <input type="checkbox" id="check_password"> 
                        </div>
                    </div>
                    <?php endif;?>
                    <div class="form_options">
                        <label for="frol">Rol</label>
                        <select name="frol" id="frol">
                            <?php if($modificar == 'ok'):?>
                            <option value="admin" <?php echo $eachUser['usuario'] == 'admin' ? 'selected' : '' ?>>Administrador</option>
                            <option value="user" <?php echo  $eachUser['usuario'] == 'admin' ? '' : 'selected' ?>>Usuario</option>
                            <?php else:?>
                            <option value="admin">Administrador</option>
                            <option value="user"selected>Usuario</option>
                            <?php endif;?>
                        </select>
                    </div>
                    <div class="form_buttons">
                        <button class="edit" type="submit" name="<?php echo($modificar == 'ok') ? 'modificar' : 'create'?>"><?php echo ($modificar == 'ok') ? 'Modificar' : 'Crear'?></button>
                    </div>
                    <div>
                         <?php if($modificar == 'ok'):?>
                        <input type="hidden" name="idUser" value="<?php echo $eachUser['idUser'];?>">
                        <?php endif;?>
                    </div>
                </div>
            </form>
        </section>
    </main>
    <footer>
        <div class="footer">
            <p>Aviso legal - Polílitica de Privacidad - Politica de Cookies<br>Odontologia Luna.2024.<br>Diseño:chemamp</p>
        </div>
    </footer>
                         <!--Utilizo v_register para validar los datos al crear nuevo usuario en la pagina de admin_users.php -->
    <script src="../../assets/scripts/v_register.js"></script>
    <script src="../../assets/scripts/show_password.js"></script>
    <script src="../../assets/scripts/passwordEdit.js"></script>

</body>

</html>