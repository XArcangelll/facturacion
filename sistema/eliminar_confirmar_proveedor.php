<?php

session_start();


if($_SESSION["rol"]!= 1){
	header("location: ./");
}

   include "../conexion.php";

    if(!empty($_POST)){

        if(empty($_POST["codproveedor"])){
            header("location: lista_proveedor.php");
        }
        $codproveedor = $_POST["codproveedor"];

         //   $query_delete = mysqli_query($connection,"Delete from usuario where idusuario = $idusuario");

         $query_delete = mysqli_query($connection,"UPDATE proveedor SET estatus = 0 where codproveedor = $codproveedor");
         mysqli_close($connection);

            if($query_delete){
                header("location: lista_proveedor.php");
            }else{
                echo "Error al eliminar";
            }


    }

    if(empty($_REQUEST["id"]) ){
        header("location: lista_proveedor.php");
        mysqli_close($connection);
    }else{
     

        $codproveedor = $_REQUEST["id"];

        $query = mysqli_query($connection,"select proveedor,contacto,telefono,direccion from proveedor where codproveedor = $codproveedor and estatus = 1");
        mysqli_close($connection);
        $result = mysqli_num_rows($query);

        if($result > 0){

            while($data = mysqli_fetch_array($query)){

                $proveedor = $data["proveedor"];
                $contacto = $data["contacto"];
                $telefono = $data["telefono"];
                $direccion = $data["direccion"];
            }

        }else{
            header("location: lista_proveedor.php");
        }

    }


?>




<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	
	<?php include "includes/scripts.php" ?>
	<title>Eliminar Proveedor</title>
</head>
<body>
	
	<?php include "includes/header.php" ?>

	<section id="container">
		
            <div class="data_delete">
            <i class="fa-solid fa-user-xmark fa-7x icono" style="color: #fb0404;"></i>
                    <h2>¿Está seguro de eliminar el siguiente registro?</h2>
                    <p>Proveedor: <span><?php echo $proveedor;?></span> </p>
                    <p>Contacto: <span><?php echo $contacto;?></span> </p>
                    <p>Teléfono: <span><?php echo $telefono;?></span> </p>
                    <p>Dirección: <span><?php echo $direccion;?></span> </p>
                    
                    <form method="post" action="">
                    <input type="hidden" name="codproveedor" value="<?php echo $codproveedor ?>" >
                    <button type="submit" class="btn_ok" onclick="return confirmacion()"><i class="fa-solid fa-trash"></i> Eliminar</button>
                        <a href="lista_proveedor.php" class="btn_cancel"><i class="fa-solid fa-xmark"></i> Cancelar</a>
                    </form>
                    
            </div>



	</section>

	<?php include "includes/footer.php" ?>

</body>
</html>