
<?php

session_start();




?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	
	<?php include "includes/scripts.php" ?>
	<title>Sistema Ventas</title>
</head>
<body>
	
	<?php include "includes/header.php";
	
	include "../conexion.php";

	//datos empresa
	$ruc = '';
	$nombreEmpresa = '';
	$razonSocial = '';
	$telEmpresa = '';
	$emailEmpresa = '';
	$dirEmpresa = '';
	$iva = '';

	$query_empresa = mysqli_query($connection,"SELECT * FROM configuracion");
	$row_empresa = mysqli_num_rows($query_empresa);
	if($row_empresa > 0){
		while($arrInfoEmpresa = mysqli_fetch_assoc($query_empresa)) {
			$ruc = $arrInfoEmpresa["ruc"];
			$nombreEmpresa = $arrInfoEmpresa["nombre"];
			$razonSocial = $arrInfoEmpresa["razon_social"];
			$telEmpresa = $arrInfoEmpresa["telefono"];
			$emailEmpresa = $arrInfoEmpresa["email"];
			$dirEmpresa = $arrInfoEmpresa["direccion"];
			$iva = $arrInfoEmpresa["iva"];
		}
	}



	$query_dash = mysqli_query($connection,"CALL dataDashboard();");
	$result_dash = mysqli_num_rows($query_dash);
	if($result_dash > 0){
		$data_hash = mysqli_fetch_assoc($query_dash);
		mysqli_close($connection);
	}


	?>

	<section id="container">

			<div class="divContainer">
				<div>
					<h1 class="titlePanelControl">
						Panel de Control
					</h1>
				</div>

				<div class="dashboard">

			   <?php
				
				if($_SESSION["rol"] == 1){

				?>

					<a href="lista_usuario.php">
					<i class="fa-solid fa-user"></i>
					<p>
						<strong>Usuarios</strong><br>
						<span><?php echo $data_hash["usuarios"] ?></span>
					</p>
					</a>

					<?php } ?>

					<a href="lista_cliente.php">
					<i class="fa-solid fa-user"></i>
					<p>
						<strong>Clientes</strong><br>
						<span><?php echo $data_hash["clientes"] ?></span>
					</p>
					</a>

					<?php if($_SESSION["rol"] == 1 || $_SESSION["rol"] == 2){ ?>
					<a href="lista_proveedor.php">
					<i class="fa-solid fa-boxes-packing"></i>
					<p>
						<strong>Proveedores</strong><br>
						<span><?php echo $data_hash["proveedores"] ?></span>
					</p>
					</a>
					<?php } ?>
				<?php if($_SESSION["rol"] == 1 || $_SESSION["rol"] == 2){ ?>
					<a href="lista_producto.php">
					<i class="fa-solid fa-store"></i>
					<p>
						<strong>Productos</strong><br>
						<span><?php echo $data_hash["productos"] ?></span>
					</p>
					</a>
					<?php } ?>
					<a href="ventas.php">
					<i class="fa-solid fa-money-bill"></i>
					<p>
						<strong>Ventas Exitosas</strong><br>
						<span><?php echo $data_hash["ventasP"] ?></span>
					</p>
					</a>
					<a href="ventas.php">
					<i class="fa-solid fa-money-bill"></i>
					<p>
						<strong>Ventas Anuladas</strong><br>
						<span><?php echo $data_hash["ventasA"] ?></span>
					</p>
					</a>
					</a>
					<a href="ventas.php">
					<i class="fa-solid fa-money-bill"></i>
					<p>
						<strong>Ventas de Hoy</strong><br>
						<span><?php echo $data_hash["ventasH"] ?></span>
					</p>
					</a>
					<a href="ventas.php">
					<i class="fa-solid fa-money-bill"></i>
					<p>
						<strong>Ganancias de Hoy</strong><br>
						<span>S/. <?php echo ($data_hash["ventasGH"] == "") ? "0.00" : $data_hash["ventasGH"];?></span>
					</p>
					</a>
				</div>
			</div>


	<div class="divInfoSistema">
	<div>
					<h1 class="titlePanelControl">
						Configuración
					</h1>
				</div>

				<div class="containerPerfil">
					<div class="containerDataUser">
							<div class="logoUser">
								<img src="img/userlogo.png" >
							</div>
							<div class="divDataUser">
								<h4>Información Personal</h4>

									<div>
										<label>Nombre:</label> <span><?php echo  $_SESSION["nombre"]?> </span>
									</div>
									<div>
										<label>Correo:</label> <span><?php echo $_SESSION["email"]?></span>
									</div>

									<h4>Datos Usuario</h4>
									<div>
										<label>Rol:</label> <span> <?php echo  $_SESSION["nombreRol"]?></span>
									</div>
									<div>
										<label>Usuario:</label> <span> <?php echo  $_SESSION["user"]?></span>
									</div>

									<h4>Cambiar Contraseña</h4>

									<form action="" method="post" name="frmChangePass" id="frmChangePass">
											<div>
												<input type="password" name="txtPassUser" id="txtPassUser" placeholder="Contraseña actual" required> 
											</div>
											<div>
												<input class="newPass" type="password" name="txtNewPassUser" id="txtNewPassUser" placeholder="Nueva Contraseña" required> 
											</div>
											<div>
												<input class="newPass" type="password" name="txtPassConfirm" id="txtPassConfirm" placeholder="Confirmar Contraseña" required> 
											</div>
											<div class="alertChangePass" style="display: none;">

											</div>
											<div>
												<button type="submit" class="btn_save btnChangePass"><i class="fas fa-key"></i> Cambiar Contraseña</button>
											</div>
									</form>

							</div>
					</div>
					<?php if($_SESSION["rol"] == 1){ ?>
					<div class="containerDataEmpresa">
							<div class="logoEmpresa">
								<img src="img/userlogo.png" >
							</div>
							<h4>Datos de la Empresa</h4>
							<form action="" method="post" name="frmEmpresa" id="frmEmpresa">
									<input type="hidden" name="action" value="updateDataEmpresa">

									<div>
										<label>RUC:</label>
										<input type="number" name="txtRUC" id="txtRUC" placeholder="RUC de la empresa" value="<?php echo $ruc?>"  required>
									</div>
									<div>
										<label>Nombre:</label>
										<input type="text" name="txtNombre" id="txtNombre" placeholder="Nombre de la empresa" value="<?php echo $nombreEmpresa?>"  required>
									</div>
									<div>
										<label>Razon Social:</label>
										<input type="text" name="txtRSocial" id="txtRSocial" placeholder="Razón Social" value="<?php echo $razonSocial?>"  required>
									</div>
									<div>
										<label>Teléfono:</label>
										<input type="number" name="txtTelEmpresa" id="txtTelEmpresa" placeholder="Número de Teléfono" value="<?php echo $telEmpresa?>"  required>
									</div>
									<div>
										<label>Correo electrónico:</label>
										<input type="email" name="txtEmailEmpresa" id="txtEmailEmpresa" placeholder="Correo Electrónico" value="<?php echo $emailEmpresa?>"  required>
									</div>
									<div>
										<label>Dirección:</label>
										<input type="text" name="txtDirEmpresa" id="txtDirEmpresa" placeholder="Dirección de la Empresa" value="<?php echo $dirEmpresa?>"  required>
									</div>
									<div>
										<label>IVA (%):</label>
										<input type="number" name="txtIVA" id="txtIVA" placeholder="Impuesto al valor Agregado (IVA)" min="0" step="0.01" value="<?php echo $iva?>"  required>
									</div>
									<div class="alertFormEmpresa" style="display: none;"></div>
									<div>
										<button type="submit" class="btn_save btnChangePass"><i class="fas fa-save fa-lg"></i> Guardar Datos</button>
									</div>

							</form>
					</div>

					<?php } ?>
				</div>

	</div>

	</section>

	<?php include "includes/footer.php" ?>

</body>
</html>