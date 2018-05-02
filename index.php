<!DOCTYPE html>
<?php
session_start();
require_once 'funciones/base_datos.php';
?>   
<html>
    <head>
        <meta charset="UTF-8">
        <script src="librerias/jquery-3.2.1.min.js" type="text/javascript"></script>
        <title>Stucomusic-Indice</title>
        <link href="librerias/slick-1.8.0/slick/slick-theme.css" rel="stylesheet" type="text/css"/>
        <link href="librerias/slick-1.8.0/slick/slick.css" rel="stylesheet" type="text/css"/>
        <script src="librerias/slick-1.8.0/slick/slick.min.js" type="text/javascript"></script>
        <script src="javascript/index.js" type="text/javascript"></script>
		<link href="css/index.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div id="contrast"></div>
        <img class="header" id="imgStucomusic" src="img/logos/stucomusic.png" alt=""/>
        <h1 class="header" id="titulo">Bienvenido a stucomusic!</h1>
        <p class="header" id="info"> 
            <?php
            if (isset($_SESSION["info"])) {
                echo $_SESSION["info"];
                if ($_SESSION["info"] == "Has cerrado sesión, hasta pronto!") {
                    session_destroy();
                } else {
                    $_SESSION["info"] = null;
                }
            }
            if (isset($_POST["submitLogIn"])) {
                $user = $_POST["user"];
                $password = $_POST["passwd"];
                $userData = login_y_tipo($user, $password);
                $_SESSION["user"] = $user;
                $_SESSION["tipo"] = $userData;
                switch ($userData) {
                    case false: echo "Usuario o contraseña incorrectos.";
                        break;
                    case 1:
                        ?> Menu de administrador: <br><br>
                        <a href="2fan.php">Ir a la pagina de fan</a>
                        <a href="3local.php">Ir a la pagina de local</a>
                        <a href="4musics.php">Ir a la pagina de musico</a> <?php
                        break;
                    case 2: header("location: fan.php");
                        break;
                    case 3: header("location: local.php");
                        break;
                    case 4: header("location: musico.php");
                        break;
                }
            }
            ?>
        </p>
        <form class="forms" id="formLogIn" action="index.php" method="POST">
            <h3>Iniciar sesión</h3>
            <table id="tableLogIn">
                <tr><td class="inputLogin"><label for="user">Nombre de usuario</label></td><td><input type="text" id="user" name="user" required></td></tr>
                <tr><td class="inputLogin"><label for="passwd">Contraseña</label></td><td><input type="password" id="passwd" name="passwd" required></td></tr>
                <tr><td colspan="2" id="submitLogIn"><input type="submit" class="submit" name="submitLogIn" value="Entrar"><td></tr>
            </table>
        </form>
        <div id="signIn" class="forms">
            <h3>Eres un usuario nuevo?</h3>
            <form id="formSignIn" action="registro.php" method="POST"><input type="submit" class="submit" value="Registrarse"></form>
        </div>
        <div id="conciertosProximos">
            <h2>Conciertos proximos</h2>
            <div class="slick">
                <?php
                $array = seleccionar_conciertos_slick();
                $i = 0;
                foreach ($array as $concierto) {
                    $i++;
                    if ($i == count($array)) {
                        echo "$concierto</table></div>";
                    } else if ($i == 1) {
                        echo "<div><table><tr><th>Nombre concierto</th><th>Local</th><th>Muscio/s</th><th>Direccion</th><th>Fecha</th><th>Genero</th></tr>$concierto";
                    } else if ($i % 5 == 0) {
                        echo "$concierto</table></div><div><table><tr><th>Nombre concierto</th><th>Local</th><th>Muscio/s</th>"
                        . "<th>Direccion</th><th>Fecha</th><th>Genero</th></tr>";
                    } else {
                        echo $concierto;
                    }
                }
                ?>
            </div>
        </div>
        <footer>
            <a href="http://www.stucom.com/" target="_blank"><img src="img/logos/stucom.jpg" id="logoStu" class="logo"/></a>
            <a href="http://www.instagram.com/stucom/" target="_blank"><img src="img/logos/instagram.png" id="logoInsta" class="logo"/></a>
            <a href="http://www.facebook.com/stucombarcelona/" target="_blank"><img src="img/logos/facebook.png" id="logoFace" class="logo"/></a>
        </footer>
    </body>
</html>
