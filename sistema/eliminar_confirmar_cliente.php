<?php

session_start();


if($_SESSION["rol"]!= 1){
	header("location: ./");
}

   include "../conexion.php";

    if(!empty($_POST)){

        if(empty($_POST["idcliente"])){
            header("location: lista_cliente.php");
        }
        $idcliente = $_POST["idcliente"];

         //   $query_delete = mysqli_query($connection,"Delete from usuario where idusuario = $idusuario");

         $query_delete = mysqli_query($connection,"UPDATE cliente SET estatus = 0 where idcliente = $idcliente");
         mysqli_close($connection);

            if($query_delete){
                header("location: lista_cliente.php");
            }else{
                echo "Error al eliminar";
            }


    }

    if(empty($_REQUEST["id"]) ){
        header("location: lista_cliente.php");
        mysqli_close($connection);
    }else{
     

        $idcliente = $_REQUEST["id"];

        $query = mysqli_query($connection,"select dni,nombre,telefono,direccion from cliente where idcliente = $idcliente and estatus = 1");
        mysqli_close($connection);
        $result = mysqli_num_rows($query);

        if($result > 0){

            while($data = mysqli_fetch_array($query)){

                $dni = $data["dni"];
                $nombre = $data["nombre"];
                $telefono = $data["telefono"];
                $direccion = $data["direccion"];
            }

        }else{
            header("location: lista_cliente.php");
        }

    }


?>




<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	
	<?php include "includes/scripts.php" ?>
	<title>Eliminar Cliente</title>
</head>
<body>
	
	<?php include "includes/header.php" ?>

	<section id="container">
		
            <div class="data_delete">
            <i class="fa-solid fa-user-xmark fa-7x icono" style="color: #fb0404;"></i>
                    <h2>¿Está seguro de eliminar el siguiente registro?</h2>
                    <p>DNI: <span><?php echo $dni;?></span> </p>
                    <p>Nombre: <span><?php echo $nombre;?></span> </p>
                    <p>Teléfono: <span><?php echo $telefono;?></span> </p>
                    <p>Dirección: <span><?php echo $direccion;?></span> </p>
                    
                    <form method="post" action="">
                    <input type="hidden" name="idcliente" value="<?php echo $idcliente ?>" >
                    <button type="submit" class="btn_ok" onclick="return confirmacion()"><i class="fa-solid fa-trash"></i> Eliminar</button>
                        <a href="lista_cliente.php" class="btn_cancel"><i class="fa-solid fa-xmark"></i> Cancelar</a>
                    </form>
                    
            </div>



	</section>

	<?php include "includes/footer.php" ?>

</body>
</html>