<?php

//Funciones basicas conectar/desconectar.
function conectar() {
	$connect = mysqli_connect("localhost", "root", "", "transversal");
	if (!$connect) {
		die("No se ha podido establecer la conexión con el servidor");
	}
	return $connect;
}

function desconectar($connect) {
	mysqli_close($connect);
}

//Funcion para autentificar al usuario.
function login_y_tipo($usr, $passwd) {
	$conexion = conectar("transversal");
	$select1 = "select contrasena,tipo from usuario where nombre_usuario='$usr'";
	$query = mysqli_query($conexion, $select1);
	$array = mysqli_fetch_assoc($query);

	$passHash = password_verify($passwd, $array["contrasena"]);
	desconectar($conexion);
	if ($passHash) {
		return $array["tipo"];
	} else {
		return false;
	}
}

//Funciones para registrar usuarios, primero añaden todos los datos comunes de usuario, mediante la funcion alta_usuario, 
//despues devuelven la id de este con mysqli_insert_id, 
//y por ultimo insertan los datos de cada tipo de usuario (fan, musico, local ), con la misma id que el usuario.
function add_Fan($user, $passHash, $type, $email, $tel, $img, $idciudad, $name, $surname, $add) {
	$conexion = conectar("transversal");
	$Alta = alta_usuario($user, $passHash, $type, $email, $tel, $img, $idciudad, $name, $conexion);
	if ($Alta == "ok") {
		$idusu = mysqli_insert_id($conexion);
		alta_fan($idusu, $surname, $add, $conexion);
		$resultado = true;
	} else {
		$resultado = $Alta;
	}
	desconectar($conexion);
	return $resultado;
}

function add_Musico($user, $passHash, $type, $email, $tel, $img, $idciudad, $name, $surname, $art, $comp, $genre, $web) {
	$conexion = conectar("transversal");
	$Alta = alta_usuario($user, $passHash, $type, $email, $tel, $img, $idciudad, $name, $conexion);
	if ($Alta == "ok") {
		$idusu = mysqli_insert_id($conexion);
		alta_musico($idusu, $surname, $art, $comp, $genre, $web, $conexion);
		$resultado = true;
	} else {
		$resultado = $Alta;
	}
	desconectar($conexion);
	return $resultado;
}

function add_Local($user, $passHash, $type, $email, $tel, $img, $idciudad, $aforo, $add, $name) {
	$conexion = conectar("transversal");
	$Alta = alta_usuario($user, $passHash, $type, $email, $tel, $img, $idciudad, $name, $conexion);
	if ($Alta == "ok") {
		$idusu = mysqli_insert_id($conexion);
		alta_local($idusu, $aforo, $add, $conexion);
		$resultado = true;
	} else {
		$resultado = $Alta;
	}
	desconectar($conexion);
	return $resultado;
}

//funcion que inserta todos los datos obligatorios de usuario.
function alta_usuario($user, $passHash, $type, $email, $tel, $img, $idciudad, $name, $c) {
	$insert = "insert into usuario values(null,'$user','$passHash',$type,'$email','$tel','$img',$idciudad,'$name')";
	if (mysqli_query($c, $insert)) {
		$result = true;
	} else {
		$result = mysqli_error($c);
	}
	return $result;
}

//Las 3 siguientes son funciones que añaden los datos especificos de cada tipo de usuario (Fan, musico, local)
function alta_fan($idusu, $surname, $add, $c) {
	$insert = "insert into fan values($idusu,'$surname','$add')";
	if (mysqli_query($c, $insert)) {
		$result = "ok";
	} else {
		$result = mysqli_error($c);
	}
	return $result;
}

function alta_local($idusu, $aforo, $add) {
	$c = conectar("transversal");
	$insert = "insert into locales values($idusu,$aforo,'$add')";
	if (mysqli_query($c, $insert)) {
		$result = true;
	} else {
		$result = mysqli_error($c);
	}
	return $result;
}

