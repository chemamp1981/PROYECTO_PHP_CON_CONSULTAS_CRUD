<?php
# Vinculamos los archivos necesarios

require_once __DIR__ . "/../../config/config.php";




if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['all_data'])) {
    $allData = $_SESSION['all_data'];

} else {
    $_SESSION['mensaje_error'] = "Lo sentimos debes iniciar sesión primero";
    header('location:../login.php');
}
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Odontologia Luna</title>

    <link rel="icon" type="image/x-icon" href="../favicon.ico">
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
                        <?php if (isset($_SESSION['all_data']['rol']) && $_SESSION['all_data']['rol'] === 'user'): ?>
                        <li class="usuario">Usuario: <?php echo $allData['usuario']; ?></li>
                        <li><a href="../../index.php" target="_self">Inicio</a></li>
                        <li><a href="../../controllers/c_users/c_read_noticias.php" target="_self">Noticias</a></li>
                        <li><a href="../../controllers/c_users/c_citas.php" target="_self">Citaciones</a></li>
                        <li><a href="#" target="_self" class="selected">Perfil</a></li>
                        <li><a href="../../controllers/c_logout.php">Cerrar sesión</a></li>
                        <?php elseif (isset($_SESSION['all_data']['rol']) && $_SESSION['all_data']['rol'] === 'admin'): ?>
                        <li class="usuario">Usuario: <?php echo $allData['usuario']; ?></li>
                        <li><a href="../../index.php" target="_self">Inicio</a></li>
                        <li><a href="../../controllers/c_users/c_read_noticias.php" target="_self">Noticias</a></li>
                        <li><a href="../../controllers/c_admin/c_read_admin.php" target="_self">Usuarios-administracíon</a>
                            <ul class="sublista">
                                <li><a href="../../controllers/c_admin/c_read_admin.php">Crear usuario</a></li>
                            </ul></li>
                        <li><a href="../../controllers/c_admin/c_read_citas_admin.php" target="_self">Citas-administracíon</a></li>
                        <li><a href="../../controllers/c_admin/c_read_news_admin.php" target="_self">Noticias-administracíon</a></li>
                        <li><a href="#" target="_self" class="selected">Perfil</a></li>
                        <li><a href="../../controllers/c_logout.php">Cerrar sesión</a></li>
                        <?php else: ?>
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
            <h2>Perfil Usuario</h2>
                <div class="table-color-format">
                    <table>
                        <tr>
                            <th>NOMBRE</th>
                            <th>APELLIDOS</th>
                            <th>EMAIL</th>
                            <th>TELEFONO</th>
                            <th>FECHA NACIMIENTO</th>
                            <th>DIRECCION</th>
                            <th>SEXO</th>
                            <th>USUARIO</th>
                        </tr>
                        <tr>
                            <td>
                                <?php echo $allData['nombre']; ?>
                            </td>
                            <td>
                                <?php echo $allData['apellidos']; ?>
                            </td>
                            <td>
                                <?php echo $allData['email']; ?>
                            </td>
                            <td>
                                <?php echo $allData['telefono']; ?>
                            </td>
                            <td>
                                <?php echo $allData['fecha_nacimiento']; ?>
                            </td>
                            <td>
                                <?php echo $allData['direccion']; ?>
                            </td>
                            <td>
                                <?php echo $allData['sexo']; ?>
                            </td>
                            <td>
                                <?php echo $allData['usuario']; ?>
                            </td>
                            <td class='button-table'>
                                <div>
                                    <form id="edit" action="../../controllers/c_users/c_perfil.php" method="post">
                                        <button class="edit" type="submit" name="edit">Editar perfil</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
        </section>
        <!--Compruebo si existe la variable edit a traves de GET-->
        <?php
        $edit = null;
        if(isset($_GET['edit'])){
            $edit = $_GET['edit'];
        }
        if($edit == 'ok'):
        ?>
        <section>
            <h2>Editar perfil</h2>
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
            <div class="formulario">
                <form id="modificar" class="modificar" action="../../controllers/c_users/c_perfil.php" method="post">
                    <div class="form_options">
                        <label for="fname">Nombre: </label>
                        <div class="input_zone">
                            <input type="text" id="fname" name="fname" value="<?php echo $allData['nombre']; ?>">
                            <small></small>
                        </div>
                    </div>
                    <div class="form_options">
                        <label for="fsurname">Apellidos: </label>
                        <div class="input_zone">
                            <input type="text" id="fsurname" name="fsurname" value="<?php echo $allData['apellidos']; ?>">
                            <small></small>
                        </div>
                    </div>
                    <div class="form_options">
                        <label for="femail">Email: </label>
                        <div class="input_zone">
                            <input type="email" id="femail" name="femail" value="<?php echo $allData['email']; ?>">
                            <small></small>
                        </div>
                    </div>
                    <div class="form_options">
                        <label for="fphone">Teléfono: </label>
                        <div class="input_zone">
                            <input type="tel" id="fphone" name="fphone" value="<?php echo $allData['telefono']; ?>">
                            <small></small>
                        </div>
                    </div>
                    <div class="form_options">
                        <label for="fbirthday">Fecha de nacimiento: </label>
                        <div class="input_zone">
                            <input type="date" id="fbirthday" name="fbirthday" value="<?php echo $allData['fecha_nacimiento']; ?>">
                            <small></small>
                        </div>
                    </div>
                    <div class="form_options">
                        <label for="faddress">Dirección: </label>
                        <div class="input_zone">
                            <input type="text" id="faddress" name="faddress" value="<?php echo $allData['direccion']; ?>">
                            <small></small>
                        </div>
                    </div>
                    <div class="form_options">
                        <label for="fgender">Genero: </label>
                        <div class="input_zone">
                            <input type="text" id="fgender" name="fgender" value="<?php echo $allData['sexo']; ?>">
                            <small></small>
                        </div>
                    </div>
                    <div class="form_options">
                        <label for="fuser">Usuario: </label>
                        <div class="input_zone">
                            <input type="text" id="fuser" name="fuser" value="<?php echo $allData['usuario']; ?>">
                            <small></small>
                        </div>
                    </div>
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
                            <input type="password" id="fpassword" name="fpassword" value="<?php echo $allData['password']; ?>">
                            <input type="password" id="fpasswordConfirm" name="fpasswordConfirm" placeholder="Confirmar password...">
                            <small></small>
                        </div>
                    </div>
                    <div class="form_buttons">
                        <button type="submit" id="actualizar" name="actualizar">Modificar</button>
                    </div>
                </form>
            </div>
        </section>
        <?php endif;?>
    </main>
    <footer>
        <div class="footer">
            <p>Aviso legal - Polílitica de Privacidad - Politica de Cookies<br>Odontologia Luna.2024.<br>Diseño:chemamp
            </p>
        </div>
    </footer>
    <script src="../../assets/scripts/v_perfil.js"></script>
    <script src="../../assets/scripts/passwordEdit.js"></script>
</body>

</html>