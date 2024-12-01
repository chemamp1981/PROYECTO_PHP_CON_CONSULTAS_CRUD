<?php
require_once __DIR__ . "/../../config/config.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['all_data'])) {
    $allData = $_SESSION['all_data'];

} else {
    $_SESSION['mensaje_error'] = " Lo sentimos debes iniciar sesión primero.";
    header('location:../login.php');
}

if(isset($_SESSION['all_users'])){
    $allUsers = $_SESSION['all_users'];

 
}else{
    $_SESSION['$mensaje_error'] = " Error al extraer los datos de todos los usuarios.";
}

if(isset($_SESSION['ver_citas'])){
    $verCitas = $_SESSION['ver_citas'];

   // echo json_encode($verCitas);

}else{
    $_SESSION['$mensaje_error'] = " Error al extraer los datos de ver citas.";
}

if(isset($_SESSION['users'])){
    $users = $_SESSION['users'];
}else{
    $_SESSION['$mensaje_error'] = " Error al extraer los datos de los usuarios.";
}

if(isset($_SESSION['each_cita'])){
    $eachCita = $_SESSION['each_cita'];
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
                        <?php if (isset($_SESSION['all_data']['rol']) && $_SESSION['all_data']['rol'] === 'admin'): ?>
                            <li class="usuario">Usuario: <?php echo $allData['usuario']; ?></li>
                            <li><a href="../../index.php" target="_self">Inicio</a></li>
                            <li><a href="../../controllers/c_users/c_read_noticias.php" target="_self">Noticias</a></li>
                            <li><a href="../../controllers/c_admin/c_read_admin.php" target="_self">Usuarios-administracíon</a>
                                <ul class="sublista">
                                    <li><a href="../../controllers/c_admin/c_read_admin.php">Crear usuario</a></li>
                                </ul>
                            <li><a href="../../controllers/c_admin/c_read_citas_admin.php" target="_self" class="selected">Citas-administracíon</a></li>
                            <li><a href="../../controllers/c_admin/c_read_news_admin.php" target="_self">Noticias-administracíon</a></li>
                            <li><a href="../users/perfil.php" target="_self">Perfil</a></li>
                            <li><a href="../../controllers/c_logout.php">Cerrar sesión</a></li>
                        <?php else: ?>
                            <li><a href="#" target="_self" class="selected">Inicio</a></li>
                            <li><a href="./controllers/c_users/c_read_noticias.php" target="_self">Noticias</a></li>
                            <li><a href="./views/registro.php" target="_self">Registro</a></li>
                            <li><a href="./views/login.php" target="_self">Login</a></li>
                        <?php endif; ?>
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
            <h2>CITAS USUARIOS</h2>
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
                                <form id="create_cita" action="../../controllers/c_admin/c_create_citas_admin.php" method="post">
                                    <button class="edit" type="submit" name="create_cita">Crear citas</button>
                                    <input type="hidden" name="idUser" value="<?php echo $value['idUser']?>">
                                </form>
                            </td>
                            <td class="button-table">
                                <form id="read_cita" action="../../controllers/c_admin/c_read_citas_admin.php" method="post">
                                    <button class="edit" type="submit" name="read_cita">Ver citas</button>
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

            <section class="dashboard">
                <h2>CREAR CITAS PARA EL USUARIO: <?php echo $users[0]['usuario']; ?></h2>
                <form action="../../controllers/c_admin/c_create_citas_admin.php" method="post">
                    <div>
                        <div class="form_options">
                            <label for="fappoimentdate">Fecha de la cita </label>
                            <div class="input_zone">
                                <input type="date" id="fappoimentdate" name="fappoimentdate">
                                <small></small>
                            </div>
                        </div>
                        <div class="form_options">
                            <label for="freason">Motivo de la cita </label>
                            <div class="input_zone">
                                <textarea type="textarea" id="freason" name="freason" placeholder="Escribe tu cita..."></textarea>
                                <small></small>
                            </div>
                        </div>
                            <div class="form_buttons">
                                <button type="submit" id="crear" name="crear_cita">Crear cita</button>
                                <input type="hidden" name="idUser" value="<?php echo $users[0]['idUser']?>">
                            </div>
                        </div>
                    </div>
                </form>
            </section>
        <?php endif;
      
        $citas = null;
        if (isset($_GET['citas'])) {
            $citas = $_GET['citas'];
        }
      

        if ($citas === 'ok'):?>
            <section class="dashboard">
                <h2>CITAS DEL USUARIO: <?php echo $verCitas[0]['usuario'];?></h2>
                <div class="table-color-format">
                    <table>
                        <tr>
                            <th>FECHA CITA</th>
                            <th>RESUMEN DE CITA</th>
                        </tr>
                        <?php
                        foreach ($verCitas as $value):?>
                            <tr>
                                <td><?php echo $value['fecha_cita']?></td>
                                <td><?php echo $value['motivo_cita']?></td>
                                <td class="button-table">
                                    <form  action="../../controllers/c_admin/c_update_cita_admin.php" method="post">
                                        <button class="edit" type="submit" name="edCita" id="edCita">Editar cita</button>
                                        <input type="hidden" name="idCita" value="<?php echo $value['idCita']?>">
                                    </form>
                                </td>
                                <td class="button-table">
                                    <form action="../../controllers/c_admin/c_delete_citas_admin.php" method="post">
                                        <button class="edit" type="submit" name="borrar" id="borrarCita">Borrar cita</button>
                                        <input type="hidden" name="idCita" value="<?php echo $value['idCita']?>">
                                        <input type="hidden" name="idUser" value="<?php echo $value['idUser']?>">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach;?>
                    </table>
                </div>
            </section>
        <?php endif;
        $modify = null;
        if (isset($_GET['modify'])) {
            $modify = $_GET['modify'];
        }
        if ($modify === 'ok'):?>
            <section class="dashboard">
                <h2>Modificar cita </h2>
                <form action="../../controllers/c_admin/c_update_cita_admin.php" method="post">
                    <div>
                        <div class="form_options">
                            <label for="fappoimentdate">Fecha de la cita </label>
                            <div class="input_zone">
                                <input type="date" id="fappoimentdate" name="fappoimentdate" value="<?php echo $eachCita[0]['fecha_cita'];?>">
                                <small></small>
                            </div>
                        </div>
                        <div class="form_options">
                            <label for="freason">Motivo de la cita </label>
                            <div class="input_zone">
                                <textarea type="textarea" id="freason" name="freason"><?php echo $eachCita[0]['motivo_cita']?></textarea>
                                <small></small>
                            </div>
                        </div>
                            <div class="form_buttons">
                                <button class="edit" type="submit" name="modificar">Modificar</button>
                                <input type="hidden" name="idCita" value="<?php echo $eachCita[0]['idCita']?>"> 
                                <input type="hidden" name="idUser" value="<?php echo $eachCita[0]['idUser']?>">
                            </div>
                        </div>
                    </div>
                </form>
            </section>
        <?php endif;?>
    </main>
    <footer>
        <div class="footer">
            <p>Aviso legal - Polílitica de Privacidad - Politica de Cookies<br>Odontologia Luna.2024.<br>Diseño:chemamp
            </p>
        </div>
    </footer>
    <script src="../../assets/scripts/diferencia_fecha.js"></script>
    <script src="../../assets/scripts/v_citas.js"></script>
</body>

</html>