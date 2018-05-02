<?php

require_once 'base_datos.php';

//(editar datos) Imprime el la parte comun de todos los tipos de usuario
function formulario_editar_usuario() {
	if (isset($_POST["usr"])) {
		echo "<p>Nombre de usuario: <input type='text' name='user' maxlength='20' required></p>";
	} if (isset($_POST["pass"])) {
		echo "<p>Antigua: <input type='password' name='password0' minlength='6' maxlength='12' required></p>" .
		"<p>Nueva contrasena: <input type='password' name='password1' minlength='6' maxlength='12' required></p>" .
		"<p>Repetir contrasena: <input type='password' name='password2' minlength='6' maxlength='12' required></p>";
	} if (isset($_POST["name"])) {
		echo "<p>Nombre: <input type='text' name='name' required></p>";
	} if (isset($_POST["mail"])) {
		echo "<p>Email: <input type='email' name='mail' maxlength='50' required></p>";
	} if (isset($_POST["tel"])) {
		echo "<p>Telefono: <input type='text' name='tel' maxlength='20' required></p>";
	} if (isset($_POST["img"])) {
		echo "<p>Imagen <input type='text' name='img'></p>";
	} if (isset($_POST["ciu"])) {
		echo "<p>Selecciona provincia: <select id='provincia'>" .
		"<option disabled selected >Selecciona una provincia</option>";
		$select = select_alta_provincia();
		while ($fila = mysqli_fetch_assoc($select)) {
			echo "<option>" . $fila["provincia"] . "</option>";
		} echo "</select></p><p style='visibility: hidden' id='ciudads'>" .
		"Selecciona ciudad: <select name='ciu' id='ciudad' required></select></p>";
	}
}

//(editar datos)Funcion que crea un array a partir de los formularios en las paginas de usuarios, 
//este se utiliza en la funcion anterior para crear la query necesaria.
function array_editar_usuario($data) {
	$update = true;
	$error = "";
	$array = array();
	if (isset($data["user"])) {
		$ok = comp_nombre_usuarios($data["user"]);
		if (!$ok) {
			echo "Este nombre de usuario ya existe";
			$update = false;
		} else {
			$datos = "nombre_usuario='" . $data["user"] . "'";
			$array[] = $datos;
		}
	}
	if (isset($data["password0"])) {
		if ($data["password1"] == $data["password2"]) {
			if (login_y_tipo($_SESSION["user"], $data["password0"])) {
				$datos = "contrasena='" . password_hash($data["password1"], PASSWORD_BCRYPT) . "'";
				$array[] = $datos;
			} else {
				$error = "Contrasena antigua incorrecta.";
				$update = false;
			}
		} else {
			$error = "Las contrasenas nuevas no son iguales.";
			$update = false;
		}
	}
	if (isset($data["name"])) {
		$datos = "nombre='" . $data["name"] . "'";
		$array[] = $datos;
	}
	if (isset($data["mail"])) {
		if (comp_emails($data["mail"])) {
			$datos = "email='" . $data["mail"] . "'";
			$array[] = $datos;
		} else {
			$error = "Ya existe una cuenta con este email.";
			$update = false;
		}
	}
	if (isset($data["tel"])) {
		$datos = "telefono='" . $data["tel"] . "'";
		$array[] = $datos;
	}

	if (isset($data["img"])) {
		$datos = "imagen='" . $data["img"] . "'";
		$array[] = $datos;
	}
	if (isset($data["ciu"])) {
		$datos = "idciudad=" . $data["ciu"];
		$array[] = $datos;
	}
	if ($update) {
		return $array;
	} else
		return $error;
}

//Funcion general para comprobar registro de cualquier usuario
function comprobaciones($data) { 
	if (!comp_nombre_usuarios($data["user"])) {
		return "El nombre de usuario '".$data["user"]."' esta en uso.";
	} else if (!comp_emails($data["mail"])) {
		return"El email '".$data["mail"]."' esta en uso.";
	} else if ($data["password"] != $data["password2"]) {
		return "Las contraseñas introducidas no son iguales.";
	} else {
		return true;
	}
}
//funcion para recogida de datos y comprobacion usuario para registrar fan
function comprobacion_alta_fan($data) {
	$passHash = password_hash($data["password"], PASSWORD_BCRYPT);
	$comp=comprobaciones($data);
	if (is_string($comp)) {
		return $comp;
	} else {
		$addFan = add_Fan($data["user"], $passHash, 2, $data["mail"], $data["tel"], $data["img"], $data["ciudad"], 
		$data["name"], $data["surname"], $data["add"]);
		if (is_string($addFan)) {
			return $addFan;
		} else {
			return true;
		}
	}
}
//funcion para recogida de datos y comprobacion usuario para registrar musico
function comprobacion_alta_musico($data) {
	$passHash = password_hash($data["password"], PASSWORD_BCRYPT);
	$comp= comprobaciones($data);
	if (is_string($comp)) {
		return $comp;
	} else {
		$addMusico = add_Musico($data["user"], $passHash, 4, $data["mail"], $data["tel"], $data["img"], $data["ciudad"], 
		$data["name"], $data["surname"], $data["art"], $data["comp"], $data["genre"], $data["web"]);
		if (is_string($addMusico)) {
			return $addMusico;
		} else {
			return true;
		}
	}
}
//funcion para recogida de datos y comprobacion usuario para registrar local
function comprobacion_alta_local($data) {
	$passHash = password_hash($data["password"], PASSWORD_BCRYPT);
	$comp= comprobaciones($data);
	if (is_string($comp)) {
		return $comp;
	} else {
		$addLocal = add_Local($data["user"], $passHash, 3, $data["mail"], $data["tel"], $data["img"], 
		$data["ciudad"], $data["aforo"], $data["add"], $data["localname"]);
		if (is_string($addLocal)) {
			return $addLocal;
		} else {
			return true;
		}
	}
}
