<?php

require_once __DIR__."/../config/config.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(isset($_SESSION['all_data'])){
    $allData = $_SESSION['all_data'];
    
}

if(isset($_SESSION['see_news'])){
    $seeNews = $_SESSION['see_news'];
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
                        <?php if(isset($_SESSION['all_data']['rol']) && $_SESSION['all_data']['rol'] === 'user'): ?>
                        <li class="usuario">Usuario: <?php echo $allData['usuario'];?></li>
                        <li><a href="../index.php" target="_self">Inicio</a></li>
                        <li><a href="#" target="_self" class="selected">Noticias</a></li>
                        <li><a href="../controllers/c_users/c_citas.php" target="_self">Citaciones</a></li>
                        <li><a href="./users/perfil.php" target="_self">Perfil</a></li>
                        <li><a href="../controllers/c_logout.php">Cerrar sesión</a></li>
                        <?php elseif(isset($_SESSION['all_data']['rol']) && $_SESSION['all_data']['rol'] === 'admin'): ?>
                        <li class="usuario">Usuario: <?php echo $allData['usuario'];?></li>
                        <li><a href="../index.php" target="_self">Inicio</a></li>
                        <li><a href="#" target="_self" class="selected">Noticias</a></li>
                        <li><a href="../controllers/c_admin/c_read_admin.php" target="_self">Usuarios-administracíon</a>
                            <ul class="sublista">
                                <li><a href="../controllers/c_admin/c_read_admin.php">Crear usuario</a></li>
                            </ul></li>
                        <li><a href="../controllers/c_admin/c_read_citas_admin.php" target="_self">Citas-administracíon</a></li>
                        <li><a href="../controllers/c_admin/c_read_news_admin.php" target="_self">Noticias-administracíon</a></li>
                        <li><a href="./users/perfil.php" target="_self">Perfil</a></li>
                        <li><a href="../controllers/c_logout.php">Cerrar sesión</a></li>
                        <?php else:?>
                         <li><a href="../index.php" target="_self">Inicio</a></li>
                         <li><a href="#" target="_self" class="selected">Noticias</a></li>
                        <li><a href="./registro.php" target="_self">Registro</a></li>
                        <li><a href="./login.php" target="_self">Login</a></li>
                        <?php endif;?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!--Cuerpo de la pagina de inicio-->

    <main>
        <div class="aviso_mensajes">
            <?php
            #Comprobar si hay mensajes de error
            if(isset($_SESSION["mensaje_error"])){
                echo "<span class='error_message'>" . $_SESSION['mensaje_error'] . "</span>";

                #Eliminar el mensaje de error
                unset($_SESSION["mensaje_error"]);
            }
            ?>
        </div>
        <?php 
        $news = null;
        if(isset($_GET['news'])){
            $news = $_GET['news'];
        }
        if($news === 'ok'):?>
        <div>
                <?php foreach($seeNews as $key):?>
                <?php echo 
                "<section>
                    
                        <div class='item'>
                            <h2>". $key['titulo'] ."</h2>
                        </div>

                        <div class='item'>
                            <p>". $key['texto']."<span class='publish_date'>Fecha de publicación:</span>".$key['fecha']."</p>
                        </div>

                        <div class='item'>
                            <img src='../assets/images/sql_img/" . $key['imagen'] ."' alt='' width='400'>
                        </div>
                        <div><hr class='line_noticias'></div>
                        

                </section>"?>
                <?php endforeach;?>
        </div>
        <?php endif; ?>
    </main>
    <footer>
        <div class="footer">
            <p>Aviso legal - Polílitica de Privacidad - Politica de Cookies<br>Odontologia Luna.2024.<br>Diseño:chemamp</p>
        </div>
    </footer>

</body>

</html>