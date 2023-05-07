<?php

//CANTIDAD POR PAGINA ES IMPORTANTE PARA EL FILTRADO

session_start();

include  "../conexion.php";




//exit;


if(!empty($_POST)){


   if($_POST["action"] == "buscarProducto"){
    $nombre = $_POST["nombre"];
        $numero = $_POST["numero"];

        $cantidad_por_pagina = 6;
        $can;
        function buscar($que = null, $paginal = 1){
            global $cantidad_por_pagina,$connection,$can;
            $where = is_null( $que) ? '' : "WHERE p.descripcion like '%$que%' or p.codproducto like '%$que%'";
            $inicio = ($paginal - 1) * $cantidad_por_pagina;
            $consulta = "select p.codproducto,p.descripcion,p.existencia,p.precio, pr.proveedor from producto p inner join proveedor pr on p.proveedor = pr.codproveedor $where order by descripcion  LIMIT $inicio, $cantidad_por_pagina";
            $filas = mysqli_query( $connection, $consulta);
            $can = mysqli_num_rows($filas);

            if($can > 0){
                while($r = mysqli_fetch_assoc($filas)){
                    $registros[] = $r;
                }
            }else{
                $registros = 0;
            }

            $consulta2 = "select count(*) as cantidad from producto p $where";
            $filas2 = mysqli_query($connection,$consulta2);
            $array = mysqli_fetch_assoc($filas2);
            $totpaginas = ceil($array["cantidad"] / $cantidad_por_pagina);
            return ['resultados'=>$registros, 'paginas'=> $totpaginas, "actual" => $paginal];

        };

        $respuesta = buscar($nombre,$numero);
        echo json_encode($respuesta);
   }

  

    //Extraer datos del producto

    if($_POST["action"] == "infoProducto")
    {
        $producto_id = $_POST["producto"];

        $query = mysqli_query($connection,"Select codmedida,codproducto,descripcion, existencia, precio from producto where codproducto = $producto_id and estatus = 1");

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
      exit;
    }

    //buscar cliente
    if($_POST["action"] == "searchcliente"){
       
            if(!empty($_POST["cliente"])){
                $dni = $_POST["cliente"];
                $query = mysqli_query($connection,"select * from cliente where dni like '$dni' and estatus = 1 ");
                mysqli_close($connection);
                $result = mysqli_num_rows($query);

                $data = "";
                if($result>0){
                    $data = mysqli_fetch_assoc($query);
                }else{
                    $data = 0;
                }

                echo json_encode($data,JSON_UNESCAPED_UNICODE);
            }

            exit;


    }
    
    //crear cliente - ventas

    if($_POST["action"] == "addCliente"){
       
       $dni = $_POST["dni_cliente"];
       $nombre = $_POST["nom_cliente"];
       $telefono = $_POST["tel_cliente"];
       $direccion = $_POST["dir_cliente"];
       $usuario_id = $_SESSION["idUser"];

       $query_insert = mysqli_query($connection,"INSERT INTO cliente(dni,nombre,telefono,direccion,usuario_id) values('$dni','$nombre','$telefono','$direccion',$usuario_id) ");

    if($query_insert){
        $codCliente = mysqli_insert_id($connection);
        $msg = $codCliente;
    }else{
        $msg = "error";
    }

    mysqli_close($connection);
    echo $msg;
    exit;

}

if($_POST["action"] == "addProductoDetalle"){
    
    if(empty($_POST["producto"]) || empty($_POST["cantidad"])){

        echo "error";
    }else{
        $codproducto = $_POST["producto"];
        $cantidad = $_POST["cantidad"];
        $token = md5($_SESSION["idUser"]);

        $query_iva = mysqli_query($connection,"SELECT iva from configuracion");
        $result_iva = mysqli_num_rows($query_iva);

        $query_detalle_temp = mysqli_query($connection,"CALL add_detalle_temp($codproducto,$cantidad,'$token')");
        $result = mysqli_num_rows($query_detalle_temp);

        $detalletabla = "";
        $sub_total = 0;
        $iva = 0;
        $total = 0;
        $arrayData = array();

        if($result > 0){
            if($result_iva > 0){
                $info_iva = mysqli_fetch_assoc($query_iva);
                $iva = $info_iva["iva"];
            }

            while($data = mysqli_fetch_assoc($query_detalle_temp)){

                $codmedida = $data["codmedida"];
                if($codmedida == 1){
                    $preciototal = round($data["cantidad"] * $data["precio_venta"],2);
                }
                else{
                    $preciototal = round(($data["cantidad"] * $data["precio_venta"])/1000,2);
                }
                $sub_total = round($sub_total + $preciototal,2);
                $total = round($total + $preciototal,2);

                    $detalletabla .= ' <tr>
                    <td>' .$data["codproducto"].'</td>
                    <td colspan="2">' .$data["descripcion"].'</td>
                    <td class="textcenter">' .$data["cantidad"].'</td>
                    <td class="textright">' .$data["precio_venta"].'</td>
                    <td class="textright">' .$preciototal.'</td>
                    <td class=""><a class="link_delete" href="#" onclick="event.preventDefault();del_producto_detalle('.$data["correlativo"].');"><i class="far fa-trash-alt"></i></a></td>
                </tr>';

            }


            $impuesto = round($sub_total * ($iva/100),2);
            $total_siva = round($sub_total - $impuesto,2);
            $total = round($total_siva + $impuesto,2);

            $detalleTotales = '  <tr>
            <td colspan="5" class="textright">SUBTOTAL S/.</td>
            <td class="textright">'.$total_siva.'</td>
        </tr>
        <tr>
            <td colspan="5" class="textright">IVA ('.$iva.'%)</td>
            <td class="textright">'.$impuesto.'</td>
        </tr>
        <tr>
            <td colspan="5" class="textright">Total S/.</td>
            <td class="textright">'.$total.'</td>
        </tr>';

            $arrayData["detalle"]= $detalletabla;
            $arrayData["totales"]= $detalleTotales;

            echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);


        }else{
            echo "error";
        }

        mysqli_close($connection);



    }

    exit;

}


