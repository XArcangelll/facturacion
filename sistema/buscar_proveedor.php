<?php

session_start();



if($_SESSION["rol"]!= 1 && $_SESSION["rol"]!= 2){
	header("location: ./");
}


include "../conexion.php";



?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	
	<?php include "includes/scripts.php" ?>
	<title>Lista de Proveedores</title>
</head>
<body>
	
	<?php include "includes/header.php" ?>

	<section id="container">

    <?php
    
        $busqueda = strtolower($_REQUEST["busqueda"]);

        if(empty($busqueda)){
            header("location: lista_proveedor.php");
            mysqli_close($connection);
        }

    ?>
		
        <h1><i class="fa-solid fa-boxes-packing"></i> Lista de Proveedores</h1>
        <a href="registro_proveedor.php" class="btn_new"><i class="fa-solid fa-user-plus"></i> Crear Proveedor</a>

        <form action="buscar_proveedor.php" method="get" class="form_search">
        <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda?>">
            <button type="submit" class="btn_search"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
        </form>

        <table>
            <tr>
                <th>ID</th>
                <th>Proveedor</th>
                <th>Contacto</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>

            <?php

                //paginador
                $sql_registe = mysqli_query($connection,"select count(*) as total_registro from proveedor where ( codproveedor like '%$busqueda%' or proveedor like '%$busqueda%' or contacto like '%$busqueda%' or telefono like '%$busqueda%' or direccion like '%$busqueda%' or date_format(date_add, '%d-%m-%Y') like '%$busqueda%' ) and estatus = 1;");
                $result_register = mysqli_fetch_array($sql_registe);

                $total_registro = $result_register['total_registro'];

                $por_pagina = 5;

                if(empty($_GET["pagina"])  || !is_numeric($_GET["pagina"])){
                    
                    $pagina = 1;
                }else{
                   
                    $pagina = $_GET["pagina"];
                }

                $desde = ($pagina-1) * $por_pagina;
                $total_paginas = ceil($total_registro / $por_pagina);

                

                if($pagina <= 0){
                    header("location: lista_proveedor.php");
                }
            
                $query = mysqli_query($connection,"select codproveedor,proveedor,contacto,telefono,direccion,date_add from proveedor  where ( codproveedor like '%$busqueda%' or proveedor like '%$busqueda%' or contacto like '%$busqueda%' or telefono like '%$busqueda%' or direccion like '%$busqueda%' or date_format(date_add, '%d-%m-%Y') like '%$busqueda%' ) and estatus = 1 order by codproveedor asc LIMIT $desde,$por_pagina");
                mysqli_close($connection);
                $result_can = mysqli_num_rows($query);

							if($result_can > 0){
                                $resultados = "";

                                if($pagina <= 0){
                                    header("location: buscar_proveedor.php?busqueda=$busqueda");
                                }
                
                                if($pagina > $total_paginas){
                                    header("location: buscar_proveedor.php?pagina=$total_paginas&busqueda=$busqueda");
                                }
                

								while($result = mysqli_fetch_array($query)){
                                    $formato = "Y-m-d H:i:s";
                                    $fecha = DateTime::createFromFormat($formato,$result["date_add"]);
						
            ?>

            <tr>
                <td><?php echo $result["codproveedor"]?></td>
                <td><?php echo $result["proveedor"]?></td>
                <td><?php echo $result["contacto"]?></td>
                <td><?php echo $result["telefono"]?></td>
                <td><?php echo $result["direccion"]?></td>
                <td><?php echo $fecha->format("d-m-Y")?></td>
                <td>
                    <a class="link_edit" href="editar_proveedor.php?id=<?php echo $result["codproveedor"]?>"><i class="fa-solid fa-pen-to-square"></i> Editar</a>
                    

                    <?php
                       if($_SESSION["rol"] == 1){ 
                    ?>
                    <a class="link_delete" href="eliminar_confirmar_proveedor.php?id=<?php echo $result["codproveedor"]?>"><i class="fa-solid fa-trash"></i> Eliminar</a>
               
                            <?php
                        }
                            ?>
                </td>
            </tr>
            <?php
                                }
                            }else{

                                if($pagina <= 0){
                                    header("location: lista_proveedor.php");
                                }
                
                                $resultados = "no hay resultados";
                              
                                ?>
                                
                                <tr><td><?php echo $resultados?></td></tr>

                                <?php
                                    
                                }
            ?>
            
        </table>

        <?php
        
                                if($total_registro != 0){
        ?>
        <div class="paginador">
                                <ul>
                                    <?php
                                    
                                        if($pagina != 1){
                                    ?>
                                    <li><a href="?pagina=<?php echo 1;?>&busqueda=<?php echo $busqueda?>"><i class="fa-solid fa-backward-step"></i></a></li>
                                    <li><a href="?pagina=<?php echo ($pagina == 1) ? 1 : $pagina-1?>&busqueda=<?php echo $busqueda?> "><i class="fa-solid fa-caret-left"></i></a></li>

                                <?php 
                                        }
                                for($i = 1; $i<= $total_paginas;$i++){

                                        if($i == $pagina){
                                            echo '  <li class="pageSelected">'.$i.'</li>';
                                        }else{

                                    echo '  <li><a href="?pagina='.$i.'&busqueda='.$busqueda.'">'.$i.'</a></li>';
                                        }
                                }

                                if($pagina != $total_paginas) { ?>
                                    <li><a href="?pagina=<?php echo ($pagina >= $total_paginas ) ? $total_paginas : $pagina+1?>&busqueda=<?php echo $busqueda?>"><i class="fa-solid fa-caret-right"></i></a></li>
                                    <li><a href="?pagina=<?php echo $total_paginas?>&busqueda=<?php echo $busqueda?>"><i class="fa-solid fa-forward-step"></i></a></li>
                                    <?php } ?>
                                </ul>
        </div>

        <?php } ?>

	</section>

	<?php include "includes/footer.php" ?>

</body>
</html>