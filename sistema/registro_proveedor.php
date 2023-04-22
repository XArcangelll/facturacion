<?php

session_start();

if($_SESSION["rol"]!= 1 && $_SESSION["rol"]!= 2){
	header("location: ./");
}


//mouse herramienta misteriosa que nos servira para los filtros 
//     $date = date_create('2023-04-09 17:01:08');
//echo date_format($date,'d-m-Y');

include "../conexion.php";

	if(!empty($_POST)){
		$alert = "";
		if(empty($_POST["proveedor"]) || empty($_POST["contacto"]) || empty($_POST["telefono"]) 
		 || empty($_POST["direccion"]) )
		{

			$alert = "<p class='msg_error'> Todos los campos son obligatorios </p>";

		}else{
		
			
                $proveedor = $_POST["proveedor"];
				$contacto = $_POST["contacto"];
				$telefono = $_POST["telefono"];
				$direccion = $_POST["direccion"];
                $idusuario = $_SESSION["idUser"];

				$query = mysqli_query($connection,"select * from proveedor where proveedor = '$proveedor' ");
				
				$result = mysqli_fetch_array($query);

				if($result > 0){
					$alert = "<p class='msg_error'> El proveedor ya esta registrado </p>";
				}else{

						$query_insert = mysqli_query($connection,"INSERT INTO proveedor(proveedor,contacto,telefono,direccion,usuario_id) values('$proveedor','$contacto','$telefono','$direccion',$idusuario) ");
						if($query_insert){
							$alert = "<p class='msg_save'> Proveedor guardado correctamente</p>";
	
						}else{
							$alert = "<p class='msg_error'> Error al guardar el Proveedor</p>";
						}							
				}
		}

        mysqli_close($connection);
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	
	<?php include "includes/scripts.php" ?>
	<title>Registro Proveedor</title>
</head>
<body>
	
	<?php include "includes/header.php" ?>

	<section id="container">
		
			<div class="form_register">
				<h1><i class="fa-solid fa-boxes-packing"></i> Registro Proveedor</h1>
				<hr>

					<div class="alert">
							<?php echo isset($alert) ? $alert : ""; ?>
					</div>

					<form action="" method="post">
                        <label for="proveedor">Proveedor</label>
						<input type="text" name="proveedor" id="proveedor" placeholder="Proveedor">
						<label for="contacto">Contacto</label>
						<input type="text" name="contacto" id="contacto" placeholder="Contacto">
						<label for="telefono">Teléfono</label>
						<input type="number" name="telefono" id="telefono" placeholder="Teléfono">
						<label for="direccion">Dirección</label>
						<input type="text" name="direccion" id="direccion" placeholder="Dirección">
						
						<button type="submit" class="btn-save"><i class="fa-solid fa-floppy-disk"></i> Crear Proveedor</button>


					</form>

			</div>


	</section>

	<?php include "includes/footer.php" ?>

</body>
</html>