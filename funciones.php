<?php 

	include "consultas.php";

	//Guarda en una variable el resultado de getCategorias y lo imprime
	function pintaCategorias($defecto) {
		$categoriasHtml = getCategorias(); 
		echo $categoriasHtml;
			
	}
	
	//Guarda en una variable el resultado de getUsuarios y lo imprime
	function pintaTablaUsuarios(){
		$usuariosHtml=getListaUsuarios(true);
		echo $usuariosHtml;
	}

	//Guarda en una variable el resultado de getProductos y lo imprime
	function pintaProductos($orden) {
		$productosHtml=getProductos($orden);
		echo $productosHtml;
	}

?>