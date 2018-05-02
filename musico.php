<?php
session_start();
require_once 'funciones/base_datos.php';
require_once 'funciones/funciones.php';
if (isset($_SESSION["user"]) && ($_SESSION["tipo"] == 4 || $_SESSION["tipo"] == 1)) {
	?>
	<html>
		<head>
			<meta charset="UTF-8">
			<link href="css/usuarios.css" rel="stylesheet" type="text/css"/>
			<script src="librerias/jquery-3.2.1.min.js" type="text/javascript"></script>
			<script src="javascript/usuarios.js" type="text/javascript"></script>
			<title>Stucomusic-Fan</title>
		</head>
		<body>
			<?php
			$userData = mysqli_fetch_assoc(select_usuario($_SESSION["user"], "musico"));
			if (isset($_SESSION["info"])) {
				echo "<p id='info'>" . $_SESSION["info"] . "</p>";
				$_SESSION["info"] = null;
			}
			if (isset($_POST["logOut"])) {
				$_SESSION["info"] = "Has cerrado sesión, hasta pronto!";
				header("location: index.php");
			}
			?>
			<div id="backgroundHeader"></div>
			<img src="img/logos/stucomusic.png" alt="" id="imgStucomusic" class="header"/>
			<h1 id="titulo" class="header">Stucomusic - Home</h1>
			<form action="musico.php" method="POST" ><input type="submit" name="logOut" id="logOut" class="header" value="Cerrar sessión"></form>
			<div id="backgroundSide"></div>
			<h2 id="titleDatos"class="leftSide">Tus datos</h2>
			<table id="tableDatos" class="leftSide" style="font-size:1.1vw;">
				<tr><td>Nombre de usuario: </td><td><?php echo $userData["NOMBRE_USUARIO"] ?></td></tr>
				<tr><td>Nombre artistico:</td><td><?php echo $userData["NOMBRE_ARTISTICO"] ?></td></tr> 
				<tr><td>Numero componentes: </td><td><?php echo $userData["NUMERO_COMPONENTES"] ?></td></tr> 
				<tr><td>Genero: </td><td><?php echo $userData["GENERO"] ?></td></tr> 
				<tr><td>Nombre: </td><td><?php echo $userData["NOMBRE"] ?></td></tr> 
				<tr><td>Apellidos: </td><td><?php echo $userData["APELLIDO"] ?></td></tr>
				<tr><td>Email: </td><td><?php echo $userData["EMAIL"] ?></td></tr> 
				<tr><td>Telefono: </td><td><?php echo $userData["TELEFONO"] ?></td></tr> 
				<tr><td>Ciudad </td><td><?php echo $userData["CIUDAD"] ?></td></tr> 
				<tr><td>Imagen</td><td></td></tr> 
			</table>
			<button id="showOptionsEdit" class="menuButton">Editar tus datos</button>
			<button id="showRequest" class="menuButton">Ver tus peticiones</button>
			<button id="showRegister" class="menuButton">Inscribirse a un concierto</button>
			<div id="backgroundMain"></div>
			<div id="optionsEdit" class="functionality">
				<h2 id="titleOptionEdit" class="optionEdit">Que datos quieres editar?</h2>
				<form action="musico.php" method="POST" id="formOptionsEdit" >
					<table id="tableOptionEdit" class="optionEdit">
						<tr><td><input type="checkbox" name="usr">Nombre usuario</td>
							<td><input type="checkbox" name="pass"> Contraseña</td>
							<td><input type="checkbox" name="name"> Nombre</td></tr>
						<tr><td><input type="checkbox" name="ape"> Apellidos</td>
							<td><input type="checkbox" name="mail"> Email</td>
							<td><input type="checkbox" name="tel"> Teléfono</td></tr>
						<tr><td><input type="checkbox" name="img"> Imagen</td>
							<td><input type="checkbox" name="ciu"> Ciudad</td>
							<td><input type="checkbox" name="art"> Nombre artístico</td></tr>
						<tr><td><input type="checkbox" name="num"> Número componentes</td>
							<td><input type="checkbox" name="web"> Página Web</td>
							<td><input type="checkbox" name="gen"> Género</td></tr>
					</table>
					<input type="submit" name="optionSubmitEdit" id="optionSubmitEdit" value="Seleccionar" style="top: 50vh">
				</form>
			</div>
			<?php
			if (isset($_POST["optionSubmitEdit"]) && !isset($_POST["backOption"])) {
				echo "<input id='infoForm'style='visibility:hidden' value='optionSelected' >";
				echo "<form action='musico.php' method='POST' id='formEdit' class='functionality'>";
				echo "<h2>Editar tus datos</h2>";
				formulario_editar_usuario();
				if (isset($_POST["ape"])) {
					echo "<p>Apellido: <input type='text' name='surname' required></p>";
				} if (isset($_POST["art"])) {
					echo "<p>Nombre artistico <input type='text' name='art'></p>";
				} if (isset($_POST["num"])) {
					echo "<p>Numero de componentes <input type='number' name='num'></p>";
				} if (isset($_POST["web"])) {
					echo "<p>Pagina web <input type='text' name='web'></p>";
				} if (isset($_POST["gen"])) {
					echo "<p>Género: <select name='gen' id='genre' required><option disabled selected >Selecciona un genero</option>";
					$selectgenre = select_genre();
					while ($fila = mysqli_fetch_assoc($selectgenre)) {
						echo "<option value = " . $fila["idgenero"] . ">";
						echo $fila["nombre"];
						echo "</option>";
					}
				}
				echo "</select><br><input type='submit' name='edit' value='Editar'></form><form action='musico.php' method='POST' id='backOption' class='functionality'>"
				. "<input type='submit' name='backOption'  value='Atras'></form>";
			} if (isset($_POST["edit"])) {
				$array = array_editar_usuario($_POST);
				if (isset($_POST["surname"]) && $array) {
					$datos = "apellido='" . $_POST["surname"] . "'";
					array_push($array, $datos);
				}
				if (isset($_POST["art"]) && $array) {
					$datos = "nombre_artistico='" . $_POST["art"] . "'";
					array_push($array, $datos);
				}
				if (isset($_POST["num"]) && $array) {
					$datos = "numero_componentes='" . $_POST["num"] . "'";
					array_push($array, $datos);
				}
				if (isset($_POST["gen"]) && $array) {
					$datos = "idgenero=" . $_POST["gen"];
					array_push($array, $datos);
				}
				if (isset($_POST["web"]) && $array) {
					$datos = "pagina_web='" . $_POST["web"] . "'";
					array_push($array, $datos);
				}
				if ($array) {
					if (editar_usuario($array, $_SESSION["user"], "musico")) {
						$_SESSION["info"] = "<p class='info'>Cambios modificados correctamente</p>";
						if (isset($_POST["user"])) {
							$_SESSION["user"] = $_POST["user"];
						}
						header("location: musico.php");
					} else {
						echo "<p class='info'>Ha habido un error, no se ha modificado ningun dato</p>";
					}
				} else {
					echo "<p class='info'>Ha habido un error, no se ha modificado ningun dato</p>";
				}
			}
			?>
			<div id="request" class="functionality">
				<h2>Tus peticiones a conciertos</h2>
				<?php
				$peticiones = seleccionar_peticiones_musico($userData["IDUSUARIO"]);
				echo "<table><tr><th>Concierto</th><th>Fecha</th><th>Local</th><th>Dirección</th><th>Estado</th></tr>$peticiones</table>";
				?>
			</div>
			<div id="register" class="functionality">
				<h2>Conciertos de tu género</h2><br>
				<table>
					<tr><th>Concierto</th><th>Local</th><th>Propuesta economica</th><th>Fecha</th><th>Inscribirse</th></tr>
					<?php echo seleccionar_conciertos_musico($userData["IDUSUARIO"]); ?>
				</table>
			</div>
			<?php
			if (isset($_POST["aceptar"])) {
				inscribirse($_POST["idconcierto"], $userData["IDUSUARIO"]);
				echo "<p class='info'>Has enviado una peticion al local</p>";
			}
			?>
			<div id="backgroundAds"></div>
			<img src="img/anuncios/albondigas.gif" alt="" id="upperAd"/>
			<img src="img/anuncios/chicken.gif" alt="" id="lowerAd"/>
			<footer>
				<a href="http://www.stucom.com/" target="_blank"><img src="img/logos/stucom.jpg" id="logoStu" class="logo"/></a>
				<a href="http://www.instagram.com/stucom/" target="_blank"><img src="img/logos/instagram.png" id="logoInsta" class="logo"/></a>
				<a href="http://www.facebook.com/stucombarcelona/" target="_blank"><img src="img/logos/facebook.png" id="logoFace" class="logo"/></a>
			</footer>
		</body>
	</html>
	<?php
} else {
	$_SESSION["info"] = "Debes iniciar sesión";
	header("location: index.php");
}	