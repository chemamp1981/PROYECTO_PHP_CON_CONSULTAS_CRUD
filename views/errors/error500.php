
<?php
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

        <link rel="icon" type="image/x-icon" href="../../favicon.ico">
        <link rel="stylesheet" href="../../assets/css/estilos.css">



        <link href="https://fonts.googleapis.com/css2?family=Indie+Flower&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Dosis&family=Indie+Flower&display=swap" rel="stylesheet">

    </head>
    <body>
        <!-- HEADER -->
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
            </div>
        </header>

            
        <!-- CUERPO PRINCIPAL -->
            
        <main>
            <section class="dashboard">
                <div>
                    <h2>!Ups! Algo ha salido mal</h2>
                    <p>Nos disculpamos por las molestias, ....</p>
                    <p><a class="color_link" href="../../index.php">Haga clic aquí para volver al INICIO</a></p>
                </div>
                <div class="aviso_mensajes">
                    <?php
                    #Comprobar si hay mensajes de error
                    if (isset($_SESSION["mensaje_error"])) {
                        echo "<span class='error_message'>" . $_SESSION['mensaje_error'] . "</span>";
    
                        #Eliminar el mensaje de error
                        unset($_SESSION["mensaje_error"]);
                    }
                    ?>
                </div>
                <div>
                    <img class="reparar" src="../../assets/images/network-service-computer.png" alt="reparando el servidor" width="920" height="845">
                </div>
            </section>
        </main>

        <!--PIE DE PÁGINA -->

        <footer>
            <div class="footer">
                <p>Aviso legal - Polílitica de Privacidad - Politica de Cookies<br>Odontologia Luna.2024.<br>Diseño:chemamp</p>
            </div>
        </footer>
    </body>
</html>