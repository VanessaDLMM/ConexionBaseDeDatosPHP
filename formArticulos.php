<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Formulario de artículos</title>
</head>
<body>

	<?php 

		include "funciones.php";
		$resultado = isset($_COOKIE['usuario']) ? $_COOKIE['usuario'] : ''; //recupera los datos de la cookie
		//Sólo el usuario registrado podrá ver los formularios para editar, borrar o añadir.
		//Si no es autorizado le mostrará el enlace a página principal, sin mostrar ninguno de los formularios.
		if ($resultado == "Registrado y autorizado") {
			//Se recupera a través de get la acción marcada el artículos.php, y en función de ello mostrará el form correspondiente.
			$accion = isset($_GET['accion']) ? $_GET['accion'] : ''; 
			
			switch ($accion) {
				case 'Anadir':
					echo "<form method='post' action='formArticulos.php?accion=Anadir'>";
					echo "Nombre: <input type='text' name='nombre'><br>";
					echo "Coste: <input type='text' name='coste'><br>";
					echo "Precio: <input type='text' name='precio'><br>";
					echo "Categoría: <input type='text' name='categoria'><br>";
					echo "<input type='submit' value='Añadir'>";
					echo "</form>";
		
					if ($_SERVER['REQUEST_METHOD'] == 'POST') { //Envía los datos y llama a la función pasándole los datos del nuevo producto
						$nombre = $_POST['nombre'];
						$coste = $_POST['coste'];
						$precio = $_POST['precio'];
						$categoria = $_POST['categoria'];
		
						$mensaje = anadirProducto($nombre, $coste, $precio, $categoria);
		
						// Mostrar mensaje de la función añadirProducto
						echo $mensaje;
					}
					break;
		
				case 'Editar':
					if (isset($_GET['id'])) { //Recupera el id del producto seleccionado en articulos.php
						$idProducto = $_GET['id'];
		
						// Obtener los datos del producto
						$datosProducto = getProducto($idProducto);
		
						// Muestra el formulario con los datos y el botón 
						echo "<form method='post' action='formArticulos.php?accion=$accion&id=$idProducto'>";
						echo "Nombre: <input type='text' name='nombre' value='" . $datosProducto['Name'] . "'><br>";
						echo "Coste: <input type='text' name='coste' value='" . $datosProducto['Cost'] . "'><br>";
						echo "Precio: <input type='text' name='precio' value='" . $datosProducto['Price'] . "'><br>";
						echo "Categoría: <input type='text' name='categoria' value='" . $datosProducto['CategoryID'] . "'><br>";
						echo "<input type='submit' value='$accion'>";
						echo "</form>";
		
						if ($_SERVER['REQUEST_METHOD'] == 'POST') { //Envía los nuevos datos llamando a la función y pasándolos como parámetro
							$nombre = $_POST['nombre'];
							$coste = $_POST['coste'];
							$precio = $_POST['precio'];
							$categoria = $_POST['categoria'];
		
							$mensaje = editarProducto($idProducto, $nombre, $coste, $precio, $categoria);
																
							echo $mensaje; //Muestra el mensaje proporcionado por editarProducto
						}
					}
					break;
				case 'Borrar':
					if (isset($_GET['id'])) { //Recupera el id del producto seleccionado en artículos.php
						$idProducto = $_GET['id']; //Guarda el id del producto
		
						// Obtén los datos del producto para mostrar en el formulario
						$datosProducto = getProducto($idProducto);
		
						// Muestra el formulario con los datos y el botón
						echo "<form method='post' action='formArticulos.php?accion=$accion&id=$idProducto'>";
						echo "Nombre: <input type='text' name='nombre' value='" . $datosProducto['Name'] . "'><br>";
						echo "Coste: <input type='text' name='coste' value='" . $datosProducto['Cost'] . "'><br>";
						echo "Precio: <input type='text' name='precio' value='" . $datosProducto['Price'] . "'><br>";
						echo "Categoría: <input type='text' name='categoria' value='" . $datosProducto['CategoryID'] . "'><br>";
						echo "<input type='submit' value='$accion'>";
						echo "</form>";
		
						if ($_SERVER['REQUEST_METHOD'] == 'POST') { //Envía los datos y los pasa como parámetro a la función
							$nombre = $_POST['nombre'];
							$coste = $_POST['coste'];
							$precio = $_POST['precio'];
							$categoria = $_POST['categoria'];
		
							$mensaje =  borrarProducto($idProducto);
		
							// Muestra el mensaje de la función borrarProducto 
							echo $mensaje;
						}
					}
					break;
			}
			echo '<a href="index.php">Ir a Página principal</a><br>'; //Enlace a página principal
			echo '<a href="articulos.php">Ir a Artículos</a>'; //Enlace para volver a artículos.php

		}else{ //Si no estás autorizado no mostrará los formularios, solo los enlaces para volver a la página principal
			
			echo "No tienes permisos de acceso";
			echo '<a href="index.php">Ir a Página principal</a>';

		}
	?>

	
</body>
</html>