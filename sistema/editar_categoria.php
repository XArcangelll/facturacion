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
		if(empty($_POST["categoria"])  || empty($_POST["idcategoria"])  )
		{

			$alert = "<p class='msg_error'> El campo nombre es Obligatorio </p>";

		}else{
				$idcategoria = $_POST["idcategoria"];
				$nombrecategoria = $_POST["categoria"];
                


						$query_update = mysqli_query($connection,"Update categoria set nombrecat='$nombrecategoria' where idcategoria = $idcategoria");
						if($query_update){	
							$alert = "<p class='msg_save'> Categoria actualizada correctamente</p>";
						}else{
							$alert = "<p class='msg_error'> Error al actualizar la Categoria</p>";
						}							
				
		}

   
	}

	include "../conexion.php";
    //mostrar datos
    if(empty($_GET["id"])){
        header("location: lista_categoria.php");
		mysqli_close($connection);	
    }else{
		$idcategoria =  $_GET["id"];
		if(!is_numeric($idcategoria)){
			header("location: lista_categoria.php");
		}

		$query_categoria = mysqli_query($connection,"SELECT idcategoria,nombrecat from categoria where idcategoria = $idcategoria and estatus = 1");
		$result_categoria = mysqli_num_rows($query_categoria);
		if($result_categoria > 0){
			$data_categoria = mysqli_fetch_assoc($query_categoria);

			$idcat = $data_categoria["idcategoria"];
			$nombrecat = $data_categoria["nombrecat"];
		}else{
			header("location: lista_categoria.php");
		}
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	
	<?php include "includes/scripts.php" ?>
	<title>Actualizar Categoria</title>
</head>
<body>
	
	<?php include "includes/header.php" ?>

	<section id="container">
		
			<div class="form_register">
				<h1><i class="fa-solid fa-store"></i> Actualizar Categoria</h1>
				<hr>

					<div class="alert">
							<?php echo isset($alert) ? $alert : ""; ?>
					</div>

					<form action="" method="post" enctype="multipart/form-data">
						<input type="hidden" name="idcategoria" value="<?php echo $idcat ?>">

						<label for="categoria">Categoria</label>
						<input type="text" name="categoria" value="<?php echo $nombrecat?>" id="categoria" placeholder="Descripción de la Categoria">
						<button type="submit" class="btn-save"><i class="fa-solid fa-floppy-disk"></i> Actualizar Categoria</button>


					</form>

			</div>


	</section>

	<?php include "includes/footer.php" ?>

</body>
</html>