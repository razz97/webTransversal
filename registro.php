<?php
require_once "funciones/base_datos.php";
require_once "funciones/funciones.php";
session_start();
?>
<html>
    <head>
        <meta charset = "UTF-8">
        <link href = "css/registro.css" rel = "stylesheet" type = "text/css"/>
        <script src="librerias/jquery-3.2.1.min.js" type="text/javascript"></script>
        <script src="javascript/registro.js" type="text/javascript"></script>
        <title>Stucomusic-Registro</title>
    </head>
    <body>
		<div id="contrast"></div>
        <img src="img/logos/stucomusic.png" class="header" id="imgStucomusic" alt=""/>
        <h1 class="header" id="titulo" >Página de registros</h1>			
        <p class="header" id="info">
			<?php
			if (isset($_POST["fan"])) {
				$addFan = comprobacion_alta_fan($_POST);
				if (!is_string($addFan)) {
					$_SESSION["user"] = $_POST["user"];
					$_SESSION["tipo"] = 2;
					$_SESSION["regis"] = true;
					header("location: fan.php");
				} else {
					echo $addFan;
					echo "<input id='infoFrom'style='visibility:hidden' value='fan'>";
				}
			}
			if (isset($_POST["musico"])) {
				$addMusico = comprobacion_alta_musico($_POST);
				if (!is_string($addMusico)) {
					$_SESSION["user"] = $_POST["user"];
					$_SESSION["tipo"] = 4;
					$_SESSION["regis"] = true;
					header("location: musico.php");
				} else {
					echo $addMusico;
					echo "<input id='infoFrom'style='visibility:hidden' value='musico'>";
				}
			}
			if (isset($_POST["local"])) {
				$addLocal = comprobacion_alta_local($_POST);
				if (!is_string($addLocal)) {
					$_SESSION["user"] = $_POST["user"];
					$_SESSION["tipo"] = 3;
					$_SESSION["regis"] = true;
					header("location: local.php");
				} else {
					echo $addLocal;
					echo "<input id='infoFrom'style='visibility:hidden' value='local'>";
				}
			}
			?>
        </p>
        <div id="optionsRegister"><h2>Qué tipo de usuario quieres registrar?</h2>   
            <button id="btnMusic" class="superBoton" >MUSICO</button>
            <button id="btnFan" class="superBoton" >FAN</button>
            <button id="btnLocal" class="superBoton" >LOCAL</button>
        </div>
        <div id="register">
            <form class="form" action="registro.php" method="POST">
                <div id="registerUser">	<h2>Datos de usuario</h2>
                    <table>
                        <tr><td><label for="user">Nombre de usuario:</label></td><td><input type="text" name="user" id="user" maxlength="20"  required></td></tr>
                        <tr><td><label for="password">Contraseña:</label></td><td><input type="password" name="password" id="password" minlength="6" maxlength="12" required></td></tr>
                        <tr><td><label for="password2">Confirmar contrasena:</label></td><td><input type="password" name="password2" id="password2" minlength="6" maxlength="12" required></td></tr>
                        <tr><td><label for="mail">Email: </label></td><td><input type="email" name="mail" id="mail" maxlength="40" required></td></tr>
                        <tr><td><label for="tel">Teléfono: </label></td><td><input type="text" name="tel" id="tel" maxlength="13" required></td></tr>
                        <tr><td><label for="img">Imagen</label></td><td><input type="text" name="img" id="img"></td></tr>
                        <tr><td><label for=provincia>Selecciona provincia:</label></td><td><select id="provincia">
                                    <option disabled selected >Provincia:</option>
									<?php
									$select = select_alta_provincia();
									while ($fila = mysqli_fetch_assoc($select)) {
										echo "<option>" . $fila["provincia"] . "</option>";
									}
									?>      
                                </select></td></tr>
                        <tr id="todoCiudad"><td><label for="selectCiudad" >Ciudad:</label></td><td><select name="ciudad" id="selectCiudad" required></select></td></tr>
                    </table>
                </div>
                <input type="submit" name="submitForm" id="submitForm" value="Registrar"></form>	
        </div>
        <button id="backOptions" class="back">Atras</button>
        <form action="index.php" method="POST"><input id="backIndex" class="back" type="submit" value="Volver al indice"></form>
        <footer>
            <a href="http://www.stucom.com/" target="_blank"><img src="img/logos/stucom.jpg" id="logoStu" class="logo"/></a>
            <a href="http://www.instagram.com/stucom/" target="_blank"><img src="img/logos/instagram.png" id="logoInsta" class="logo"/></a>
            <a href="http://www.facebook.com/stucombarcelona/" target="_blank"><img src="img/logos/facebook.png" id="logoFace" class="logo"/></a>
        </footer>
    </body>
</html>