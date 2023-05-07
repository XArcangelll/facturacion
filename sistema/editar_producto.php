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
		if(empty($_POST["proveedor"]) || empty($_POST["descripcion"]) || empty($_POST["precio"]) || empty($_POST["medida"])
		 || empty($_POST["id"])  || empty($_POST["foto_actual"])  || empty($_POST["foto_remove"]))
		{

			$alert = "<p class='msg_error'> Los campos Proveedor,Descripción y Precio son Obligatorios </p>";

		}else{
		
				$codproducto = $_POST["id"];
                $proveedor = $_POST["proveedor"];
				$descripcion = $_POST["descripcion"];
				$precio = $_POST["precio"];
				$medida = $_POST["medida"];

				if($medida != 1 && $medida != 2){
					$medida = 1;
				}
				$imgProducto = $_POST["foto_actual"];
				$imgRemove = $_POST["foto_remove"];
               

				$foto = $_FILES["foto"];
				$nombre_foto = $foto["name"];
				$type = $foto["type"];
				$url_temp = $foto["tmp_name"];

				$upd = "";

				if($nombre_foto != ""){
					$destino = "img/uploads/";
					$img_nombre = "img_" . md5(date("d-m-Y H:m:s"));
					$imgProducto = $img_nombre.".jpg";
					$src = $destino.$imgProducto;
				}else{
					if($_POST["foto_actual"] != $_POST["foto_remove"]){
						$imgProducto = "img_producto.png";
					}
				}


						$query_update = mysqli_query($connection,"Update producto set descripcion='$descripcion', proveedor = $proveedor, precio = $precio, codmedida = $medida, foto = '$imgProducto' where codproducto = $codproducto");
						if($query_update){

							if(($nombre_foto != "" && ($_POST["foto_actual"] != "img_producto.png" )) || ($_POST["foto_actual"] != $_POST["foto_remove"])){

								unlink("img/uploads/".$_POST["foto_actual"]);
							}

									if($nombre_foto != ""){
										move_uploaded_file($url_temp,$src);
									}

								
							$alert = "<p class='msg_save'> Producto actualizado correctamente</p>";
							
	
						}else{
							$alert = "<p class='msg_error'> Error al actualizar el Producto</p>";
						}							
				
		}

   
	}

	include "../conexion.php";
    //mostrar datos
    if(empty($_GET["id"])){
        header("location: lista_producto.php");
		mysqli_close($connection);	
    }else{
		$idproducto =  $_GET["id"];
		if(!is_numeric($idproducto)){
			header("location: lista_producto.php");
		}

		$query_producto = mysqli_query($connection,"SELECT p.codproducto,p.descripcion,p.precio,m.codmedida,m.nombre,p.foto,pr.codproveedor,pr.proveedor from producto p inner join proveedor pr on p.proveedor = pr.codproveedor inner join medida m on p.codmedida = m.codmedida where p.codproducto = $idproducto and p.estatus = 1");
		$result_producto = mysqli_num_rows($query_producto);

		$foto = "";
		$classRemove = "notBlock";

		if($result_producto > 0){
			$data_producto = mysqli_fetch_assoc($query_producto);
			//print_r($data_producto);

			if($data_producto["foto"] != "img_producto.png"){
				$classRemove = "";
				$foto = '<img id="img" src="img/uploads/'.$data_producto["foto"].'" alt="" >';
			}

			$codproducto = $data_producto["codproducto"];
			$descripcion = $data_producto["descripcion"];
			$precio = $data_producto["precio"];
			$codmedida = $data_producto["codmedida"];
			$nommedida = $data_producto["nombre"];
			$fotito = $data_producto["foto"];
			$id_proveedor = $data_producto["codproveedor"];
			$proveedor = $data_producto["proveedor"];
		}else{
			header("location: lista_producto.php");
		}
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	
	<?php include "includes/scripts.php" ?>
	<title>Actualizar Producto</title>
</head>
<body>
	
	<?php include "includes/header.php" ?>

	<section id="container">
		
			<div class="form_register">
				<h1><i class="fa-solid fa-store"></i> Actualizar Producto</h1>
				<hr>

					<div class="alert">
							<?php echo isset($alert) ? $alert : ""; ?>
					</div>

					<form action="" method="post" enctype="multipart/form-data">
						<input type="hidden" name="id" value="<?php echo $codproducto ?>">
						<input type="hidden" name="foto_actual" id="foto_actual" value="<?php echo $fotito ?>">
						<input type="hidden" name="foto_remove" id="foto_remove" value="<?php echo $fotito ?>">


                        <label for="proveedor">Proveedor</label>
							<?php
								$query_proveedor = mysqli_query($connection,"Select codproveedor,proveedor from proveedor where estatus = 1 order by proveedor asc");
									$result_proveedor = mysqli_num_rows($query_proveedor);
								
							?>

						<select name="proveedor" id="proveedor">
							
						<?php

							if($result_proveedor > 0){
								while($pro = mysqli_fetch_array($query_proveedor)){
									
							?>
								<option <?php echo ($pro["codproveedor"] == $id_proveedor) ? "selected" : "" ; ?> value="<?php echo $pro["codproveedor"] ?>"><?php echo $pro["proveedor"] ?></option>
							<?php
									
								}
							}

							?>	
						</select>
						
						<label for="producto">Producto</label>
						<input type="text" name="descripcion" value="<?php echo $descripcion?>" id="descripcion" placeholder="Descripción del Producto">

						<label for="precio">Precio</label>
						<input type="number" name="precio" value="<?php echo $precio?>" step="0.01" id="precio" placeholder="Precio del Producto">
					
						<label for="medida">Medida</label>

						<?php

							$query_medida = mysqli_query($connection,"Select codmedida,nombre from medida");
							mysqli_close($connection);
								$result_medida = mysqli_num_rows($query_medida);
							
						?>

						<select name="medida" id="medida">

						<?php   

						if($result_medida > 0){
							while($medida = mysqli_fetch_array($query_medida)){
								?>
										<option <?php echo ($medida["codmedida"] == $codmedida) ? "selected" : "" ; ?> value="<?php echo $medida["codmedida"] ?>"><?php echo $medida["nombre"] ?></option>
								<?php
							}
						}

						?>
						</select>

						<div class="photo">
							<label for="foto">Foto</label>
								<div class="prevPhoto">
								
										<span class="delPhoto <?php echo $classRemove?>">X</span>
										<label for="foto"></label>
										<?php echo $foto?>
								</div>
								<div class="upimg">
								<input type="file" name="foto" id="foto">
								</div>
								<div id="form_alert"></div>
						</div>
						
						<button type="submit" class="btn-save"><i class="fa-solid fa-floppy-disk"></i> Actualizar Producto</button>


					</form>

			</div>


	</section>

	<?php include "includes/footer.php" ?>

</body>
</html>