<?php

session_start();



if($_SESSION["rol"]!= 1){
	header("location: ./");
}

include "../conexion.php";



?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	
	<?php include "includes/scripts.php" ?>
	<title>Lista de Usuarios</title>
</head>
<body>
	
	<?php include "includes/header.php" ?>

	<section id="container">

    <?php
    
        $busqueda = strtolower($_REQUEST["busqueda"]);

        if(empty($busqueda)){
            header("location: lista_usuario.php");
            mysqli_close($connection);
        }

    ?>
		
        <h1><i class="fa-solid fa-users"></i> Lista de Usuarios</h1>
        <a href="registro_usuario.php" class="btn_new"><i class="fa-solid fa-user-plus"></i> Crear Usuario</a>
        
        <form action="buscar_usuario.php" method="get" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda;?>">

            <button type="submit" class="btn_search"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
        </form>

        <table>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>

            <?php

                //paginador
                $sql_registe = mysqli_query($connection,"select count(*) as total_registro from usuario inner join rol on usuario.rol = rol.idrol where ( idusuario like '%$busqueda%' or nombre like '%$busqueda%' or correo like '%$busqueda%' or usuario like '%$busqueda%' or rol.rol like '%$busqueda%' ) and estatus = 1 and usuario.rol != 1;");
                $result_register = mysqli_fetch_array($sql_registe);

                $total_registro = $result_register['total_registro'];

                $por_pagina = 5;

                if(empty($_GET["pagina"]) ){
                    
                    $pagina = 1;
                }else{
                   
                    $pagina = $_GET["pagina"];
                }

                $desde = ($pagina-1) * $por_pagina;
                $total_paginas = ceil($total_registro / $por_pagina);

                

                if($pagina <= 0){
                    header("location: lista_usuario.php");
                }
            
                $query = mysqli_query($connection,"select u.idusuario,u.nombre,u.correo,u.usuario,u.rol as idrol,r.rol from usuario u inner join rol r on r.idrol = u.rol where ( u.idusuario like '%$busqueda%' or u.nombre like '%$busqueda%' or u.correo like '%$busqueda%' or u.usuario like '%$busqueda%' or r.rol like '%$busqueda%' ) and u.estatus = 1 and u.rol != 1 order by u.idusuario asc LIMIT $desde,$por_pagina");
                mysqli_close($connection);
                $result_can = mysqli_num_rows($query);

							if($result_can > 0){
                                $resultados = "";

                                if($pagina <= 0){
                                    header("location: buscar_usuario.php?busqueda=$busqueda");
                                }
                
                                if($pagina > $total_paginas){
                                    header("location: buscar_usuario.php?pagina=$total_paginas&busqueda=$busqueda");
                                }
                

								while($result = mysqli_fetch_array($query)){
						
            ?>

            <tr>
                <td><?php echo $result["idusuario"]?></td>
                <td><?php echo $result["nombre"]?></td>
                <td><?php echo $result["correo"]?></td>
                <td><?php echo $result["usuario"]?></td>
                <td><?php echo $result["rol"]?></td>
                <td>
                    <a class="link_edit" href="editar_usuario.php?id=<?php echo $result["idusuario"]?>"><i class="fa-solid fa-pen-to-square"></i> Editar</a>
                    

                    <?php
                        if($result["idrol"] != 1){
                    ?>
                    |
                    <a class="link_delete" href="eliminar_confirmar_usuario.php?id=<?php echo $result["idusuario"]?>"><i class="fa-solid fa-trash"></i> Eliminar</a>
               
                            <?php
                        }
                            ?>
                </td>
            </tr>
            <?php
                                }
                            }else{

                                if($pagina <= 0){
                                    header("location: lista_usuario.php");
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