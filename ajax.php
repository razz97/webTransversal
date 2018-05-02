<?php

// Conexion para devolver todos los datos de la tabla ciudades y poder mostrarlos en un select
require_once './funciones/base_datos.php';
if (isset($_POST["prov"])) {
    $provincia = $_POST["prov"];
    $c = conectar("transversal");
    $query = "select nombre,idciudad from ciudades where provincia='$provincia' order by nombre";
    $r = mysqli_query($c, $query);
    $i = true;
    echo '[';
    while ($fila = mysqli_fetch_assoc($r)) {
        if ($i) {
            echo '{"nombre":"' . $fila["nombre"] . '","id":"' . $fila["idciudad"] . '"}';
            $i = false;
        } else {
            echo ',{"nombre":"' . $fila["nombre"] . '","id":"' . $fila["idciudad"] . '"}';
        } 
    }
    echo "]";
}
// Para cargar los generos en registro
if (isset($_POST["genre"])) {
    $selectgenre = select_genre();
    $i=true;
    echo '[';
    while ($fila = mysqli_fetch_assoc($selectgenre)) {
        if ($i) {
            echo '{"nombre":"'.$fila["nombre"].'","id":"'.$fila["idgenero"].'"}';
			$i=false;
        } else {
            echo ',{"nombre":"'.$fila["nombre"].'","id":"'.$fila["idgenero"].'"}';
        }
    }
	echo ']';
}

