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
		if(empty($_POST["proveedor"]) || empty($_POST["descripcion"]) || empty($_POST["precio"]) 
		 || empty($_POST["cantidad"]) )
		{

			$alert = "<p class='msg_error'> Los campos Proveedor,Descripcipon,Precio y Cantidad son Obligatorios </p>";

		}else{
		
			
                $proveedor = $_POST["proveedor"];
				$descripcion = $_POST["descripcion"];
				$precio = $_POST["precio"];
				$cantidad = $_POST["cantidad"];
                $idusuario = $_SESSION["idUser"];

				$foto = $_FILES["foto"];
				$nombre_foto = $foto["name"];
				$type = $foto["type"];
				$url_temp = $foto["tmp_name"];

				$imgProducto = "img_producto.png";

				if($nombre_foto != ""){
					$destino = "img/uploads/";
					$img_nombre = "img_" . md5(date("d-m-Y H:m:s"));
					$imgProducto = $img_nombre.".jpg";
					$src = $destino.$imgProducto;
				}


						$query_insert = mysqli_query($connection,"INSERT INTO producto(proveedor,descripcion,precio,existencia,usuario_id,foto) values($proveedor,'$descripcion','$precio','$cantidad',$idusuario,'$imgProducto') ");
						if($query_insert){
									if($nombre_foto != ""){
										move_uploaded_file($url_temp,$src);
									}

								
							$alert = "<p class='msg_save'> Producto guardado correctamente</p>";
							
	
						}else{
							$alert = "<p class='msg_error'> Error al guardar el Producto</p>";
						}							
				
		}

   
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	
	<?php include "includes/scripts.php" ?>
	<title>Registro Producto</title>
</head>
<body>
	
	<?php include "includes/header.php" ?>

	<section id="container">
		
			<div class="form_register">
				<h1><i class="fa-solid fa-store"></i> Registro Producto</h1>
				<hr>

					<div class="alert">
							<?php echo isset($alert) ? $alert : ""; ?>
					</div>

					<form action="" method="post" enctype="multipart/form-data">
                        <label for="proveedor">Proveedor</label>

							<?php
							
								$query_proveedor = mysqli_query($connection,"Select codproveedor,proveedor from proveedor where estatus = 1 order by proveedor asc");

									$result_proveedor = mysqli_num_rows($query_proveedor);
									mysqli_close($connection);
							?>

						<select name="proveedor" id="proveedor">
							
						<?php   
						
							if($result_proveedor > 0){
								while($proveedor = mysqli_fetch_array($query_proveedor)){
									?>
											<option value="<?php echo $proveedor["codproveedor"]?>"><?php echo $proveedor["proveedor"]?></option>
									<?php
								}
							}

						?>
						</select>
						
						<label for="producto">Producto</label>
						<input type="text" name="descripcion" id="descripcion" placeholder="Descripción del Producto">

						<label for="precio">Precio</label>
						<input type="number" name="precio" step="0.01" id="precio" placeholder="Precio del Producto">
						<label for="cantidad">Cantidad del producto</label>
						<input type="number" name="cantidad" id="cantidad" placeholder="Cantidad del Producto">

						<div class="photo">
							<label for="foto">Foto</label>
								<div class="prevPhoto">
										<span class="delPhoto notBlock">X</span>
										<label for="foto"></label>
								</div>
								<div class="upimg">
								<input type="file" name="foto" id="foto">
								</div>
								<div id="form_alert"></div>
						</div>
						
						<button type="submit" class="btn-save"><i class="fa-solid fa-floppy-disk"></i> Crear Producto</button>


					</form>

			</div>


	</section>

	<?php include "includes/footer.php" ?>

</body>
</html>