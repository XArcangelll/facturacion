<?php

session_start();

include "../conexion.php";



?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	
	<?php include "includes/scripts.php" ?>
	<title>Lista de Categorias</title>
</head>
<body>
	
	<?php include "includes/header.php" ?>

	<section id="container">

    <?php
    
        $busqueda = strtolower($_REQUEST["busqueda"]);

        if(empty($busqueda)){
            header("location: lista_categoria.php");
            mysqli_close($connection);
        }

    ?>
		
        <h1><i class="fa-solid fa-store"></i> Lista de Categorias</h1>
        <a href="registro_categoria.php" class="btn_new"><i class="fa-solid fa-plus"></i> Crear Categoria</a>

        <form action="buscar_categoria.php" method="get" class="form_search">
        <input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
            <button type="submit" class="btn_search"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
        </form>

        <table>
            <tr>
            <th>Código</th>
                <th>Nombre de la Categoría</th>
                <th>Acciones</th>
            </tr>

            <?php

                //paginador
                $sql_registe = mysqli_query($connection,"select count(*) as total_registro from categoria where ( idcategoria like '%$busqueda%' or nombrecat like '%$busqueda%') and estatus = 1;");
                $result_register = mysqli_fetch_array($sql_registe);

                $total_registro = $result_register['total_registro'];

                $por_pagina = 5;

                if(empty($_GET["pagina"]) || !is_numeric($_GET["pagina"]) ){
                    
                    $pagina = 1;
                }else{
                   
                    $pagina = $_GET["pagina"];
                }

                $desde = ($pagina-1) * $por_pagina;
                $total_paginas = ceil($total_registro / $por_pagina);

                

                if($pagina <= 0){
                    header("location: lista_categoria.php");
                }
            
                $query = mysqli_query($connection,"select idcategoria,nombrecat from categoria  where ( idcategoria like '%$busqueda%' or nombrecat like '%$busqueda%') and estatus = 1 order by idcategoria asc LIMIT $desde,$por_pagina");
                mysqli_close($connection);
                $result_can = mysqli_num_rows($query);

							if($result_can > 0){
                                $resultados = "";

                                if($pagina <= 0){
                                    header("location: buscar_categoria.php?busqueda=$busqueda");
                                }
                
                                if($pagina > $total_paginas){
                                    header("location: buscar_categoria.php?pagina=$total_paginas&busqueda=$busqueda");
                                }
                

								while($result = mysqli_fetch_array($query)){
						
            ?>

            <tr>
                <td><?php echo $result["idcategoria"]?></td>
                <td><?php echo $result["nombrecat"]?></td>
                <td>
                    <a class="link_edit" href="editar_categoria.php?id=<?php echo $result["idcategoria"]?>"><i class="fa-solid fa-pen-to-square"></i> Editar</a>
                    

                    <?php
                       if($_SESSION["rol"] == 1){ 
                    ?>
                    <a class="link_delete" href="eliminar_confirmar_categoria.php?id=<?php echo $result["idcategoria"]?>"><i class="fa-solid fa-trash"></i> Eliminar</a>
               
                            <?php
                        }
                            ?>
                </td>
            </tr>
            <?php
                                }
                            }else{

                                if($pagina <= 0){
                                    header("location: lista_categoria.php");
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

                                if($total_paginas > 1){

                                for($i = 1; $i<= $total_paginas;$i++){

                                        if($i == $pagina){
                                            echo '  <li class="pageSelected">'.$i.'</li>';
                                        }else{

                                    echo '  <li><a href="?pagina='.$i.'&busqueda='.$busqueda.'">'.$i.'</a></li>';
                                        }
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