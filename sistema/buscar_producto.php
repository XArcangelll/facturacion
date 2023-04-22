<?php

session_start();
ob_start();


if($_SESSION["rol"] != 1 && $_SESSION["rol"]!= 2){
	header("location: ./");
}



include "../conexion.php";



?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	
	<?php include "includes/scripts.php" ?>
	<title>Lista de Productos</title>
</head>
<body>
	
	<?php include "includes/header.php" ?>

<section id="container">
    <?php

  $busqueda = "";
  $search_proveedor = "";
  $resultados = "";
  $opcion = "";

    if(empty($_REQUEST["busqueda"]) && empty($_REQUEST["proveedor"])){
        header("location: lista_producto.php");
        mysqli_close($connection);
    }

    if(!empty($_REQUEST["busqueda"])){
        $busqueda = strtolower($_REQUEST["busqueda"]);
        $where = "where ( p.codproducto like '%$busqueda%' or p.descripcion like '%$busqueda%' ) and p.estatus = 1";
        $opcion = "busqueda";
    }

    if(!empty($_REQUEST["proveedor"])){
        $search_proveedor = strtolower($_REQUEST["proveedor"]);
        $where = "where p.proveedor = $search_proveedor and p.estatus = 1";
        $opcion = "proveedor";
    }



?>
		
        <h1><i class="fa-solid fa-store"></i> Lista de Productos</h1>
        <a href="registro_producto.php" class="btn_new"><i class="fa-solid fa-plus"></i> Crear Producto</a>

        <form action="buscar_producto.php" method="get" class="form_search">
        <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda;?>">
            <button type="submit" class="btn_search"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
        </form>

        <table>
            <tr>
                <th>Código</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Existencia</th>
                <th>
                <?php

                $pro = 0;
                if(!empty($_REQUEST["proveedor"])){
                    $pro = $_REQUEST["proveedor"];
                }					
                            $query_proveedor = mysqli_query($connection,"Select codproveedor,proveedor from proveedor where estatus = 1 order by proveedor asc");
                                $result_proveedor = mysqli_num_rows($query_proveedor);                         
                        ?>
                    <select name="proveedor" id="search_proveedor"> 
                        <option value="" selected>Proveedor</option>
                    <?php   
                        if($result_proveedor > 0){
                            while($proveedor = mysqli_fetch_array($query_proveedor)){
                                if($pro == $proveedor["codproveedor"]){
                                ?>
                                        <option value="<?php echo $proveedor["codproveedor"]?>" selected><?php echo $proveedor["proveedor"]?></option>
                                <?php

                                }else{
                                ?>
                            <option value="<?php echo $proveedor["codproveedor"]?>" ><?php echo $proveedor["proveedor"]?></option>
                                <?php
                                }
                            }
                        }

                    ?>
                    </select>
                </th>
                <th>Foto</th>
                <th>Acciones</th>
            </tr>

            <?php

                //paginador
                $sql_registe = mysqli_query($connection,"select count(*) as total_registro from producto p $where ");
                $result_register = mysqli_fetch_array($sql_registe);

                $total_registro = $result_register['total_registro'];

               //echo $total_registro;exit;

                $por_pagina = 5;

                if(empty($_GET["pagina"]) ){
                    
                    $pagina = 1;
                }else{
                   
                    $pagina = $_GET["pagina"];
                }

                $desde = ($pagina-1) * $por_pagina;
                $total_paginas = ceil($total_registro / $por_pagina);

                if($desde<0){
                    header("location: lista_producto.php");
                }

            /*    if($pagina <= 0){
                    header("location: lista_producto.php");
                }

                if($pagina > $total_paginas){
                    header("location: lista_producto.php?pagina=$total_paginas");
                }*/
            
                $query = mysqli_query($connection,"select p.codproducto,p.descripcion,p.precio,p.existencia,pr.proveedor,p.foto from producto p inner join proveedor pr on p.proveedor = pr.codproveedor $where order by p.codproducto desc LIMIT $desde,$por_pagina");        

                
                mysqli_close($connection);	
                $result_can = mysqli_num_rows($query);

							if($result_can > 0){

                                if($pagina <= 0){
                                    header("location: buscar_producto.php?proveedor=$search_proveedor");
                                }
                
                                if($pagina > $total_paginas){
                                    header("location: buscar_producto.php?&proveedor=$search_proveedor&pagina=$total_paginas");
                                }
                

								while($result = mysqli_fetch_array($query)){
						
            ?>

            <tr class="row<?php echo $result["codproducto"]?>">
                <td><?php echo $result["codproducto"]?></td>
                <td><?php echo $result["descripcion"]?></td>
                <td class="celPrecio"><?php echo $result["precio"]?></td>
                <td class="celExistencia"><?php echo $result["existencia"]?></td>
                <td><?php echo $result["proveedor"]?></td>
    
                <td><img class="img_producto" src="<?php echo "img/uploads/".$result["foto"]?>" alt="<?php echo $result["foto"]?>"></td>
       
               
                <td>
                <?php
                    
                    if($_SESSION["rol"] == 1 || $_SESSION["rol"] == 2){               
                    

                    ?>
                <a class="link_add add_product" product="<?php echo $result["codproducto"]?>"  href="#"><i class="fa-solid fa-plus"></i> Agregar</a>
                                
                    <a class="link_edit" href="editar_producto.php?id=<?php echo $result["codproducto"]?>"><i class="fa-solid fa-pen-to-square"></i> Editar</a>

                    
                    <a class="link_delete del_product" product="<?php echo $result["codproducto"]?>" href="#"><i class="fa-solid fa-trash"></i> Eliminar</a>
               
               <?php } ?>
               
                </td>
            </tr>
            <?php
                                }}else{

                                    if($pagina <= 0){
                                        header("location: lista_producto.php");
                                    }

                                    $resultados = "no hay resultados";
                                    ?>
                                
                                    <tr><td><?php echo $resultados?></td></tr>
    
                                    <?php
                                        
                                    
                                }
            ?>
            
        </table>

        <div class="paginador">
                                <ul>
                                    <?php
                                    
                                        if($pagina != 1){
                                    ?>
                                    <li><a href="?pagina=<?php echo 1;?>&<?php echo $opcion ?>=<?php echo ($opcion == "proveedor") ? $search_proveedor : $busqueda?>"><i class="fa-solid fa-backward-step"></i></a></li>
                                    <li><a href="?pagina=<?php echo ($pagina == 1) ? 1 : $pagina-1?>&<?php echo $opcion ?>=<?php echo ($opcion == "proveedor") ? $search_proveedor : $busqueda?> "><i class="fa-solid fa-caret-left"></i></a></li>

                                <?php 
                                        }
                                for($i = 1; $i<= $total_paginas;$i++){

                                        if($i == $pagina){
                                            echo '  <li class="pageSelected">'.$i.'</li>';
                                        }else{
                                                if($opcion == "proveedor"){

                                                }
                                    echo '<li><a href="?pagina='.$i.'&'.$opcion.'='.(($opcion=="proveedor") ? $search_proveedor : $busqueda).'">'.$i.'</a></li>';
                                        }
                                }

                                if($pagina != $total_paginas && $resultados != "no hay resultados") { ?>
                                    <li><a href="?pagina=<?php echo ($pagina >= $total_paginas ) ? $total_paginas : $pagina+1?>&<?php echo $opcion ?>=<?php echo ($opcion == "proveedor") ? $search_proveedor : $busqueda?>"><i class="fa-solid fa-caret-right"></i></a></li>
                                    <li><a href="?pagina=<?php echo $total_paginas?>&<?php echo $opcion ?>=<?php echo ($opcion == "proveedor") ? $search_proveedor : $busqueda?>"><i class="fa-solid fa-forward-step"></i></a></li>
                                    <?php } ?>
                                </ul>
        </div>

	</section>

	<?php include "includes/footer.php" ?>

</body>
</html>