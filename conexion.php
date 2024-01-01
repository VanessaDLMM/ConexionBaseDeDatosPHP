<?php 

	function crearConexion() {
		// Cambiar en el caso en que se monte la base de datos en otro lugar
		$host = "localhost";
		$user = "root";
		$pass = "";
		$baseDatos = "pac3_daw";

		
    //establecer conexion. Guarda en una variable los datos de conexión para pasarlos como parámetro

    $conexion = mysqli_connect($host, $user, $pass, $baseDatos); 

    //si hay un error en la conexion, lo mostramos y detenemos

    if(!$conexion){
        die("<br>Error de conexion a la BD: ".mysqli_connect_error());
    }
    return $conexion;
	}


	function cerrarConexion($conexion) {
		mysqli_close($conexion);
	}


?>