function alta_musico($idusu, $surname, $art, $comp, $genre, $web, $c) {
	$insert = "insert into musico values($idusu,'$surname','$art',$comp,'$genre','$web')";
	if (mysqli_query($c, $insert)) {
		$result = true;
	} else {
		$result = mysqli_error($c);
	}
	return $result;
}

//Selects necesarios para mostrar informacion o para rellenar selects(html) de formularios.
function select_genre() {
	$c = conectar("transversal");
	$select = "select idgenero, nombre from genero";
	$result = mysqli_query($c, $select);
	desconectar($c);
	return $result;
}
function select_alta_provincia() {
	$c = conectar("transversal");
	$query = "select distinct provincia from ciudades";
	$result = mysqli_query($c, $query);
	desconectar($c);
	return $result;
}

//Funcion para seleccionar y devolver todos los datos de un usuario segun su tipo,
// utilizada para mostrar los datos de usuario por pantalla una vez se ha registrado
function select_usuario($user, $type) {
	$c = conectar("transversal");
	if ($type == "locales") {
		$query = "select usuario.*, locales.*,ciudades.nombre CIUDAD from usuario join locales join ciudades "
				. "on idlocal=idusuario and usuario.idciudad=ciudades.idciudad where nombre_usuario='$user'";
	} else if ($type == "fan") {
		$query = "select usuario.*, fan.*,ciudades.nombre CIUDAD from usuario join fan join ciudades "
				. "on idfan=idusuario and usuario.idciudad=ciudades.idciudad where nombre_usuario='$user'";
	} else {
		$query = "select usuario.*, musico.*,ciudades.nombre CIUDAD, genero.nombre GENERO from usuario join musico join ciudades join genero "
				. "on idmusico=idusuario and usuario.idciudad=ciudades.idciudad and genero.idgenero=musico.idgenero where nombre_usuario='$user'";
	}
	$result = mysqli_query($c, $query);
	desconectar($c);
	return $result;
}

//funcion para comprobar que el nombre de usuario escogido no existe ya, la utilizamos en registro y en edicion de datos
function comp_nombre_usuarios($user) {
	$c = conectar("transversal");
	$query = "select nombre_usuario from usuario where nombre_usuario='$user'";
	if (mysqli_num_rows(mysqli_query($c, $query)) != 0) {
		$result = false;
	} else {
		$result = true;
	}
	desconectar($c);
	return $result;
}

//funcion para comprobar que el email de usuario escogido no existe ya, la utilizamos en registro y en edicion de datos
function comp_emails($mail) {
	$c = conectar("transversal");
	$query = "select email from usuario where email='$mail'";
	if (mysqli_num_rows(mysqli_query($c, $query)) != 0) {
		$result = false;
	} else {
		$result = true;
	}
	desconectar($c);
	return $result;
}

//Funcion update para editar un usuario de cualquier tipo.
function editar_usuario($array, $user, $type) {
	$c = conectar("transversal");
	$long = count($array);

	$query = "update $type,usuario set ";
	for ($i = 0; $i < $long; $i++) {
		if ($i == $long - 1) {
			$query .= $array[$i];
		} else {
			$query .= $array[$i] . ",";
		}
	}
	if ($type == "locales") {
		$query .= " where idlocal=idusuario and nombre_usuario like '$user'";
	} else {
		$query .= " where id" . $type . "=idusuario and nombre_usuario like '$user'";
	}
	$result = mysqli_query($c, $query);
	desconectar($c);
	return $result;
}

//Seleccionar conciertos para la homepage (solo los que tienen musico)
function seleccionar_conciertos_slick() {
	$c = conectar("transversal");
	$query = "select concierto.nombre, fecha, genero.nombre genero, nombre_artistico musico, locales.direccion, usuario.nombre nombre_local "
			. "from usuario join locales join concierto join genero join musico on idusuario=locales.idlocal and locales.idlocal=concierto.idlocal "
			. "and concierto.idgenero=genero.idgenero and concierto.idmusico=musico.idmusico where fecha between now() and date_add(curdate(), interval +1 year)";
	$result = mysqli_query($c, $query);
	$array = array();
	while ($r = mysqli_fetch_assoc($result)) {
		$datos = "<tr><td>" . $r["nombre"] . "</td><td>" . $r["nombre_local"] . "</td><td>" . $r["musico"] . "</td><td>"
				. $r["direccion"] . "</td><td>" . $r["fecha"] . "</td><td>" . $r["genero"] . "</td></tr>";
		$array[] = $datos;
	}
	return $array;
}

