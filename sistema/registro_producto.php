<?php

session_start();

if($_SESSION["rol"]!= 1 && $_SESSION["rol"]!= 2){
	header("location: ./");
}



//mouse herramienta misteriosa que nos servira para los filtros 
//     $date = date_create('2023-04-09 17:01:08');
//echo date_format($date,'d-m-Y');

include "../conexion.php";

$hashmap = array();
$opciones = "";

$query_proveedor = mysqli_query($connection,"Select codproveedor,proveedor from proveedor where estatus = 1 order by proveedor asc");
$result_proveedor = mysqli_num_rows($query_proveedor);
if($result_proveedor > 0){
	while($proveedor = mysqli_fetch_array($query_proveedor)){
		$hashmap[$proveedor["codproveedor"]] = $proveedor["proveedor"];
		
			$opciones .=	'<option value="'.$proveedor["codproveedor"].'">'.$proveedor["proveedor"].'</option>';
		
	}
}

$hashmapCategoria = array();
$opcionesCategoria = "";

$query_categoria = mysqli_query($connection,"Select idcategoria,nombrecat from categoria where estatus = 1 order by nombrecat asc");
$result_categoria = mysqli_num_rows($query_categoria);
if($result_categoria > 0){
	while($categoria = mysqli_fetch_array($query_categoria)){
		$hashmapCategoria[$categoria["idcategoria"]] = $categoria["nombrecat"];
		
			$opcionesCategoria .=	'<option value="'.$categoria["idcategoria"].'">'.$categoria["nombrecat"].'</option>';
		
	}
}





	if(!empty($_POST)){
		$alert = "";
		if(empty($_POST["proveedor"]) || empty($_POST["descripcion"]) || empty($_POST["precio"]) 
		 || empty($_POST["cantidad"])  || empty($_POST["medida"]) || empty($_POST["categoria"]) )
		{

			$alert = "<p class='msg_error'>" .$_POST["proveedor"]." Los campos Proveedor,Descripcipon,Precio y Cantidad son Obligatorios </p>";

		
		}else{
		
			
                $proveedor = $_POST["proveedor"];
				$descripcion = $_POST["descripcion"];
				$precio = $_POST["precio"];
				$cantidad = $_POST["cantidad"];
				$medida = $_POST["medida"];
				$adicion = $_POST["adicion"];
				$idcategoria = $_POST["categoria"];

				if(empty($adicion)){
					$adicion = 0.00;
				}

				

				if($medida != 1 && $medida != 2){
					$medida = 1;
				}

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


					if(!array_key_exists($proveedor,$hashmap) || !array_key_exists($idcategoria,$hashmapCategoria)){
						$alert = "<p class='msg_error'> No seas chistoso sobrino</p>";
					}else{

						$query_insert = mysqli_query($connection,"INSERT INTO producto(proveedor,descripcion,precio,existencia,adicion,idcategoria,codmedida,usuario_id,foto) values($proveedor,'$descripcion','$precio','$cantidad',$adicion, $idcategoria, $medida, $idusuario,'$imgProducto') ");
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
						<select name="proveedor" id="proveedor">
							
						<?php echo $opciones?>

						</select>

						<label for="categoria">Categoria</label>
						<select name="categoria" id="categoria">
							
						<?php echo $opcionesCategoria?>

						</select>


						<label for="producto">Producto</label>
						<input type="text" name="descripcion" id="descripcion" placeholder="Descripción del Producto">

						<label for="precio">Precio</label>
						<input type="number" name="precio" step="0.01" id="precio" placeholder="Precio del Producto">
						<label for="cantidad">Cantidad del producto</label>
						<input type="number" name="cantidad" id="cantidad" placeholder="Cantidad del Producto">

						<label for="medida">Medida</label>

						<?php

							$query_medida = mysqli_query($connection,"Select codmedida,nombre from medida");

								$result_medida = mysqli_num_rows($query_medida);
								mysqli_close($connection);
						?>

						<select name="medida" id="medida">

						<?php   

						if($result_medida > 0){
							while($medida = mysqli_fetch_array($query_medida)){
								?>
										<option value="<?php echo $medida["codmedida"]?>"><?php echo $medida["nombre"]?></option>
								<?php
							}
						}

						?>
						</select>

						<label for="adicion">Precio Adicional</label>
						<input type="number" name="adicion" min="0.00" step="0.01" id="adicion" placeholder="Precio adicional del Producto">

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