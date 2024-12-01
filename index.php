<?php
require_once "config/config.php";


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(isset($_SESSION['all_data'])){
    $allData = $_SESSION['all_data'];
}
?>

<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Odontologia Luna</title>

        <link rel="icon" type="image/x-icon" href="./favicon.ico">
        <link rel="stylesheet" href="./assets/css/estilos.css">



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
                        <img src="./assets/images/luna.png" alt="imagen de una luna" width="300" height="302">
                    </div>
                </div>

                <!--Menu de navegación-->
            
                <div class="menu">
                    <nav>
                        <ul class="lista">
                            <?php if(isset($_SESSION['all_data']['rol']) && $_SESSION['all_data']['rol'] === 'user'):?>
                            <li class="usuario">Usuario: <?php echo $allData['usuario'];?></li>
                            <li><a href="#" target="_self" class="selected">Inicio</a></li>
                            <li><a href="./controllers/c_users/c_read_noticias.php" target="_self">Noticias</a></li>
                            <li><a href="./controllers/c_users/c_citas.php" target="_self">Citaciones</a></li>
                            <li><a href="./views/users/perfil.php" target="_self">Perfil</a></li>
                            <li><a href="./controllers/c_logout.php">Cerrar sesión</a></li>
                            <?php elseif(isset($_SESSION['all_data']['rol']) && $_SESSION['all_data']['rol'] === 'admin'):?>
                            <li class="usuario">Usuario: <?php echo $allData['usuario'];?></li>
                            <li><a href="#" target="_self" class="selected">Inicio</a></li>
                            <li><a href="./controllers/c_users/c_read_noticias.php" target="_self">Noticias</a></li>
                            <li><a href="./controllers/c_admin/c_read_admin.php" target="_self">Usuarios-administracíon</a>
                                <ul class="sublista">
                                    <li><a href="./controllers/c_admin/c_read_admin.php">Crear usuario</a></li>
                                </ul></li>
                            <li><a href="./controllers/c_admin/c_read_citas_admin.php" target="_self">Citas-administracíon</a></li>
                            <li><a href="./controllers/c_admin/c_read_news_admin.php" target="_self">Noticias-administracíon</a></li>
                            <li><a href="./views/users/perfil.php" target="_self">Perfil</a></li>
                            <li><a href="./controllers/c_logout.php">Cerrar sesión</a></li>
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
            <!--1° Sección de la pagina de inicio-->
            <div class="aviso_conn">
            AVISO:
            <?php
                  require_once  'controllers/db_conn.php';
            ?>
            </div>
            <section>

                <div class="item">
                    <h2>Odontología infantil.</h2>
                </div>
                <div class="item">
                    <p>La odontología infantil, también conocida como odontopediatría u odontología pediátrica, estudia la prevención y el tratamiento de las enfermedades bucodentales en niños y adolescentes. El encargado de realizar este seguimiento en niños y adolescentes es el odontopediatra. Dicho especialista también adquiere la formación idónea para tratar a bebés y niños, a través de ciertas habilidades, estrategias y técnicas de manejo de la conducta y del comportamiento.

                        Lo cierto es que, aunque el niño no presente problemas bucodentales, lo idóneo es realizar una primera revisión odontológica a los 6 meses. Durante esta visita, el odontopediatra instruye los papás sobre cómo higienizar estos primeros dientes: deberá limpiárselos con una gasita o con un cepillo infantil blando, hasta los 2-3 años aproximadamente. A esta edad el niño ya es capaz de coger el cepillo y, acompañándose de la mano del progenitor, realizar un cepillado correcto.

                        A los 6 años el niño ya debería cepillárselos solo, bajo la supervisión del progenitor para asegurar que utiliza la técnica adecuada y sus dientes quedan limpios. El niño debe aprender a coger hábitos saludables y ser lo más autónomo posible.

                        Es en las revisiones tempranas cuando se pueden identificar los primeros signos y síntomas asociados a posibles patologías y realizar tratamientos sencillos en caso de ser necesario. Durante todo este proceso de cambios es obligatorio realizar un buen seguimiento odontológico. ¡Todo lo que se pueda corregir o prevenir desde bien pequeño, mejor para el futuro adulto!</p>
                </div>
                <div class="item">
                    <img src="./assets/images/odontologia_infantil.png" alt="infantil" width="412" height="307">
                </div>

                <div class="bisel-abajo"></div>
            </section>

            <!--2 Sección de la pagina de inicio-->

            <section class="style-section">


                <div class="item">
                    <h2>Expodental 2024.</h2>
                </div>
                <div class="item">
                    <p>IFEMA MADRID y Fenin han presentado ante las empresas del sector la décimo séptima edición de Expodental, Salón Internacional de Equipos, Productos y Servicios Dentales, que se celebrará del 14 al 16 de marzo en los pabellones 4, 6 y 8 del Recinto Ferial.

                        Más de un centenar de profesionales de 80 empresas líderes del sector dental, se han dado cita en el Recinto Ferial, con motivo de la presentación de la próxima edición de EXPODENTAL 2024, Salón Internacional de Equipos, Productos y Servicios Dentales. La feria, organizada por IFEMA MADRID, en colaboración con la Federación Española de Empresas de Tecnología Sanitaria, Fenin, celebrará su XVII edición los días 14 al 16 de marzo (de jueves a sábado) bajo el lema “Tecnología al servicio de los profesionales”.

                        El acto, que ha contado con las intervenciones de la directora de negocio de IFEMA MADRID, Arancha Priede; la secretaria general de Fenin, Margarita Alfonsel; el presidente del sector dental de Fenin, Luis Garralda, y la directora de EXPODENTAL, Ana Rodríguez, ha ofrecido un amplio detalle de los contenidos, así como de la estrategia, ya en marcha, para impulsar la representatividad e internacionalidad de esta feria líder y con una fuerte orientación al negocio, que se perfila, una vez más, como el principal punto de encuentro de todos los profesionales del sector de la Odontología.

                        La presentación también ha contado con la participación de Roberto Rosso, fundador y presidente de Key-Stone, que ha realizado un análisis de la situación actual del mercado dental.</p>
                </div>
                <div class="item">
                    <img src="./assets/images/Expodental_2024.png" alt="expo" width="691" height="460">
                </div>

            </section>

            <!--3 Sección de la pagina de inicio-->

            <section>
                <div class="bisel-arriba"></div>
                <div class="item">
                    <h2>Las mejores pastas dentales.</h2>
                </div>
                <div class="item">
                    <p>A diario consumimos multitud de alimentos que resultan agresivos para el esmalte. Café, té, limón e incluso especias como el curry , pueden provocar la aparición de manchas y esa tonalidad amarillenta poco favorecedora que cualquiera querría evitar a toda costa. Uno de los métodos más efectivos es el blanqueamiento dental desde casa, por supuesto controlado y supervisado por un dentista; sin embargo, no siempre queremos invertir el dinero que cuestan estos tratamientos y buscamos algo más asequible aunque ofrezca resultados progresivos.
                        Las pastas de dientes blanqueantes suponen un truco muy sencillo de utilizar y de incluir en nuestra rutina de higiene dental diaria para combatir las manchas, evitar que se formen nuevas y, por supuesto, garantizar una limpieza en profundidad de la boca. Lo mejor de todo es que la mayoría de las mejores pastas de dientes blanqueantes 2023 están al alcance de nuestra mano por un precio muy conveniente.
                        Nuestra apuesta segura es la pasta de dientes blanqueante de Oral-B Encías y Esmalte Repair. Una alternativa que no solo ayuda a reparar el esmalte en dos semanas con un uso continuado, sino que protege y rejuvenece las encías . Con su fórmula y tecnología única ActivRepair repara el esmalte y lo guarda de la erosión ácida. Envase de 75ml .
                    </p>
                </div>
                <div class="item">
                    <img src="./assets/images/hombrepastadedientes.png" alt="hombrepastadedientes" width="701" height="500">
                </div>

            </section>

        </main>
        <!--PIE DE PÁGINA -->
        <footer>
            <div class="footer">
                <p>Aviso legal - Polílitica de Privacidad - Politica de Cookies.<br>Odontologia Luna.2024.<br>Diseño:chemamp</p>
            </div>
        </footer>
    </body>
</html>