//Selecionar los conciertos 
function seleccionar_peticiones_musico($idmusico) {
	$conciertos = "";
	$c = conectar();
	$sql = "select concierto.idconcierto,concierto.nombre nombreC, concierto.fecha, concierto.nombre, concierto.propuesta_economica, usuario.nombre nombreL,asistir.estado,locales.direccion
from asistir join concierto join usuario join locales on asistir.idconcierto=concierto.idconcierto and concierto.idlocal=usuario.idusuario and usuario.idusuario=locales.idlocal
where asistir.idmusico=$idmusico;";
	$result = mysqli_query($c, $sql);
	while ($r = mysqli_fetch_assoc($result)) {
		if ($r["estado"] == 0) {
			$estado = "Denegada";
		} else if ($r["estado"] == 1) {
			$estado = "Aceptada";
		} else {
			$estado = "En espera del local";
		}$conciertos .= "<tr><td>" . $r["nombreC"] . "</td><td>" . $r["fecha"] . "</td><td>" . $r["nombreL"] . "</td><td>" . $r["direccion"] . "</td><td>" . $estado . "</td></tr>";
	}desconectar($c);

	return $conciertos;
}

//Seleccionar todos los conciertos que sean el mismo género que el músico
function seleccionar_conciertos_musico($idmusico) {
	$c = conectar();
	$sql = "select idconcierto, fecha, concierto.nombre, propuesta_economica, usuario.nombre nombreL 
from concierto join usuario on concierto.idlocal=usuario.idusuario
where idgenero=(select idgenero from musico where idmusico=$idmusico)
and idconcierto in (select idconcierto from concierto  where idconcierto not in (select idconcierto from asistir where idmusico=$idmusico));";
	$result = mysqli_query($c, $sql);
	$datos = "";
	while ($r = mysqli_fetch_assoc($result)) {
		$datos .= "<tr><td>" . $r["nombre"] . "</td><td>" . $r["nombreL"] . "</td><td>" . $r["propuesta_economica"]
				. "</td><td>" . $r["fecha"] . "</td><td>" . "<form method='POST'><input type='hidden' name='idconcierto' value='" . $r["idconcierto"] .
				"'><input type='submit'  name='aceptar' value='Inscribirse'></form></td></tr>" . "</tr>";
	}
	desconectar($c);
	return $datos;
}
//Enviar una peticion al local
function inscribirse($idconcierto, $idmusico) {
	$c = conectar();
	$insert = "insert into asistir values ($idconcierto,$idmusico,2)";
	$result = mysqli_query($c, $insert);
	desconectar($c);
	return $result;
}
//Crear un nuevo concierto
function alta_concierto($date, $genre, $cashMoney, $name, $idlocal) {
	$c = conectar("transversal");
	$insert = "insert into concierto values(null,'$date',$genre, $cashMoney,'$name',$idlocal,null,0)";
	if (mysqli_query($c, $insert))
		$result = true;
	else
		$result = mysqli_error($c);
	desconectar($c);
	return $result;
}
//Seleccionar conciertos de un local
function seleccionar_conciertos_local($idlocal) {
	$conciertos = "";
	$c = conectar("transversal");
	$select = "select concierto.idconcierto, concierto.nombre as nombreC ,musico.nombre_artistico nombreM ,genero.nombre as nombreG, concierto.fecha as fecha from concierto
    inner join musico on concierto.idmusico=musico.idmusico inner join genero
    on concierto.idgenero=genero.idgenero
    inner join asistir on concierto.idconcierto=asistir.idconcierto
    where concierto.idlocal=$idlocal and concierto.estado=1";
	$result = mysqli_query($c, $select);
	while ($r = mysqli_fetch_assoc($result)) {
		$conciertos .= "<tr><td>" . $r["nombreC"] . "</td><td>" . $r["nombreM"] . "</td><td>" . $r["nombreG"] . "</td><td>" . $r["fecha"] . "</td>";
	}

	desconectar($c);
	return $conciertos;
}
//Seleccionar conciertos local (solo el id y nombre) para eliminarlos
function seleccionar_conciertos_local_eliminar($idlocal) {
	$c = conectar("transversal");
	$select = "select idconcierto,nombre from concierto where idlocal=$idlocal";
	$result = mysqli_query($c, $select);
	desconectar($c);
	return $result;
}
//Seleccionar los conciertos aun no aceptados de un local
function seleccionar_conciertos_pendientes_local($idlocal) {
	$conciertos = "";
	$c = conectar("transversal");
	$select = "select concierto.nombre nombreC  ,genero.nombre nombreG,fecha 
    from genero join concierto  
    on genero.idgenero=concierto.idgenero
    where concierto.idlocal=$idlocal and concierto.estado=0
    group by concierto.idconcierto;";
	$result = mysqli_query($c, $select);

	while ($r = mysqli_fetch_assoc($result)) {
		$conciertos .= "<tr><td>" . $r["nombreC"] . "</td><td>" . $r["nombreG"] . "</td><td>" . $r["fecha"] . "</td>";
	}
	desconectar($c);
	return $conciertos;
}
//Seleccionar las peticiones que le han llegado al local
function seleccionar_peticiones_local($idlocal) {
	$c = conectar();
	$select = "select concierto.idconcierto,concierto.nombre nombreC, musico.nombre_artistico nombreM,musico.idmusico idmusico,genero.nombre nombreG, fecha
    from genero join concierto join asistir join musico
    on genero.idgenero=concierto.idgenero
    and concierto.idconcierto=asistir.idconcierto
    and asistir.idmusico=musico.idmusico
    where concierto.idlocal=$idlocal and asistir.estado=2;";
	$result = mysqli_query($c, $select);
	$conciertos = "";
	while ($r = mysqli_fetch_assoc($result)) {
		$conciertos .= "<tr><td>" . $r["nombreC"] . "</td><td>" . $r["nombreM"] . "</td><td>" . $r["nombreG"] . "</td><td>" . $r["fecha"]
				. "</td><td> <form method='POST'>"
				. "<input type='hidden' value='" . $r["idconcierto"] . "' name='idconcierto'>"
				. "<input type='hidden' value='" . $r["idmusico"] . "' name='idmusico'>"
				. "<form method='POST'><input type='submit'  name='aceptar' value='Aceptar'></form></td></tr>";
	}
	return $conciertos;
}
//Aceptar peticion de un musico
function aceptar_musico($idmusico, $idconcierto) {
	$c = conectar();
	$updateAsistir = "update asistir set estado=1 where asistir.idmusico=$idmusico and asistir.idconcierto=$idconcierto;";
	$updateConcierto = "update concierto set idmusico=$idmusico, estado=1 where concierto.idconcierto=$idconcierto;";
	mysqli_query($c, $updateAsistir);
	mysqli_query($c, $updateConcierto);
	desconectar($c);
}
//Rechaza a los demas musicos al aceptar a uno.
function rechazar_musicos($idmusico, $idconcierto) {
	$c = conectar();
	$update = "update asistir set estado=0 where idmusico!=$idmusico and idconcierto=$idconcierto;";
	$result = mysqli_query($c, $update);
	desconectar($c);
	return $result;
}
//Para eliminar un concierto
function eliminar_concierto($idconcierto) {
	$c = conectar();
	$deleteAsistir = "delete from asistir where idconcierto=$idconcierto;";
	$deletePuntuar = "delete from puntuar where idconcierto=$idconcierto;";
	$deleteConcierto = "delete from concierto where idconcierto=$idconcierto;";
	mysqli_query($c, $deleteAsistir);
	mysqli_query($c, $deletePuntuar);
	mysqli_query($c, $deleteConcierto);
	desconectar($c);
}
