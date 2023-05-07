<?php

session_start();
ob_start();
include "../conexion.php";



?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	
	<?php include "includes/scripts.php" ?>
	<title>Lista de Ventas</title>
</head>
<body>
	
	<?php include "includes/header.php" ?>

	<section id="container">
		
        <h1><i class="fa-solid fa-money-bill"></i> Lista de Ventas</h1>
        <a href="nueva_venta.php" class="btn_new"><i class="fa-solid fa-plus"></i> Nueva Venta</a>

        <form action="buscar_venta.php" method="get" class="form_search">
        <input type="text" name="busqueda" id="busqueda" placeholder="No. Factura">
            <button type="submit" class="btn_search"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
        </form>

        <div>
            <h5>Buscar por Fecha</h5>
            <form action="buscar_venta.php" method="get" class="form_search_date">
                    <label>De: </label>
                    <input type="date" name="fecha_de" id="fecha_de" required>
                    <label>A: </label>
                    <input type="date" name="fecha_a" id="fecha_a" required>
                    <button type="submit" class="btn_view" ><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
        </div>

        <table>
            <tr>
                <th>No.</th>
                <th>Fecha / Hora</th>
                <th>Cliente</th>
                <th>Vendedor</th>
                <th>Estado</th>
                <th class="textright">Total Factura</th>
                <th >Acciones</th>
            </tr>

            <?php

                //paginador
                $sql_registe = mysqli_query($connection,"select count(*) as total_registro from factura where estatus != 10");
                $result_register = mysqli_fetch_array($sql_registe);

                $total_registro = $result_register['total_registro'];

                $por_pagina = 10;

                if(empty($_GET["pagina"]) ){
                    
                    $pagina = 1;
                }else{
                   
                    $pagina = $_GET["pagina"];
                }

                $desde = ($pagina-1) * $por_pagina;
                $total_paginas = ceil($total_registro / $por_pagina);

                if($pagina <= 0){
                    header("location: ventas.php");
                }

                if($pagina > $total_paginas){
                    header("location: ventas.php?pagina=$total_paginas");
                }
            
                $query = mysqli_query($connection,"select f.nofactura,f.fecha,f.totalfactura,f.codcliente,f.estatus,u.nombre as vendedor, cl.nombre as cliente from factura f inner join usuario u on f.usuario = u.idusuario inner join cliente cl on f.codcliente = cl.idcliente where f.estatus !=10 order by f.fecha desc LIMIT $desde,$por_pagina");
                mysqli_close($connection);	
                $result_can = mysqli_num_rows($query);

							if($result_can > 0){
								while($result = mysqli_fetch_array($query)){

                                    if($result["estatus"] == 1){
                                        $estado = '<span class="pagada">Pagada</span>';
                                    }else if($result["estatus"] == 2){
                                        $estado = '<span class="pendiente">Pendiente</span>';
                                    }else{
                                        $estado = '<span class="anulada">Anulada</span>';
                                    }
						
            ?>

            <tr id="row_<?php echo $result["nofactura"]?>">
                <td><?php echo $result["nofactura"]?></td>
                <td><?php echo $result["fecha"]?></td>
                <td><?php echo $result["cliente"]?></td>
                <td><?php echo $result["vendedor"]?></td>
                <td class="estado"><?php echo $estado?></td>
                <td class="textright totalfactura">S/. <?php echo $result["totalfactura"]?></td>
                <td >
                   
                <div class="div_acciones">
                   
                        <a class="btn_view" style="background: #f9a825;" href="facturadetalle.php?cl=<?php echo $result["codcliente"] ?>&f=<?php echo $result["nofactura"]?>" target="_blank"><i class="fas fa-eye"></i></a>
                        <button class="btn_view view_factura" type="button" cl="<?php echo $result["codcliente"]; ?>" f="<?php echo $result["nofactura"];?>" ><i class="fa-solid fa-file-pdf"></i></button>
                    
               

                <?php if($_SESSION["rol"] == 1 || $_SESSION["rol"] == 2){
                    
                        if($result["estatus"] == 1 || $result["estatus"] == 2){
                    ?>

                <div class="div_factura">
                        <button class="btn_anular anular_factura" fac="<?php echo $result["nofactura"]; ?>"><i class="fas fa-ban"></i></button>
                </div>
                <div class="div_facturaa">
                        <button class="btn_actualizar_factura actualizar_factura" fac="<?php echo $result["nofactura"]; ?>"><i class="fa-solid fa-clipboard"></i></button>
                </div>
                
               <?php }else{ ?>
              
                  <div class="div_factura">
                        <button type="button" class="btn_anular inactive"><i class="fas fa-ban"></i></button>
                        
                </div>
                <div class="div_facturaa">
                        <button type="button" class="btn_actualizar_factura inactivee"><i class="fa-solid fa-clipboard"></i></button>    
                </div>
                
                
                  </div>
              <?php } } ?>
              </div> </td>
            </tr>
            <?php
                                }}else{
                                    echo "no hay datos";
                                }
            ?>
            
        </table>

        <div class="paginador">
                                <ul>
                                    <?php
                                    
                                        if($pagina != 1){
                                    ?>
                                    <li><a href="?pagina=<?php echo 1;?>"><i class="fa-solid fa-backward-step"></i></a></li>
                                    <li><a href="?pagina=<?php echo ($pagina == 1) ? 1 : $pagina-1?>"><i class="fa-solid fa-caret-left"></i></a></li>

                                <?php 
                                        }
                                if($total_paginas <= 5  ){
                                    for($i = 1; $i<= $total_paginas;$i++){

                                        if($i == $pagina){
                                            echo '  <li class="pageSelected">'.$i.'</li>';
                                        }else{

                                    echo '  <li><a href="?pagina=' .$i.'">'.$i.'</a></li>';
                                        }
                                    }
                                }else{

                                if($pagina <= $total_paginas -4){
                                for($i = $pagina; $i<= 4+$pagina;$i++){
                                    $pag = 0;
                                    $tot = $total_paginas;
                                  
                                    if($i > $tot){
                                        if($i == $pagina){
                                            echo '  <li class="pageSelected">'.$i.'</li>';
                                        }else{

                                    echo '  <li><a href="?pagina=' .$i.'">'.$i.'</a></li>';
                                        }
                                    
                                    }else if($i <= $tot){

                                        if($i == $pagina){
                                            echo '  <li class="pageSelected">'.$i.'</li>';
                                        }else{

                                    echo '  <li><a href="?pagina=' .$i.'">'.$i.'</a></li>';
                                        }
                                    }    
                                   
                                }
                            }else{

                                for($j = $total_paginas - 4; $j <= $total_paginas;$j++){
                                    if($j == $pagina){
                                        echo '  <li class="pageSelected">'.$j.'</li>';
                                    }else{

                                echo '  <li><a href="?pagina=' .$j.'">'.$j.'</a></li>';
                                    }
                                }
                                   
                                
                            }
                        }

                                if($pagina != $total_paginas) { ?>
                                    <li><a href="?pagina=<?php echo ($pagina >= $total_paginas ) ? $total_paginas : $pagina+1?>"><i class="fa-solid fa-caret-right"></i></a></li>
                                    <li><a href="?pagina=<?php echo $total_paginas?>"><i class="fa-solid fa-forward-step"></i></a></li>
                                    <?php } ?>
                                </ul>
        </div>

	</section>

	<?php include "includes/footer.php" ?>

</body>
</html>