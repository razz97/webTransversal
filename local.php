<?php
session_start();
require_once 'funciones/base_datos.php';
require_once 'funciones/funciones.php';
if (isset($_SESSION["user"]) && ($_SESSION["tipo"] == 3 || $_SESSION["tipo"] == 1)) {
	?>
	<html>
		<head>
			<meta charset="UTF-8">
			<link href="css/usuarios.css" rel="stylesheet" type="text/css"/>
			<script src="librerias/jquery-3.2.1.min.js" type="text/javascript"></script>
			<script src="javascript/usuarios.js" type="text/javascript"></script>
			<title>Stucomusic-Local</title>
		</head>
		<body>
			<?php
			$userData = mysqli_fetch_assoc(select_usuario($_SESSION["user"], "locales"));
			if (isset($_SESSION["info"])) {
				echo "<p class='info'>" . $_SESSION["info"] . "</p>";
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
			<form action="local.php" method="POST" ><input type="submit" name="logOut" id="logOut" class="header" value="Cerrar sessión"></form>
			<div id="backgroundSide"></div>
			<h2 id="titleDatos"class="leftSide">Tus datos</h2>
			<table id="tableDatos" class="leftSide">
				<tr><td>Nombre de usuario: </td><td><?php echo $userData["NOMBRE_USUARIO"] ?></td></tr>
				<tr><td>Nombre: </td><td><?php echo $userData["NOMBRE"] ?></td></tr> 
				<tr><td>Email: </td><td><?php echo $userData["EMAIL"] ?></td></tr> 
				<tr><td>Telefono: </td><td><?php echo $userData["TELEFONO"] ?></td></tr> 
				<tr><td>Ciudad: </td><td><?php echo $userData["CIUDAD"] ?></td></tr> 
				<tr><td>Direccion: </td><td><?php echo $userData["DIRECCION"] ?></td></tr> 
				<tr><td>Apellidos: </td><td><?php echo $userData["AFORO"] ?></td></tr>
				<tr><td>Imagen:</td><td><img/></td></tr> 
			</table>
			<button id="showOptionsEdit" class="menuButton">Editar tus datos</button>
			<button id="showCreateConcert" class="menuButton">Crear un concierto</button>
			<button id="showDeleteConcert" class="menuButton">Borrar un concierto</button>
			<button id="showInfoConcert" class="menuButton">Informacion de tus conciertos</button>
			<div id="backgroundMain"></div>
			<div id="optionsEdit" class="functionality">
				<h2 id="titleOptionEdit" class="optionEdit">Que datos quieres editar?</h2>
				<form action="local.php" method="POST" id="formOptionsEdit" >
					<table id="tableOptionEdit" class="optionEdit">
						<tr><td><input type="checkbox" name="usr">Nombre usuario</td>
							<td><input type="checkbox" name="pass">Contraseña</td>
							<td><input type="checkbox" name="name">Nombre </td></tr>
						<tr><td><input type="checkbox" name="mail">Email </td>
							<td><input type="checkbox" name="ciu">Ciudad </td>
							<td><input type="checkbox" name="tel">Teléfono</td></tr>
						<tr><td><input type="checkbox" name="add">Direccion</td>
							<td><input type="checkbox" name="aforo">Aforo</td>
							<td><input type="checkbox" name="img">Imagen</td></tr>
					</table>
					<input type="submit" name="optionSubmitEdit" id="optionSubmitEdit" value="Seleccionar">
				</form>
			</div>
			<?php
			if (isset($_POST["optionSubmitEdit"]) && !isset($_POST["backOption"])) {
				echo "<input id='infoForm'style='visibility:hidden' value='optionSelected' >";
				echo "<form action='local.php' method='POST' id='formEdit' class='functionality'>";
				echo "<h2>Editar tus datos</h2>";
				formulario_editar_usuario();
				if (isset($_POST["aforo"])) {
					echo "<p>Aforo <input type='text' name='aforo'></p>";
				} if (isset($_POST["add"])) {
					echo "<p>Direccion: <input type='text' name='add' maxlength='50' required></p>";
				}
				echo "<input type='submit' name='edit' value='Editar'></form><form action='local.php' method='POST' class='functionality' id='backOption'><input type='submit' name='backOption'  value='Atras'></form>";
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
					if (editar_usuario($array, $_SESSION["user"], "locales")) {
						$_SESSION["info"] = "<p class='info'>Cambios modificados correctamente</p>";
						if (isset($_POST["user"])) {
							$_SESSION["user"] = $_POST["user"];
						}
						header("location: local.php");
					} else {
						echo "<p class='info'>Ha habido un error, no se ha modificado nada</p>";
					}
				} else {
					echo "<p class='info'>No se ha modificado ningun dato</p>";
				}
			}
			?>
			<div id="createConcert" class="functionality">
				<h2>Crea un concierto</h2>
				<form action="local.php" method="POST">
					<table>
						<tr><td>Nombre concierto: </td><td><input type="text" required name="nombre"/></td></tr>
						<tr><td>Fecha del concierto: (cuidado con formato)</td><td><input type="datetime-local" placeholder="yyyy/mm/ddThh:mm" required name="fecha"/></td></tr>
						<tr><td>Género: </td><td><select name="genero" required>
									<option disabled selected >Selecciona un genero</option>
									<?php
									$genre = select_genre();
									while ($fila = mysqli_fetch_assoc($genre)) {
										echo "<option value = '" . $fila["idgenero"] . "'>" . $fila["nombre"] . "</option>";
									}
									?> 
								</select></td></tr>
						<tr><td>Propuesta económica: </td>
							<td><input type="number" name="propuesta"/></td></tr>
					</table><br>
					<input  type="submit" name="submitCreateConcert" value="Crear" >
				</form> 
			</div>
			<?php
			if (isset($_POST["submitCreateConcert"])) {
				//!!Esto se tiene que cambiar, solo funciona en chrome!!
				$name = $_POST["nombre"];
				$date = explode("T", $_POST["fecha"]);
				$genre = $_POST["genero"];
				$cashMoney = $_POST["propuesta"];
				$result = alta_concierto($date[0] . " " . $date[1] . ":00", $genre, $cashMoney, $name, $userData["IDUSUARIO"]);
				if ($result) {
					echo "<p class='info'>Concierto creado con éxito</p>";
				}
			}
			?>
			<div id="deleteConcert" class="functionality">
				<h2> Que concierto quieres eliminar?</h2>
				<form  method='POST'>
					<select name = 'selectDeleteConcert'>
						<?php
						$concierto = seleccionar_conciertos_local_eliminar($userData["IDUSUARIO"]);
						while ($fila = mysqli_fetch_assoc($concierto)) {
							echo "<option  value = '" . $fila["idconcierto"] . "'>" . $fila["nombre"] . "</option>";
						}
						?>
					</select>
					<input type ='submit' name ='submitDeleteConcert' value='Eliminar'/>
				</form>
			</div>
			<?php
			if (isset($_POST["submitDeleteConcert"])) {
				$result = eliminar_concierto($_POST["selectDeleteConcert"]);
				echo "<p class='info'>Concierto eliminado con éxito</p>";
			}
			?>
			<div id="infoConcert" class="functionality">
				<?php
				$asignados = seleccionar_conciertos_local($userData["IDUSUARIO"]);
				echo "<table class='tablesInfoConcert'><tr><th>Nombre concierto</th><th>Musico/s</th><th>Genero</th><th>Fecha y hora</th></tr>$asignados</table>";
				?>
				<h2>Conciertos pendientes de asignación</h2>
				<?php
				$pendientes = seleccionar_conciertos_pendientes_local($userData["IDUSUARIO"]);
				echo "<table class='tablesInfoConcert'><tr><th>Nombre concierto</th><th>Genero</th><th>Fecha y hora</th></tr>$pendientes</table>";
				?>
				<h2>Candidaturas a conciertos</h2>
				<?php
				$candidaturas = seleccionar_peticiones_local($userData["IDUSUARIO"]);
				echo "<table class='tablesInfoConcert'> <tr><th>Concierto</th><th>Musico</th><th>Genero</th><th>Fecha</th><th>Confirmar</th></tr>$candidaturas</table></div> ";
				if (isset($_POST["aceptar"])) {
					aceptar_musico($_POST["idmusico"], $_POST["idconcierto"]);
					rechazar_musicos($_POST["idmusico"], $_POST["idconcierto"]);
					echo "<p class='info'>El musico ha sido aceptado</p>";
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