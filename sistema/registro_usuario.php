<?php

session_start();



if($_SESSION["rol"]!= 1){
	header("location: ./");
}



include "../conexion.php";



					

	if(!empty($_POST)){
		$alert = "";
		if(empty($_POST["nombre"]) || empty($_POST["correo"]) || empty($_POST["usuario"]) 
		 || empty($_POST["clave"])  || empty($_POST["rol"]))
		{

			$alert = "<p class='msg_error'> Todos los campos son obligatorios </p>";

		}else{
		
			

				$nombre = $_POST["nombre"];
				$email = $_POST["correo"];
				$user = $_POST["usuario"];
				$clave = md5($_POST["clave"]);
				$rol = $_POST["rol"];

				$query = mysqli_query($connection,"select * from usuario where usuario = '$user' or correo = '$email' ");
				
				$result = mysqli_fetch_array($query);

				if($result > 0){
					$alert = "<p class='msg_error'> El correo y/o el usuario ya existe </p>";
				}else{
					$roles = [];
					$query2 = mysqli_query($connection,"select idrol from rol");
					while( $aea =	$result2 = mysqli_fetch_array($query2)){
					array_push($roles,$aea["idrol"]);
					}

					if(in_array($rol,$roles) == false){
						$alert = "<p class='msg_save'> El id no existe no te pases</p>";
					}else{
					if( $rol != 1 ){
						$query_insert = mysqli_query($connection,"INSERT INTO usuario(nombre,correo,usuario,clave,rol) values('$nombre','$email','$user','$clave','$rol') ");
						mysqli_close($connection);	
						if($query_insert){
							$alert = "<p class='msg_save'> Usuario creado correctamente</p>";
	
						}else{
							$alert = "<p class='msg_error'> Error al crear el usuario</p>";
						}
					}else{
						$alert = "<p class='msg_error'> No te pases de vivo</p>";
					}

				}
				
				
				}


				

		}
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	
	<?php include "includes/scripts.php" ?>
	<title>Registro Usuario</title>
</head>
<body>
	
	<?php include "includes/header.php" ?>

	<section id="container">
		
			<div class="form_register">
				<h1><i class="fa-solid fa-users"></i> Registro Usuario</h1>
				<hr>
					<div class="alert">
							<?php echo isset($alert) ? $alert : ""; ?>
					</div>

					<form action="" method="post">
						<label for="nombre">Nombre</label>
						<input type="text" name="nombre" id="nombre" placeholder="Nombre Completo">
						<label for="correo">Correo Electrónico</label>
						<input type="email" name="correo" id="correo" placeholder="Correo Electrónico">
						<label for="usuario">Usuario</label>
						<input type="text" name="usuario" id="usuario" placeholder="Usuario">
						<label for="clave">Clave</label>
						<input type="password" name="clave" id="clave" placeholder="Clave de acceso">
						<label for="rol">Tipo Usuario</label>


							<?php
							include "../conexion.php";
							$query_rol = mysqli_query($connection,"select * from rol");		
							mysqli_close($connection);					
							$result_rol = mysqli_num_rows($query_rol);

							?>

							<select name="rol" id="rol">
							<option value="">Seleccione un rol</option>
							<?php

							if($result_rol > 0){
								while($rol = mysqli_fetch_array($query_rol)){
									if($rol["idrol"] != 1){
							?>
								<option value="<?php echo $rol["idrol"] ?>"><?php echo $rol["rol"] ?></option>
							<?php
								}
								}
							}

							?>	
						</select>

					
						<button type="submit" class="btn-save"><i class="fa-solid fa-floppy-disk"></i> Crear Usuario</button>

					</form>

			</div>


	</section>

	<?php include "includes/footer.php" ?>

</body>
</html>