//extrae datos del detalle temp
if($_POST["action"] == "searchForDetalle"){
    
    if(empty($_POST["user"]) ){

        echo "error";
    }else{
     
        $token = md5($_SESSION["idUser"]);

        $query = mysqli_query($connection,"SELECT p.codmedida,tmp.correlativo, tmp.token_user, tmp.cantidad, tmp.precio_venta, p.codproducto, p.descripcion from detalle_temp tmp inner join producto p on tmp.codproducto = p.codproducto where token_user = '$token' ");
        $result = mysqli_num_rows($query);
        $query_iva = mysqli_query($connection,"SELECT iva from configuracion");
        $result_iva = mysqli_num_rows($query_iva);

        
    

        $detalletabla = "";
        $sub_total = 0;
        $iva = 0;
        $total = 0;
        $arrayData = array();

        if($result > 0){
            if($result_iva > 0){
                $info_iva = mysqli_fetch_assoc($query_iva);
                $iva = $info_iva["iva"];
            }

            while($data = mysqli_fetch_assoc($query)){
                $codmedida = $data["codmedida"];
                if($codmedida == 1){
                    $preciototal = round($data["cantidad"] * $data["precio_venta"],2);
                }
                else{
                    $preciototal = round(($data["cantidad"] * $data["precio_venta"])/1000,2);
                }
                $sub_total = round($sub_total + $preciototal,2);
                $total = round($total + $preciototal,2);

                    $detalletabla .= ' <tr>
                    <td>' .$data["codproducto"].'</td>
                    <td colspan="2">' .$data["descripcion"].'</td>
                    <td class="textcenter">' .$data["cantidad"].'</td>
                    <td class="textright">' .$data["precio_venta"].'</td>
                    <td class="textright">' .$preciototal.'</td>
                    <td class=""><a class="link_delete" href="#" onclick="event.preventDefault();del_producto_detalle('.$data["correlativo"].');"><i class="far fa-trash-alt"></i></a></td>
                </tr>';

            }


            $impuesto = round($sub_total * ($iva/100),2);
            $total_siva = round($sub_total - $impuesto,2);
            $total = round($total_siva + $impuesto,2);

            $detalleTotales = '  <tr>
            <td colspan="5" class="textright">SUBTOTAL S/.</td>
            <td class="textright">'.$total_siva.'</td>
        </tr>
        <tr>
            <td colspan="5" class="textright">IVA ('.$iva.'%)</td>
            <td class="textright">'.$impuesto.'</td>
        </tr>
        <tr>
            <td colspan="5" class="textright">Total S/.</td>
            <td class="textright">'.$total.'</td>
        </tr>';

            $arrayData["detalle"]= $detalletabla;
            $arrayData["totales"]= $detalleTotales;

            echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);


        }else{
            echo "error";
        }

        mysqli_close($connection);



    }

    exit;

}

