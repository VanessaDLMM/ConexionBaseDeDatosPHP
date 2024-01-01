<?php 

	include "conexion.php";
	$DB= crearConexion("pac3_daw");

	function tipoUsuario($nombre, $correo){
		$DB = crearConexion("pac3_daw");
    	$sql = "SELECT FullName, Email FROM user WHERE FullName = '$nombre' AND Email = '$correo'"; //Consulta sql para comprobar usuario/email que recibe
    	$result = mysqli_query($DB, $sql); //Ejecuta la consulta

    	if (mysqli_num_rows($result) > 0) { //Si la consulta devuelve resultados...
        	while ($fila = mysqli_fetch_assoc($result)) {
            	$nombreUsuario = $fila['FullName']; //Guardo los datos en variables para la siguiente consulta
            	$emailUsuario = $fila['Email'];

            	// Ahora, realiza una segunda consulta para obtener el valor de Enabled
            	$sqlEnabled = "SELECT Enabled FROM user WHERE FullName = '$nombreUsuario' AND Email = '$emailUsuario'";
            	$resultEnabled = mysqli_query($DB, $sqlEnabled);//Ejecuta la consulta

            	if ($resultEnabled) { //Si hay resultados...
                	$filaEnabled = mysqli_fetch_assoc($resultEnabled); 
                	$enabled = $filaEnabled['Enabled']; //Guardo el resultado en variable para comprobar su valor

                	if ($enabled == 1) { //Comprueba los permisos de cada usuario
                    	echo "Bienvenido " . $nombreUsuario . "<br>";
						return "Registrado y autorizado";
						
						
                	} elseif ($enabled == 0) {
                    
                    	if (esSuperadmin($nombre, $correo)) { //Llama a la funcion para comprobar si es superadmin
                        	echo "Bienvenido " . $nombreUsuario . "<br>";
							return "Superadmin";
							
							
                    	} else {
							echo "Bienvenido " . $nombreUsuario . "<br>";
                        	return "Registrado";
							
							
                    	}
                	}
            	} else {
                	echo "Error al obtener el valor de Enabled"; //Manejo el error si no se recupera el resultado
            	}	
        	}
    	} else {
        	echo "No registrado";
    }
		
		cerrarConexion($DB);
		//funciona ok
	}


	function esSuperadmin($nombre, $correo){
		$DB= crearConexion("pac3_daw");
		$sql="SELECT user.UserID
			FROM user
			INNER JOIN setup ON user.UserID = setup.SUPERADMIN
			WHERE user.FullName = '$nombre' AND user.Email = '$correo'"; //Sentencia swql para para recuperar los datos de la base de datos
		$result= mysqli_query($DB, $sql); //Ejecuta la consulta
		if(mysqli_num_rows($result)>0){ //Si hay resultados devuelve true, si no false (no es superadmin)
			return true;
		}else{
			return false;
		}
		cerrarConexion($DB);
		//funciona ok
	}


	function getPermisos() {
		$DB= crearConexion("pac3_daw");
		$sql="SELECT Autenticación FROM setup"; //Sentencia sql para recuperar el valor de autenticación de la bd
		$result= mysqli_query($DB, $sql);
		if ($result) {
			// Obtener el valor de la columna Autenticación y guardo en variable
			$row = mysqli_fetch_assoc($result);
	
			// Devolver el valor
			if ($row) {
				return $row['Autenticación'];
			}
		}
		cerrarConexion($DB);
	}
	function cambiarPermisos() { 
		$result=getPermisos(); //obtengo el resultado de getPermisos y lo guardo en variable
		$DB= crearConexion("pac3_daw");
		//Si el valor es 0 cambia a 1, si no al contrario
		if($result==0){
			$sql="UPDATE Setup SET Autenticación=1"; 
		}elseif ($result==1){
			$sql="UPDATE Setup SET Autenticación=0";
		}

		$result=mysqli_query($DB, $sql); //Ejecuta la consulta correspondiente

		cerrarConexion($DB);
		
	}


	function getCategorias() {
		$DB= crearConexion("pac3_daw");
		$sql= "SELECT CategoryID, Name FROM category"; //Sentecia sql para recuperar las categorias
		$result = mysqli_query($DB, $sql);
		$fila = mysqli_fetch_assoc($result);	//Guardo cada fila de datos en un array
		if ($result) { //Si hay resultados...
			$output = "<select name='categoria'>\n";

        // Itera sobre los resultados y agrega opciones a la lista
        while ($fila = mysqli_fetch_assoc($result)) { //Y recorro el array montrando las opciones
            $selected = ($fila["CategoryID"] == $defecto) ? "selected" : "";
            $output .= "<option value='" . $fila["CategoryID"] . "' $selected>" . $fila["Name"] . "</option>\n";
			
        }

       		// Cierra la lista de opciones
        	$output .= "</select>\n";
	
			// Devuelve la tabla construida
			return $output;
		} else {
			// Maneja el error si la consulta falla
			return "Error en la consulta: " . mysqli_error($DB);
		}
		cerrarConexion($DB);
	}


	function getListaUsuarios() {
		$DB= crearConexion("pac3_daw");
		$sql= "SELECT FullName, Email, Enabled FROM user"; //Recupera los datos de los usuarios
		$result = mysqli_query($DB, $sql);
		$classCss="rojo"; //Guardo la clase css en una variable para usarla después
			
		if ($result) {
			// Inicia la tabla
			$output = "<table>\n";
			$output .= "<tr>\n";
			$output .= "<th>Nombre</th>";
			$output .= "<th>Email</th>";
			$output .= "<th>Permisos</th>";
			$output .= "</tr>\n";
	
			// Itera sobre los resultados y agrega filas a la tabla
			while ($fila = mysqli_fetch_assoc($result)) {
				$output .= "<tr>\n";
				$output .= "<td>" . $fila["FullName"] . "</td>";
				$output .= "<td>" . $fila["Email"] . "</td>";
				$claseCSS = ($fila["Enabled"] == 1) ? "rojo" : ""; //Si enabled es igual a 1, utiliza la clase css
            	$output .= "<td class='$claseCSS'>" . $fila["Enabled"] . "</td>";

            	$output .= "</tr>\n";
			}
	
			// Cierra la tabla
			$output .= "</table>\n";
	
			// Devuelve la tabla construida
			return $output;
		} else {
			// Manejo el error si la consulta no obtiene resultados
			return "Error en la consulta: " . mysqli_error($DB);
		}
		cerrarConexion($DB);
	}


	function getProducto($ID) {
		$DB= crearConexion("pac3_daw");
		$sql = "SELECT * FROM product WHERE ProductID=$ID"; //Sentencia sql para recuperar todos los datos del producto con id pasado por parámetro
		$result = mysqli_query($DB, $sql);

		if ($result) { //Si hay resultados devuelve los datos
			$row = mysqli_fetch_assoc($result);
			return $row;
		}

		cerrarConexion($DB);
	}


	function getProductos($orden) {
		$DB = crearConexion("pac3_daw");
	
		// Define la consulta base
		$sql = "SELECT p.ProductID, p.Name AS ProductName, p.Cost, p.Price, c.Name AS CategoryName
				FROM product p
				JOIN category c ON p.CategoryID = c.CategoryID";
	
		// Ordena los productos según el parámetro
		switch ($orden) {
			case 'productID':
				$sql .= " ORDER BY p.ProductID";
				break;
			case 'productName':
				$sql .= " ORDER BY p.Name";
				break;
			case 'cost':
				$sql .= " ORDER BY p.Cost";
				break;
			case 'price':
				$sql .= " ORDER BY p.Price";
				break;
			case 'categoryName':
				$sql .= " ORDER BY c.Name";
				break;
		
				
		}
	
		// Realiza la consulta
		$result = mysqli_query($DB, $sql);
	
		if ($result) {
			// Inicia la tabla
			$output = "<table id='productos'>\n";
			$output .= "<tr>\n";
			$output .= "<th><a href='?orden=productID'>ID de producto</a></th>";
			$output .= "<th><a href='?orden=productName'>Nombre</a></th>";
			$output .= "<th><a href='?orden=cost'>Costo</a></th>";
			$output .= "<th><a href='?orden=price'>Precio</a></th>";
			$output .= "<th><a href='?orden=categoryName'>Categoría</a></th>";
			$output .= "</tr>\n";
	
			// Itera sobre los resultados y agrega filas a la tabla
			while ($fila = mysqli_fetch_assoc($result)) {
				$output .= "<tr>\n";
				$output .= "<td>" . $fila["ProductID"] . "</td>";
				$output .= "<td>" . $fila["ProductName"] . "</td>";
				$output .= "<td>" . $fila["Cost"] . "</td>";
				$output .= "<td>" . $fila["Price"] . "</td>";
				$output .= "<td>" . $fila["CategoryName"] . "</td>";
				if(getPermisos()==1){ //Solo si permisos de app activados se mostraran enlaces para editar o borrar un producto
					$output .= "<td><a href='formArticulos.php?accion=Editar&id=" . $fila["ProductID"] . "'>Editar</a></td>";
					$output .= "<td><a href='formArticulos.php?accion=Borrar&id=" . $fila["ProductID"] . "'>Borrar</a></td>";
				
				}
				
				$output .= "</tr>\n";
				
			}
	
			// Cierra la tabla
			$output .= "</table>\n";
			$output .= "<td><a href='formArticulos.php?accion=Anadir'>Añadir</a></td><br>";
	
			// Devuelve la tabla construida
			return $output;
		} else {
			// Manejo el error si la consulta no obtiene resultados
			return "Error en la consulta: " . mysqli_error($DB);
		}
	
		cerrarConexion($DB);
	}
	


	function anadirProducto($nombre, $coste, $precio, $categoria) {
		$DB= crearConexion("pac3_daw");
		$sql= "INSERT INTO product (Name,Cost,Price,CategoryID) VALUES ('$nombre',$coste,$precio,$categoria)"; 
		//Sentencia sql usando los datos pasados por parámetro para incluir el nuevo producto a la bd
		$result = mysqli_query($DB, $sql);
		if($result){
			return "Inserción realizada con éxito"; //Transacción ok
		}else{
			// Manejo el error si la consulta no obtiene resultados
			return "Error en la consulta: " . mysqli_error($DB);
		}
		cerrarConexion($DB);
	}


	function borrarProducto($id) {
		$DB= crearConexion("pac3_daw");
		$sql= "DELETE FROM product WHERE ProductID=$id"; //Utilizo el id pasado por parámetro para encontrar el producto y eliminarlo de la bd
		$result = mysqli_query($DB, $sql);
		if($result){
			return "Borrado con éxito con éxito"; //Transacción ok
		}else{
			// Manejo el error si la consulta no obtiene resultados
			return "Error en la consulta: " . mysqli_error($DB);
		}	
		cerrarConexion($DB);
	}


	function editarProducto($id, $nombre, $coste, $precio, $categoria) {
		$DB= crearConexion("pac3_daw");
		$sql= "UPDATE product
		SET Name = '$nombre', Cost = $coste, Price = $precio, CategoryID = $categoria
		WHERE ProductID = $id"; //Sentencia sql para modificar el producto con los nuevos datos pasados por parámetro
		$result = mysqli_query($DB, $sql);
		if($result){
			return "Actualización realizada con éxito"; //Transacción ok
		}else{
			// Manejo el error si la consulta no obtiene resultados
			return "Error en la consulta: " . mysqli_error($DB);
		}
		cerrarConexion($DB);	
	}
	

?>