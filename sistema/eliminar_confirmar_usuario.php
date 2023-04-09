<?php

session_start();


if($_SESSION["rol"]!= 1){
	header("location: ./");
}

   include "../conexion.php";

    if(!empty($_POST)){


        if($_POST["idusuario"] == 13){
            header("location: lista_usuario.php");
            mysqli_close($connection);
            exit;
        }

        $idusuario = $_POST["idusuario"];

         //   $query_delete = mysqli_query($connection,"Delete from usuario where idusuario = $idusuario");

         $query_delete = mysqli_query($connection,"UPDATE usuario SET estatus = 0 where idusuario = $idusuario");
         mysqli_close($connection);

            if($query_delete){
                header("location: lista_usuario.php");
            }else{
                echo "Error al eliminar";
            }


    }

    if(empty($_REQUEST["id"]) || $_REQUEST["id"] == 13){
        header("location: lista_usuario.php");
        mysqli_close($connection);
    }else{
     

        $idusuario = $_REQUEST["id"];

        $query = mysqli_query($connection,"select u.nombre,u.usuario,r.rol from usuario u inner join rol r on u.rol = r.idrol where u.idusuario = $idusuario");
        mysqli_close($connection);
        $result = mysqli_num_rows($query);

        if($result > 0){

            while($data = mysqli_fetch_array($query)){

                $nombre = $data["nombre"];
                $usuario = $data["usuario"];
                $rol = $data["rol"];
            }

        }else{
            header("location: lista_usuario.php");
        }

    }


?>




<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	
	<?php include "includes/scripts.php" ?>
	<title>Eliminar Usuario</title>
</head>
<body>
	
	<?php include "includes/header.php" ?>

	<section id="container">
		
            <div class="data_delete">
                    <h2>¿Está seguro de eliminar el siguiente registro?</h2>
                 
                    <p>Nombre: <span><?php echo $nombre;?></span> </p>
                    <p>Usuario: <span><?php echo $usuario;?></span> </p>
                    <p>Tipo Usuario: <span><?php echo $rol;?></span> </p>
                    <form method="post" action="">
                    <input type="hidden" name="idusuario" value="<?php echo $idusuario ?>" >
                         <input type="submit" value="Eliminar" class="btn_ok" onclick="return confirmacion()" >
                        <a href="lista_usuario.php" class="btn_cancel">Cancelar</a>
                    </form>
                    
            </div>



	</section>

	<?php include "includes/footer.php" ?>

</body>
</html>