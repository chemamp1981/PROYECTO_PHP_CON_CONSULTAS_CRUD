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
                                <li><a href="#" target="_self" class="selected">Registro</a></li>
                                <li><a href="login.php" target="_self">Login</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </header>
                
                 <!--Formulario del Registro-->

            <main>
                <section class="budget-content">
                    <h2>Formulario registro</h2>
                        <div class="aviso_mensajes">
                            <?php
                                #Comprobar si hay mensajes de error
                                if(isset($_SESSION["mensaje_error"])){
                                    echo "<span class='error_message'>" . $_SESSION['mensaje_error'] . "</span>";

                                    #Eliminar el mensaje de error
                                    unset($_SESSION["mensaje_error"]);
                                }
                            

                                #Comprobar si hay mensajes de exito
                                if(isset($_SESSION["mensaje_exito"])){
                                    echo "<span class='success_message'>" . $_SESSION['mensaje_exito'] . "</span>";

                                    #Eliminar el mensaje de exito
                                    unset($_SESSION["mensaje_exito"]);
                                }
                        
                            ?>
                        </div>    
                        <div class="formulario">
                            <form id="registro" class="registro" action="../controllers/c_registro.php" method="post">
                                <fieldset>
                                    <legend>Datos personales</legend>   
                                    <div class="form_options">
                                        <label for="fname">Nombre: </label>
                                        <div class="input_zone">
                                            <input type="text" id="fname" name="fname" placeholder="Escriba su nombre..">
                                            <small></small>
                                        </div>
                                    </div>
                                    <div class="form_options">
                                        <label for="fsurname">Apellidos: </label>
                                        <div class="input_zone">
                                            <input type="text" id="fsurname" name="fsurname" placeholder="Escriba su apellidos..">
                                            <small></small>
                                        </div>
                                    </div>
                                    <div class="form_options">
                                        <label for="femail">Email: </label>
                                        <div class="input_zone">
                                            <input type="email" id="femail" name="femail" placeholder="Escriba su email..">
                                            <small></small>
                                        </div>
                                    </div>
                                    <div class="form_options">
                                        <label for="fphone">Teléfono: </label>
                                        <div class="input_zone">
                                            <input type="tel" id="fphone" name="fphone" placeholder="Escriba su teléfono..">
                                            <small></small>
                                        </div>
                                    </div>
                                    <div class="form_options">
                                        <label for="fbirthday">Fecha de nacimiento: </label>
                                        <div class="input_zone">
                                            <input type="date" id="fbirthday" name="fbirthday" placeholder="Selecciona su fecha de nacimiento..">
                                            <small></small>
                                        </div>
                                    </div>
                                    <div class="form_options">
                                        <label for="faddress">Dirección: </label>
                                        <div class="input_zone">
                                            <input type="text" id="faddress" name="faddress" placeholder="Escriba su dirección..">
                                            <small></small>
                                        </div>
                                    </div>
                                    <div class="form_options">
                                        <label for="fgender">Genero: </label>
                                        <div class="input_zone">
                                            <input type="text" id="fgender" name="fgender" placeholder="Escriba su genero..">
                                            <small></small>
                                        </div>
                                    </div>
                                </fieldset>
                                <!--DATOS DEL LOGIN-->
                                <fieldset>
                                    <legend>Datos login </legend>
                                    <div class="form_options">
                                        <label for="fuser">usuario: </label>
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
                                </fieldset>
                                <fieldset>
                                    <legend>Aceptación de condiciones y envio del formulario</legend>
                                    <div class="form_terms">
                                        <label for="fterms">Acepto los términos y condiciones y la política de privacidad de la empresa.</label>
                                        <div class="input_terms">
                                            <input type="checkbox" name="fterms" id="fterms">
                                            <small></small>
                                        </div>
                                    </div>
                                    <div class="form_buttons">
                                        <button type="reset" value="Borrar">Borrar</button>
                                        <button type="submit" id="enviar" name="registrarse">Registrar</button>
                                    </div>
                                </fieldset>
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
            <script src="../assets/scripts/v_register.js"></script>
        </body>
</html>