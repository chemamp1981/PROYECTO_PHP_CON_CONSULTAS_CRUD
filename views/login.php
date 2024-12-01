<?php
require_once __DIR__."/../config/config.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
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
            <link rel="stylesheet" href="../assets/css/estilos.css">



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
                            <img src="../assets/images/luna.png" alt="imagen de una luna" width="300" height="302">
                        </div>
                    </div>

                    <!--Menu de navegación-->
                
                    <div class="menu">
                        <nav>
                            <ul class="lista">
                                <li><a href="../index.php" target="_self">Inicio</a></li>
                                <li><a href="../controllers/c_users/c_read_noticias.php" target="_self">Noticias</a></li>
                                <li><a href="registro.php" target="_self">Registro</a></li>
                                <li><a href="#" target="_self" class="selected">Login</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </header>
               
                 <!--Formulario del Login-->

            <main>

            <section>
                <h2>Formulario de incio de sesión</h2>
                <div class="aviso_registro">
                <?php
                #Comprobar si hay mensajes de error
                if(isset($_SESSION["mensaje_error"])){
                    echo "<span class='error_message'>" . $_SESSION['mensaje_error'] . "</span>";

                    #Eliminar el mensaje de error
                    unset($_SESSION["mensaje_error"]);
                }
                ?>
                </div>
                <div class="formulario">
                    <form id="login_form" calss="mi_form" action="../controllers/c_login.php" method="POST">
                        <div class="form_options">
                            <label for="fuser">Usuario: </label>
                            <div class="input_zone">
                                <input type="text" id="fuser" name="fuser" placeholder="Escriba su usuario..">
                                <small></small>
                            </div>
                        </div>
                        <div class="form_options">
                            <label for="fpassword">Contraseña: </label>
                            <div class="input_zone">
                                <input type="password" id="fpassword" name="fpassword" placeholder="Escriba su contraseña..">
                                <small></small>
                            </div>
                        </div>
                        <div class="password_show">
                            <label for="check_password">Mostrar Contraseña</label>
                            <input type="checkbox" id="check_password"> 
                        </div>
                        <div class="form_buttons">
                            <button type="reset" value="Borrar">Borrar</button>
                            <button type="submit" id="enviar" name="inicio_sesion">Enviar</button>
                        </div>
                    </form>
                </div>
            </section>
            </main>
            <!--PIE DE PÁGINA -->
            <footer>
                <div class="footer">
                    <p>Aviso legal - Polílitica de Privacidad - Politica de Cookies.<br>Odontologia Luna.2024.<br>Diseño:chemamp</p>
                </div>
            </footer>
            <script src="../assets/scripts/show_password.js"></script>
            <script src="../assets/scripts/v_login.js"></script>
        </body>
</html>