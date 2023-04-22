<?php

session_start();

include  "../conexion.php";



//exit;


if(!empty($_POST)){


  

    //Extraer datos del producto

    if($_POST["action"] == "infoProducto")
    {
        $producto_id = $_POST["producto"];

        $query = mysqli_query($connection,"Select codproducto,descripcion from producto where codproducto = $producto_id and estatus = 1");

            mysqli_close($connection);
            $result = mysqli_num_rows($query);

            if($result > 0){
                $data = mysqli_fetch_assoc($query);
                echo json_encode($data,JSON_UNESCAPED_UNICODE);
                exit;
            }
            echo "error";
            exit;

    }
    

    if($_POST["action"] == "addProduct")
    {
       
        if(!empty($_POST["cantidad"]) || !empty($_POST["precio"]) || !empty($_POST["producto_id"]))
        {
            $cantidad = $_POST["cantidad"];
            $precio = $_POST["precio"];
            $producto_id = $_POST["producto_id"];
            $usuario_id = $_SESSION["idUser"];


            $query_insert = mysqli_query($connection,"INSERT INTO entradas(codproducto,cantidad,precio,usuario_id) values($producto_id,$cantidad,$precio,$usuario_id)");


            if($query_insert){
                $query_upd = mysqli_query($connection,"Call actualizar_precio_producto($cantidad,$precio,$producto_id)");
                $result_pro = mysqli_num_rows($query_upd);

                if($result_pro > 0){
                    $data = mysqli_fetch_assoc($query_upd);
                    $data["producto_id"] = $producto_id;
                    echo json_encode($data,JSON_UNESCAPED_UNICODE);
                    exit;
                }
            }else{
                echo "error";
            }
            mysqli_close($connection);
        }
        exit;

    }

    if($_POST["action"] == "delProduct")
    {

        if(empty($_POST["producto_id"]) || !is_numeric($_POST["producto_id"])){
            echo "error";
        }else{

            $idproducto = $_POST["producto_id"];

            //   $query_delete = mysqli_query($connection,"Delete from usuario where idusuario = $idusuario");
    
            $query_delete = mysqli_query($connection,"UPDATE producto SET estatus = 0 where codproducto = $idproducto");
            mysqli_close($connection);
    
               if($query_delete){
                   echo "ok";
               }else{
                   echo "error";
               }
        }

      echo "error";

    }
    
    exit;

}


exit;

?>