<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Index.php</title>
	<link rel="stylesheet" type="text/css" href="estilo.css">
</head>
<body id="comienzo">
	<h1>Bienvenido a la tienda online <a>+queRopa</a></h1>
	<h4>Introduce tus credenciales para continuar:</h4>
	<form method="POST" action="">
		<label for="nombre">Nombre</label>
		<input type="text" id="nombre" name="nombre"><br>
		<label for="email">Email</label>
		<input type="email" id="email" name="email"><br>
		<button type="submit" value="acceso" id="acesso">Acceder</button>
		
	</form>
	

	<?php
	
		include "consultas.php";
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			// Validar y obtener los datos del formulario
			$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
			$correo = isset($_POST['email']) ? $_POST['email'] : '';
	
			// Llamar a la función para verificar las credenciales
			$resultado = tipoUsuario($nombre, $correo);

			setcookie("usuario", $resultado, time() + 3600, "/"); //Se crea y guarda en la cookie

			if($resultado=="Superadmin"){
				echo '<br><a href="usuarios.php">Ir a Página Usuarios</a'; //Enlace de superadmin
			}
			elseif($resultado=="Registrado y autorizado"){
				echo '<br><a href="articulos.php">Ir a Página Artículos</a>'; //Enlace autorizado
			}
			else{
				echo $nombre;
				echo '<br>No tienes permisos de acceso'; //registrado no permisos
			}
		}	
		
	?>
	
	
</body>
</html>

<!-- Contiene formulario para acceder a la app. 
llama a consultas.php para comprobar el usuario.
Si es superadmin->enlace a usuarios.php
Si es usser autorizado->enlace a articulos.php
Si es usser registrado->alert no autorizado,no acceso
Si datos mal o no registrado->usser no registrado
Cookie con tipo de usser que ha intentado acceder -->