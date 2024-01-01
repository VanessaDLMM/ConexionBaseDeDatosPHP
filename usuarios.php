<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="estilo.css">
	<title>Usuarios</title>
</head>
<body>
	

	<?php 

		include "funciones.php";
		$resultado = isset($_COOKIE['usuario']) ? $_COOKIE['usuario'] : ''; //Recupera los resultados de la cookie
		//Si es superadmin llama a getPermisos para comprobar el valor de los permisos actuales, los guarda y muestra en una variable
		//
		if ($resultado == "Superadmin") {
			
			$permisos= getPermisos();
			echo "<br>Los permisos actuales son " . $permisos;
			if (isset($_GET['permisos'])) { //Comprueba si están los permisos y llama a la función para cambiarlos.
				cambiarPermisos();
			}
			echo "<form action='usuarios.php' method='GET'>"; //Enviando los nuevos permisos para modificar en base de datos
			echo "<input type='submit' name='permisos' value='Cambiar permisos'>";
			echo "</form>";
			

			pintaTablaUsuarios(); //Muestra la tabla de usuarios
			
			echo '<a href="index.php">Ir a Página principal</a>';
			
			

		}else{ //Si no es superadmin, no se muestran los usuarios, solo los enlaces
			
			echo "No tienes permisos de acceso";
			echo '<a href="index.php">Ir a Página principal</a>';

		}
		

	?>

</body>
</html>

<!--
	comprueba cookie index para comprobar superadmin
	boton para cambiar valor de los permisos de la app
	muestra tabla con todos datos usuarios
	en los autorizados cambia el color de fondo de la columna
	enlace para volver a index.php
-->