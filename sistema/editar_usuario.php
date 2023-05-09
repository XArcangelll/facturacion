<?php

session_start();

if($_SESSION["rol"]!= 1){
	header("location: ./");
}

include "../conexion.php";

	if(!empty($_POST)){
		$alert = "";
		if(empty($_POST["nombre"]) || empty($_POST["correo"]) || empty($_POST["usuario"]) 
		  || empty($_POST["rol"]))
		{

			$alert = "<p class='msg_error'> Todos los campos son obligatorios </p>";

		}else{
		
			
				$idusuario = $_POST["idusuario"];
				$nombre = $_POST["nombre"];
				$email = $_POST["correo"];
				$user = $_POST["usuario"];
				$clave = md5($_POST["clave"]);
				$rol = $_POST["rol"];

				$query = mysqli_query($connection,"select * from usuario where ((usuario = '$user' and idusuario != $idusuario) or (correo = '$email' and idusuario != $idusuario )) and estatus = 1");
				$result = mysqli_fetch_array($query);

				if($result > 0){
					$alert = "<p class='msg_error'> El correo y/o el usuario ya existe </p>";
				}else{

					if($rol == 1 && $idusuario == 13  ){	

						if($idusuario == 13){
							if(empty($_POST["clave"])){
								$query_update = mysqli_query($connection, "Update usuario set nombre = '$nombre', correo= '$email', usuario = '$user' where idusuario = $idusuario" );
							}else{
								$query_update = mysqli_query($connection, "Update usuario set nombre = '$nombre', correo= '$email', usuario = '$user', clave = '$clave'where idusuario = $idusuario" );
							}
	
							if($query_update){
								$alert = "<p class='msg_save'> Usuario actualizado correctamente administrador </p>";
		
							}else{
								$alert = "<p class='msg_error'> Error al actualizar el usuario</p>";
							}

						}else{
							$alert = "<p class='msg_error'> No te pases de vivo adm</p>";
						}
				

				}else{


					if($idusuario != 13 && $rol !=1){
					if(empty($_POST["clave"])){
						$query_update = mysqli_query($connection, "Update usuario set nombre = '$nombre', correo= '$email', usuario = '$user', rol='$rol' where idusuario = $idusuario" );
					}else{
						$query_update = mysqli_query($connection, "Update usuario set nombre = '$nombre', correo= '$email', usuario = '$user', clave = '$clave', rol='$rol' where idusuario = $idusuario" );
					}

					

					if($query_update){
						$alert = "<p class='msg_save'> Usuario actualizado correctamente</p>";

					}else{
						$alert = "<p class='msg_error'> Error al crear el usuario</p>";
					}

				}else{
					$alert = "<p class='msg_error'> No te pases de vivo adm</p>";
				}
				


				}
				
				}


				

		}
		mysqli_close($connection);	
	}

	include "../conexion.php";
    //mostrar datos
    if(empty($_GET["id"])){
        header("location: lista_usuario.php");
		mysqli_close($connection);	
    }

	$iduser =  $_GET["id"];
	if($iduser == 13){
		header("location: lista_usuario.php");
	}

	$sql = mysqli_query($connection,"select u.idusuario,u.nombre,u.correo,u.usuario, (u.rol) as idrol, (r.rol) as rol from usuario u inner join rol r on r.idrol = u.rol where idusuario = $iduser and estatus = 1");
	mysqli_close($connection);	
	$result = mysqli_num_rows($sql);

	if($result == 0){
		header("location: lista_usuario.php");
	}else{

		while($data = mysqli_fetch_array($sql)){

			$iduser = $data["idusuario"];
			$nombre = $data["nombre"];
			$correo = $data["correo"];
			$usuario = $data["usuario"];
			$idrol = $data["idrol"];
			$rol = $data["rol"];

			$globalidrol = $idrol;
		}

	}



?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	
	<?php include "includes/scripts.php" ?>
	<title>Actualizar Usuario</title>
</head>
<body>
	
	<?php include "includes/header.php" ?>

	<section id="container">
		
			<div class="form_register">
				<h1 ><i class="fa-solid fa-pen-to-square"></i> Actualizar Usuario</h1>
				<hr>
					<div class="alert">
							<?php echo isset($alert) ? $alert : ""; ?>
					</div>

					<form action="" method="post">
						<input type="hidden" name="idusuario" value="<?php echo $iduser ?>" >
						<label for="nombre">Nombre</label>
						<input type="text" name="nombre" value="<?php echo $nombre?>" id="nombre" placeholder="Nombre Completo">
						<label for="correo">Correo Electrónico</label>
						<input type="email" name="correo" value="<?php echo $correo?>" id="correo" placeholder="Correo Electrónico">
						<label for="usuario">Usuario</label>
						<input type="text" name="usuario" value="<?php echo $usuario?>" id="usuario" placeholder="Usuario">
						<label for="clave">Clave</label>
						<input type="password" name="clave" id="clave" placeholder="Clave de acceso">

<?php

if($idrol != 1){
?>

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
								<option <?php echo ($rol["idrol"] == $idrol) ? "selected" : "" ; ?> value="<?php echo $rol["idrol"] ?>"><?php echo $rol["rol"] ?></option>
							<?php
									}
								}
							}

							?>	
						</select>


<?php
}else{
?>

<input type="hidden" name="rol" value="<?php echo $idrol ?>" >

<?php

}
?>

<button type="submit" class="btn-save"><i class="fa-solid fa-pen-to-square"></i> Actualizar Usuario</button>


					</form>

			</div>


	</section>

	<?php include "includes/footer.php" ?>

</body>
</html>