<?php

session_start();


include "../conexion.php";

	if(!empty($_POST)){
		$alert = "";
		if(empty($_POST["dni"]) || empty($_POST["nombre"]) || empty($_POST["telefono"]) || empty($_POST["direccion"]))
		{

			$alert = "<p class='msg_error'> Todos los campos son obligatorios </p>";

		}else{
				$idcliente = $_POST["idcliente"];
                $dni = $_POST["dni"];
				$nombre = $_POST["nombre"];
				$telefono = $_POST["telefono"];
				$direccion = $_POST["direccion"];

				$query = mysqli_query($connection,"select * from cliente where dni = '$dni' and idcliente != $idcliente and estatus = 1 ");
				$result = mysqli_fetch_array($query);

				if($result > 0){
					$alert = "<p class='msg_error'> El dni ya existe </p>";
				}else{
				$query_update = mysqli_query($connection, "Update cliente set dni = '$dni',nombre = '$nombre', telefono= '$telefono', direccion = '$direccion' where idcliente = $idcliente and estatus = 1" );
								

					if($query_update){
						$alert = "<p class='msg_save'> Cliente actualizado correctamente</p>";

					}else{
						$alert = "<p class='msg_error'> Error al actualizar el cliente</p>";
					}
				}


				

		}
		mysqli_close($connection);	
	}

	include "../conexion.php";
    //mostrar datos
    if(empty($_GET["id"])){
        header("location: lista_cliente.php");
		mysqli_close($connection);	
    }

	$idcliente =  $_GET["id"];

	$sql = mysqli_query($connection,"select  * from cliente where idcliente = $idcliente and estatus = 1");
	mysqli_close($connection);	
	$result = mysqli_num_rows($sql);

	if($result == 0){
		header("location: lista_cliente.php");
	}else{

		while($data = mysqli_fetch_array($sql)){

			$idcliente = $data["idcliente"];
            $dni = $data["dni"];
			$nombre = $data["nombre"];
			$telefono = $data["telefono"];
			$direccion = $data["direccion"];
		}

	}



?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	
	<?php include "includes/scripts.php" ?>
	<title>Actualizar Cliente</title>
</head>
<body>
	
	<?php include "includes/header.php" ?>

	<section id="container">
		
			<div class="form_register">
				<h1 ><i class="fa-solid fa-pen-to-square"></i> Actualizar Cliente</h1>
				<hr>
					<div class="alert">
							<?php echo isset($alert) ? $alert : ""; ?>
					</div>

					<form action="" method="post">
						<input type="hidden" name="idcliente" value="<?php echo $idcliente ?>" >
                        <label for="dni">DNI</label>
						<input type="number" name="dni" value="<?php echo $dni?>" id="dni" placeholder="Documento Nacional de Identidad">
						<label for="nombre">Nombre</label>
						<input type="text" name="nombre" value="<?php echo $nombre?>" id="nombre" placeholder="Nombre Completo">
						<label for="telefono">Teléfono</label>
						<input type="number" name="telefono" value="<?php echo $telefono?>" id="telefono" placeholder="Teléfono">
						<label for="direccion">Dirección</label>
						<input type="text" name="direccion" value="<?php echo $direccion?>" id="direccion" placeholder="Dirección">
						
						<button type="submit" class="btn-save"><i class="fa-solid fa-pen-to-square"></i> Actualizar Cliente</button>


					</form>

			</div>


	</section>

	<?php include "includes/footer.php" ?>

</body>
</html>