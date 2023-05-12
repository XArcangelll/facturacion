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
		if(empty($_POST["categoria"]) )
		{

			$alert = "<p class='msg_error'> El campo nombre es obligatorio</p>";

		
		}else{
		
			
                $nombre = $_POST["categoria"];

						$query_insert = mysqli_query($connection,"INSERT INTO categoria(nombrecat) values('$nombre') ");
						if($query_insert){
							$alert = "<p class='msg_save'> Categoria guardado correctamente</p>";
							
	
						}else{
							$alert = "<p class='msg_error'> Error al guardar la Categoria</p>";
						}	
					}					
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	
	<?php include "includes/scripts.php" ?>
	<title>Registro Categoria</title>
</head>
<body>
	
	<?php include "includes/header.php" ?>

	<section id="container">
		
			<div class="form_register">
				<h1><i class="fa-solid fa-store"></i> Registro Categoria</h1>
				<hr>

					<div class="alert">
							<?php echo isset($alert) ? $alert : ""; ?>
					</div>

					<form action="" method="post" >

						<label for="categoria">Nombre de la Categoria</label>
						<input type="text" name="categoria" id="categoria" placeholder="Nombre de la Categoria">
						<button type="submit" class="btn-save"><i class="fa-solid fa-floppy-disk"></i> Crear Categoria</button>


					</form>

			</div>


	</section>

	<?php include "includes/footer.php" ?>

</body>
</html>