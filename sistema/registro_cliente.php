<?php

session_start();


//mouse herramienta misteriosa que nos servira para los filtros 
//     $date = date_create('2023-04-09 17:01:08');
//echo date_format($date,'d-m-Y');

include "../conexion.php";

	if(!empty($_POST)){
		$alert = "";
		if(empty($_POST["nombre"]) || empty($_POST["telefono"]) || empty($_POST["direccion"]) 
		 || empty($_POST["dni"]) )
		{

			$alert = "<p class='msg_error'> Todos los campos son obligatorios </p>";

		}else{
		
			
                $dni = $_POST["dni"];
				$nombre = $_POST["nombre"];
				$telefono = $_POST["telefono"];
				$direccion = $_POST["direccion"];
                $idusuario = $_SESSION["idUser"];

				$query = mysqli_query($connection,"select * from cliente where dni = '$dni' and estatus = 1");
				
				$result = mysqli_fetch_array($query);

				if($result > 0){
					$alert = "<p class='msg_error'> El DNI ya está en uso </p>";
				}else{

						$query_insert = mysqli_query($connection,"INSERT INTO cliente(dni,nombre,telefono,direccion,usuario_id) values('$dni','$nombre','$telefono','$direccion',$idusuario) ");
						if($query_insert){
							$alert = "<p class='msg_save'> Cliente guardado correctamente</p>";
	
						}else{
							$alert = "<p class='msg_error'> Error al guardar el cliente</p>";
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
	<title>Registro Cliente</title>
</head>
<body>
	
	<?php include "includes/header.php" ?>

	<section id="container">
		
			<div class="form_register">
				<h1><i class="fa-solid fa-users"></i> Registro Cliente</h1>
				<hr>

					<div class="alert">
							<?php echo isset($alert) ? $alert : ""; ?>
					</div>

					<form action="" method="post">
                        <label for="dni">Documento Nacional de Identidad</label>
						<input type="number" name="dni" id="dni" placeholder="Documento Nacional de Identidad">
						<label for="nombre">Nombre</label>
						<input type="text" name="nombre" id="nombre" placeholder="Nombre Completo">
						<label for="telefono">Teléfono</label>
						<input type="number" name="telefono" id="telefono" placeholder="Teléfono">
						<label for="direccion">Dirección</label>
						<input type="text" name="direccion" id="direccion" placeholder="Dirección">
						
						<button type="submit" class="btn-save"><i class="fa-solid fa-floppy-disk"></i> Crear Cliente</button>


					</form>

			</div>


	</section>

	<?php include "includes/footer.php" ?>

</body>
</html>