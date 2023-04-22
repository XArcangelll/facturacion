<?php

session_start();

if($_SESSION["rol"] != 1 && $_SESSION["rol"]!= 2){
	header("location: ./");
}


include "../conexion.php";

	if(!empty($_POST)){
		$alert = "";
		if(empty($_POST["proveedor"]) || empty($_POST["contacto"]) || empty($_POST["telefono"]) || empty($_POST["direccion"]))
		{

			$alert = "<p class='msg_error'> Todos los campos son obligatorios </p>";

		}else{
				$codproveedor = $_POST["codproveedor"];
                $proveedor = $_POST["proveedor"];
				$contacto = $_POST["contacto"];
				$telefono = $_POST["telefono"];
				$direccion = $_POST["direccion"];

				$query = mysqli_query($connection,"select * from proveedor where proveedor = '$proveedor' and codproveedor != $codproveedor and estatus = 1 ");
				$result = mysqli_fetch_array($query);

				if($result > 0){
					$alert = "<p class='msg_error'> El Proveedor ya existe </p>";
				}else{
				$query_update = mysqli_query($connection, "Update proveedor set proveedor = '$proveedor',contacto = '$contacto', telefono= '$telefono', direccion = '$direccion' where codproveedor = $codproveedor" );
								

					if($query_update){
						$alert = "<p class='msg_save'> Proveedor actualizado correctamente</p>";

					}else{
						$alert = "<p class='msg_error'> Error al actualizar el Proveedor</p>";
					}
				}


				

		}
		mysqli_close($connection);	
	}

	include "../conexion.php";
    //mostrar datos
    if(empty($_GET["id"])){
        header("location: lista_proveedor.php");
		mysqli_close($connection);	
    }

	$codproveedor =  $_GET["id"];

	$sql = mysqli_query($connection,"select * from proveedor where codproveedor = $codproveedor and estatus = 1");
	mysqli_close($connection);	
	$result = mysqli_num_rows($sql);

	if($result == 0){
		header("location: lista_proveedor.php");
	}else{

		while($data = mysqli_fetch_array($sql)){

			$codproveedor = $data["codproveedor"];
            $proveedor = $data["proveedor"];
			$contacto = $data["contacto"];
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
	<title>Actualizar Proveedor</title>
</head>
<body>
	
	<?php include "includes/header.php" ?>

	<section id="container">
		
			<div class="form_register">
				<h1 ><i class="fa-solid fa-pen-to-square"></i> Actualizar Proveedor</h1>
				<hr>
					<div class="alert">
							<?php echo isset($alert) ? $alert : ""; ?>
					</div>

					<form action="" method="post">
						<input type="hidden" name="codproveedor" value="<?php echo $codproveedor ?>" >
                        <label for="proveedor">Proveedor</label>
						<input type="text" name="proveedor" value="<?php echo $proveedor?>" id="proveedor" placeholder="Proveedor">
						<label for="contacto">Contacto</label>
						<input type="text" name="contacto" value="<?php echo $contacto?>" id="contacto" placeholder="Contacto">
						<label for="telefono">Teléfono</label>
						<input type="number" name="telefono" value="<?php echo $telefono?>" id="telefono" placeholder="Teléfono">
						<label for="direccion">Dirección</label>
						<input type="text" name="direccion" value="<?php echo $direccion?>" id="direccion" placeholder="Dirección">
						
						<button type="submit" class="btn-save"><i class="fa-solid fa-pen-to-square"></i> Actualizar Proveedor</button>


					</form>

			</div>


	</section>

	<?php include "includes/footer.php" ?>

</body>
</html>