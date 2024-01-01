<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Articulos</title>
</head>
<body>

	<?php 

		include "funciones.php";

	?>

	<h1>Lista de artículos</h1>

	<?php 

		$resultado = isset($_COOKIE['usuario']) ? $_COOKIE['usuario'] : ''; //recupera el resultado de la cookie

		//Comprueba los permisos para comprobar si puede acceder a articulos.php
		if ($resultado == "Registrado y autorizado") { 
			$orden = isset($_GET['orden']) ? $_GET['orden'] : 'default';
			
			$permisos= getPermisos();
			
			pintaProductos($orden);
			echo '<a href="index.php">Ir a Página principal</a>'; //Enlace de vuelta a la página principal
	

		}else{
	
			echo "No tienes permisos de acceso"; //Si después de comprobar la cookie no tiene accesos saldra solo el enlace de la página
			echo '<a href="index.php">Ir a Página principal</a>';

		}

		
	?>

</body>
</html>