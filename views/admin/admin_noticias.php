<?php
require_once __DIR__."/../../config/config.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(isset($_SESSION['all_data'])){
    $allData = $_SESSION['all_data'];
    
}else{
    $_SESSION['mensaje_error'] = " Lo sentimos debes iniciar sesión primero. ";
    header('location:../login.php');
    exit();
}

if(isset($_SESSION['all_users'])){
    $allUsers = $_SESSION['all_users'];
      
}else{
    $_SESSION['$mensaje_error'] = " Error al extraer los datos de todos los usuarios. ";
}

if(isset($_SESSION['usuarios'])){
    $users = $_SESSION['usuarios'];
  
}else{
    $_SESSION['$mensaje_error'] = " Error al extraer los datos de todos los usuarios. ";
}


if(isset($_SESSION['data_news'])){
    $newsData = $_SESSION['data_news'];
}else{
    $_SESSION['$mensaje_error'] = " No hay ninguna noticia publicada. ";
   
}

if(isset($_SESSION['each_news'])){
    $eachNews = $_SESSION['each_news'];     

}else{
    $_SESSION['$mensaje_error'] = " No hay ninguna noticia del usuario seleccionado. ";
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
                        <li><a href="../../controllers/c_admin/c_read_admin.php" target="_self">Usuarios-administracíon</a>
                            <ul class="sublista">
                                <li><a href="../../controllers/c_admin/c_read_admin.php">Crear usuario</a></li>
                            </ul></li>
                        <li><a href="../../controllers/c_admin/c_read_citas_admin.php" target="_self">Citas-administracíon</a></li>
                        <li><a href="../../controllers/c_admin/c_read_news_admin.php" target="_self" class="selected">Noticias-administracíon</a></li>
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
        <section>
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
        <section>
            <h2>NOTICIAS ADMINISTRACION</h2>
            <div class="table-color-format">
                <table>
                    <tr>
                        <th>USUARIOS</th>
                    </tr>
                    <?php
                    foreach ($allUsers as $value):?>
                        <tr>
                            <td><?php echo $value['usuario']?></td>
                            <td class="button-table">
                                <form id="create_noticia" action="../../controllers/c_admin/c_create_news_admin.php" method="post">
                                    <button class="edit" type="submit" name="edit_create">Crear noticia</button>
                                    <input type="hidden" name="idUser" value="<?php echo $value['idUser']?>">
                                </form>
                            </td>
                            <td class="button-table">
                                <form id="read_cita" action="../../controllers/c_admin/c_read_news_admin.php" method="post">
                                    <button class="edit" type="submit" name="read_news">Ver noticia</button>
                                    <input type="hidden" name="idUser" value="<?php echo $value['idUser']?>">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach;?>
                </table>
            </div>
        </section>

        <?php

        $create = null;

        if (isset($_GET['create'])) {
            $create = $_GET['create'];
        }

        if ($create === 'ok'):?>

        <section>
            <h2>CREAR NOTICIA PARA EL USUARIO: <?php echo $users[0]['usuario'];?></h2>
            <form action="../../controllers/c_admin/c_create_news_admin.php" method="post" enctype="multipart/form-data">
                <div class="form_options">
                    <label for="ftitle">Titulo noticia</label>
                    <div class="input_zone">
                        <input type="text" id="ftitle" name="ftitle">
                        <small></small>
                    </div>
                </div>
                <div class="form_options">
                    <label for="fimage">Selecciona una imagen</label>
                    <div class="input_zone">
                        <input type="file" id="fimage" name="fimage">
                        <small></small>
                    </div>
                </div>
                <div class="form_options">
                    <label for="ftext">Descripción noticia</label>
                    <div class="input_zone">
                        <textarea placeholder="Descripción de la noticia" name="ftext" id="ftext" minlength="150" rows="8" cols="100"></textarea>
                        <div class="contador" id="contador">Min 150/0</div>
                        <small></small>
                    </div>
                </div>
                <div class="form_options">
                    <label for="fdate"></label>
                    <div class="input_zone">
                        <input type="date" id="fdate" name="fdate">
                        <small></small>
                    </div>
                </div>
                </div>
                    <div class="form_buttons">
                        <button type="submit" id="crear" name="create_news">Publicar noticia</button>
                        <input type="hidden" name="idUser" value="<?php echo $users[0]['idUser']?>">
                    </div>
                </div>
            </form>
        </section>
        <?php endif;
      
        $news = null;
        if (isset($_GET['news'])) {
            $news = $_GET['news'];
        }
      

        if ($news === 'ok'):?>
            <section>
                <h2>NOTICIAS DEL USUARIO: <?php echo $newsData[0]['usuario'];?></h2>
                        <?php
                        foreach ($newsData as $value):?>
                           
                        <div class='item'>
                            <h2><?php echo $value['titulo'];?></h2>
                        </div>

                        <div class='item'>
                            <p><?php echo $value['texto'];?><span class='publish_date'>Fecha de publicación:</span><?php echo $value['fecha'];?></p>
                        </div>
                      
                        <div class='item'>
                            <img src='../../assets/images/sql_img/<?php echo $value['imagen'];?>' alt='' width='400'>
                            <div class="button_row_center">
                                <div>
                                    <form id="edNews" action="../../controllers/c_admin/c_update_news_admin.php" method="post">
                                        <button class="edit" type="submit" name="edNews">Editar noticia</button>
                                        <input type="hidden" name="idNews" value="<?php echo $value['idNoticia']?>">
                                    </form>
                                </div>
                                <div>
                                    <form id="borrar" action="../../controllers/c_admin/c_delete_news_admin.php" method="post">
                                        <button class="edit" type="submit" name="borrar">Borrar noticia</button>
                                        <input type="hidden" name="idNoticia" value="<?php echo $value['idNoticia']?>">
                                        <input type="hidden" name="idUser" value="<?php echo $value['idUser']?>">
                                    </form>
                                </div>
                            </div>
                            <hr class="line">
                        </div>
                            
                        <?php endforeach;?>
            </section>
        <?php endif;
        $update = null;
        if (isset($_GET['update_news'])) {
            $update = $_GET['update_news'];
        }
        if ($update === 'ok'):?>
            <section>
                <h2>MODIFICAR NOTICIA</h2>
                <form action="../../controllers/c_admin/c_update_news_admin.php" method="post" enctype="multipart/form-data">
                    <div class="form_options">
                        <label for="ftitle">Titulo noticia</label>
                        <div class="input_zone">
                            <input type="text" id="ftitle" name="ftitle" value="<?php echo $eachNews[0]['titulo'];?>">
                            <small></small>
                        </div>
                    </div>
                    <div class="form_options">
                        <label for="fimage">Selecciona una imagen</label>
                        <div class="input_zone">
                            <input type="file" id="fimage" name="fimage" value="">
                            <small></small>
                        </div>
                    </div>
                    <div class="form_options">
                        <label for="ftext">Descripción noticia</label>
                        <div class="input_zone">
                            <textarea name="ftext" id="ftext" rows="8" cols="100"><?php echo $eachNews[0]['texto'];?></textarea>
                            <small></small>
                        </div>
                    </div>
                    <div class="form_options">
                        <label for="fdate"></label>
                        <div class="input_zone">
                            <input type="date" id="fdate" name="fdate" value="<?php echo $eachNews[0]['fecha'];?>">
                            <small></small>
                        </div>
                    </div>
                    </div>
                        <div class="form_buttons">
                            <button type="submit" name="update_news">Modificar</button>
                            <input type="hidden" name="idNoticia" value="<?php echo $eachNews[0]['idNoticia'];?>">
                            <input type="hidden" name="idUser" value="<?php echo $eachNews[0]['idUser'];?>">
                        </div>
                    </div>
                </form>
            </section>
        <?php endif;?>
    </main>
    <footer>
        <div class="footer">
            <p>Aviso legal - Polílitica de Privacidad - Politica de Cookies<br>Odontologia Luna.2024.<br>Diseño:chemamp</p>
        </div>
    </footer>
    <script src="../../assets/scripts/v_noticias.js"></script>
   <script src="../../assets/scripts/textaContador.js"></script>
</body>

</html> 