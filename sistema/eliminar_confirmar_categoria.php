<?php

session_start();


if($_SESSION["rol"]!= 1 || $_SESSION["rol"] == 2){
	header("location: ./");
}

   include "../conexion.php";

    if(!empty($_POST)){

        if(empty($_POST["idcategoria"])){
            header("location: lista_categoria.php");
        }
        $idcategoria = $_POST["idcategoria"];

         //   $query_delete = mysqli_query($connection,"Delete from usuario where idusuario = $idusuario");

         $query_delete = mysqli_query($connection,"UPDATE categoria SET estatus = 0 where idcategoria = $idcategoria");
         mysqli_close($connection);

            if($query_delete){
                header("location: lista_categoria.php");
            }else{
                echo "Error al eliminar";
            }


    }

    if(empty($_REQUEST["id"]) ){
        header("location: lista_categoria.php");
        mysqli_close($connection);
    }else{
     

        $idcategoria = $_REQUEST["id"];

        $query = mysqli_query($connection,"select nombrecat from categoria where idcategoria = $idcategoria and estatus = 1");
        mysqli_close($connection);
        $result = mysqli_num_rows($query);

        if($result > 0){

            while($data = mysqli_fetch_array($query)){
                $nombre = $data["nombrecat"];
            }

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
	<title>Eliminar Categoria</title>
</head>
<body>
	
	<?php include "includes/header.php" ?>

	<section id="container">
		
            <div class="data_delete">
            <i class="fa-solid fa-user-xmark fa-7x icono" style="color: #fb0404;"></i>
                    <h2>¿Está seguro de eliminar la siguiente Categoria?</h2>
                    <p>Nombre: <span><?php echo $nombre;?></span> </p>
                    
                    <form method="post" action="">
                    <input type="hidden" name="idcategoria" value="<?php echo $idcategoria ?>" >
                    <button type="submit" class="btn_ok" onclick="return confirmacion()"><i class="fa-solid fa-trash"></i> Eliminar</button>
                        <a href="lista_categoria.php" class="btn_cancel"><i class="fa-solid fa-xmark"></i> Cancelar</a>
                    </form>
                    
            </div>



	</section>

	<?php include "includes/footer.php" ?>

</body>
</html>