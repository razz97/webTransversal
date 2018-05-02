<?php
session_start();
require_once 'funciones/base_datos.php';
require_once 'funciones/funciones.php';
if (isset($_SESSION["user"]) && ($_SESSION["tipo"] == 2 || $_SESSION["tipo"] == 1)) {
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
			if (isset($_SESSION["info"])) {
				echo "<p class='info'>" . $_SESSION["info"] . "</p>";
				$_SESSION["info"] = null;
			}
			if (isset($_POST["logOut"])) {
				$_SESSION["info"] = "Has cerrado sesión, hasta pronto!";
				header("location: index.php");
			}
			$userData = mysqli_fetch_assoc(select_usuario($_SESSION["user"], "fan"));
			?>
			<div id="backgroundHeader"></div>
			<img src="img/logos/stucomusic.png" alt="" id="imgStucomusic" class="header"/>
			<h1 id="titulo" class="header">Stucomusic - Home</h1>
			<form action="fan.php" method="POST" ><input type="submit" name="logOut" id="logOut" class="header" value="Cerrar sessión"></form>
			<div id="backgroundSide"></div>
			<h2 id="titleDatos"class="leftSide">Tus datos</h2>
			<table id="tableDatos" class="leftSide">
				<tr><td>Nombre de usuario: </td><td><?php echo $userData["NOMBRE_USUARIO"] ?></td></tr>
				<tr><td>Nombre: </td><td><?php echo $userData["NOMBRE"] ?></td></tr> 
				<tr><td>Apellidos: </td><td><?php echo $userData["APELLIDO"] ?></td></tr>
				<tr><td>Email: </td><td><?php echo $userData["EMAIL"] ?></td></tr> 
				<tr><td>Telefono: </td><td><?php echo $userData["TELEFONO"] ?></td></tr> 
				<tr><td>Ciudad: </td><td><?php echo $userData["CIUDAD"] ?></td></tr> 
				<tr><td>Direccion: </td><td><?php echo $userData["DIRECCION"] ?></td></tr> 
				<tr><td>Imagen:</td><td><img/></td></tr> 
			</table>
			<button id="showOptionsEdit" class="menuButton">Editar tus datos</button>
			<button id="showVoteMusic" class="menuButton">Votar a un musico</button>
			<button id="showVoteConcierto" class="menuButton">Votar a un concierto</button>
			<div id="backgroundMain"></div>
			<div id="optionsEdit" class="functionality">
				<h2 id="titleOptionEdit" class="optionEdit">Que datos quieres editar?</h2>
				<form action="fan.php" method="POST" id="formOptionsEdit" >
					<table id="tableOptionEdit" class="optionEdit">
						<tr><td><input type="checkbox" name="usr">Nombre usuario</td>
							<td><input type="checkbox" name="pass">Contraseña</td>
							<td><input type="checkbox" name="name">Nombre</td></tr>
						<tr><td><input type="checkbox" name="ape">Apellidos</td>
							<td><input type="checkbox" name="mail">Email</td>
							<td><input type="checkbox" name="tel">Teléfono</td></tr>
						<tr><td><input type="checkbox" name="img">Imagen</td>
							<td><input type="checkbox" name="ciu">Ciudad</td>
							<td><input type="checkbox" name="add">Direccion</td></tr>
					</table>
					<input type="submit" name="optionSubmitEdit" id="optionSubmitEdit" value="Seleccionar">
				</form>
			</div>
			<?php
			if (isset($_POST["optionSubmitEdit"]) && !isset($_POST["backOption"])) {
				echo "<input id='infoForm'style='visibility:hidden' value='optionSelected' >";
				echo "<form action='fan.php' method='POST' id='formEdit' class='functionality'>";
				echo "<h2>Editar tus datos</h2>";
				formulario_editar_usuario();
				if (isset($_POST["ape"])) {
					echo "<p>Apellido: <input type='text' name='surname' required></p>";
				} if (isset($_POST["add"])) {
					echo "<p>Direccion: <input type='text' name='add' maxlength='50' required></p>";
				}
				echo "<input type='submit' name='edit' value='Editar'></form><form action='fan.php' method='POST' id='backOption' class='functionality'><input type='submit' name='backOption'  value='Atras'></form>";
			} if (isset($_POST["edit"])) {
				$array = array_editar_usuario($_POST);
				if (isset($_POST["surname"]) && $array) {
					$datos = "apellido='" . $_POST["surname"] . "'";
					$array[] = $datos;
				}
				if (isset($_POST["add"]) && $array) {
					$datos = "direccion='" . $_POST["add"] . "'";
					$array[] = $datos;
				}
				if ($array) {
					if (editar_usuario($array, $_SESSION["user"], "fan")) {
						$_SESSION["info"] = "<p class='info'>Cambios modificados correctamente</p>";
						if (isset($_POST["user"])) {
							$_SESSION["user"] = $_POST["user"];
						}
						header("location: fan.php");
					} else {
						echo "<p class='info'>Ha habido un error, no se ha modificado ningun dato</p>";
					}
				} else {
					echo "<p class='info'>Ha habido un error, no se ha modificado ningun dato</p>";
				}
			}
			?>
			<form class="functionality" class="vote" id="voteConcierto" >
				<h2>Vota a tu concierto favorito:</h2><br> 
				<select class="selectVote">
					<option value="" >Best one</option>
					<option value="" >Old One </option>
					<option value="" >Worse One </option>
				</select><br>
				<input type="submit" class="submitVote" name="submitVoteConcierto" value="Votar">
			</form>
			<form class="functionality" class="vote" id="voteMusico" >
				<h2>Vota a tu músico favorito:</h2><br> 
				<select class="selectVote">
					<option>Eminem</option>
					<option>Michael Jackson </option>
					<option>Bruno Mars </option>
				</select><br>
				<input type="submit" class="submitVote" name="submitVoteMusico" value="Votar">
			</form>
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