if($_POST["action"] == "delProductoDetalle"){
    
    if(empty($_POST["id_detalle"]) ){

        echo "error";
    }else{
        $id_detalle = $_POST["id_detalle"];
        $token = md5($_SESSION["idUser"]);

        

        $query_iva = mysqli_query($connection,"SELECT iva from configuracion");
        $result_iva = mysqli_num_rows($query_iva);

    $query_detalle_temp = mysqli_query($connection,"Call del_detalle_temp($id_detalle,'$token')");
    $result = mysqli_num_rows($query_detalle_temp);
    

        $detalletabla = "";
        $sub_total = 0;
        $iva = 0;
        $total = 0;
        $arrayData = array();

        if($result > 0){
            if($result_iva > 0){
                $info_iva = mysqli_fetch_assoc($query_iva);
                $iva = $info_iva["iva"];
            }

            while($data = mysqli_fetch_assoc($query_detalle_temp)){
                $codmedida = $data["codmedida"];
                if($codmedida == 1){
                    $preciototal = round($data["cantidad"] * $data["precio_venta"],2);
                }
                else{
                    $preciototal = round(($data["cantidad"] * $data["precio_venta"])/1000,2);
                }
                $sub_total = round($sub_total + $preciototal,2);
                $total = round($total + $preciototal,2);

                    $detalletabla .= ' <tr>
                    <td>' .$data["codproducto"].'</td>
                    <td colspan="2">' .$data["descripcion"].'</td>
                    <td class="textcenter">' .$data["cantidad"].'</td>
                    <td class="textright">' .$data["precio_venta"].'</td>
                    <td class="textright">' .$preciototal.'</td>
                    <td class=""><a class="link_delete" href="#" onclick="event.preventDefault();del_producto_detalle('.$data["correlativo"].');"><i class="far fa-trash-alt"></i></a></td>
                </tr>';

            }


            $impuesto = round($sub_total * ($iva/100),2);
            $total_siva = round($sub_total - $impuesto,2);
            $total = round($total_siva + $impuesto,2);

            $detalleTotales = '  <tr>
            <td colspan="5" class="textright">SUBTOTAL S/.</td>
            <td class="textright">'.$total_siva.'</td>
        </tr>
        <tr>
            <td colspan="5" class="textright">IVA ('.$iva.'%)</td>
            <td class="textright">'.$impuesto.'</td>
        </tr>
        <tr>
            <td colspan="5" class="textright">Total S/.</td>
            <td class="textright">'.$total.'</td>
        </tr>';

            $arrayData["detalle"]= $detalletabla;
            $arrayData["totales"]= $detalleTotales;

            echo json_encode($arrayData,JSON_UNESCAPED_UNICODE);


        }else{
            echo "error";
        }

        mysqli_close($connection);



    }

    exit;

}

//anular venta
if($_POST["action"] == "anularVenta"){

    $token = md5($_SESSION["idUser"]);
    $query_del = mysqli_query($connection,"Call anular_venta('$token')");
    mysqli_close($connection);
    if($query_del){
        echo "ok";
    }else{
        echo "error";
    }
    exit;
}

//procesar venta
if($_POST["action"] == "procesarVenta"){

    if(empty($_POST["codcliente"])){
        $codcliente = 1;
    }else{
        $codcliente = $_POST["codcliente"];
    }

        if(empty($_POST["estatus"])){
            $estatus = 1;
        }else{
           $estatus = $_POST["estatus"];
            if($estatus == 2){
                $estatus = 2;
            }else{
                $estatus = 1;
            }
        }

    $token = md5($_SESSION["idUser"]);
    $usuario = $_SESSION["idUser"];

    $query = mysqli_query($connection,"select * from detalle_temp where token_user = '$token'");
    $result = mysqli_num_rows($query);

    if($result > 0){
        $query_procesar = mysqli_query($connection,"CALL procesar_venta($usuario,$codcliente,'$token',$estatus)");
        $result_detalle = mysqli_num_rows($query_procesar);

        if($result_detalle > 0){
            $data = mysqli_fetch_assoc($query_procesar);
            echo json_encode($data,JSON_UNESCAPED_UNICODE);
        }else{
            echo "error";
        }

    }else{
        echo "error";
    }
    mysqli_close($connection);
    exit;

}

//info factura

if($_POST["action"] == "infoFactura"){

    if(!empty($_POST["nofactura"])){

        $nofactura = $_POST["nofactura"];

        $query = mysqli_query($connection,"SELECT * FROM  factura where nofactura = '$nofactura' and (estatus = 1 or estatus = 2)");
        mysqli_close($connection);
        $result = mysqli_num_rows($query);
        if($result > 0){
            $data = mysqli_fetch_assoc($query);
            echo json_encode($data,JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
    echo "error";
    exit;
}

//anular factura

if($_POST["action"] == "anularFactura"){
    if(!empty($_POST["noFactura"]))
    {
        $noFactura = $_POST["noFactura"];
        $query_anular = mysqli_query($connection,"Call anular_factura($noFactura)");
        $result = mysqli_num_rows($query_anular);
        if($result > 0){
            $data = mysqli_fetch_assoc($query_anular);
            echo json_encode($data,JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
    echo "error";
    exit;
}

//anular factura

if($_POST["action"] == "actualizarFactura"){
    if(!empty($_POST["noFactura"]))
    {
        $noFactura = $_POST["noFactura"];
        $estado = $_POST["estado"];
        $query_actualizar = mysqli_query($connection,"Update factura set estatus = $estado where nofactura = $noFactura");
        mysqli_close($connection);
        if($query_actualizar){
           echo "ok";
           exit;
        }else{
            echo "error";
            exit;
        }
    
            
    }


}


  

}


exit;

?>