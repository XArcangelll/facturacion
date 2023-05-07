<?php

//CANTIDAD POR PAGINA ES IMPORTANTE PARA EL FILTRADO



?>

	
	
	<section id="containerr">
		
        <h1><i class="fa-solid fa-store"></i> Lista de Productos</h1>
       

        <form action="" method="get" id="buscar_producto" class="form_searchh">
        <input type="search" name="busqueda" id="busqueda" placeholder="Buscar">
            <button type="submit" class="btn_searchh"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>    
        </form>

        <div id="cerrar_producto">
        <button id="boton_cerrar" class="btn_searchh"> Cerrar</button>
        </div>
      

        <table>
            <tr>
                <th>Código</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Existencia</th>
                <th>Proveedor</th>
              
                <th>Acciones</th>
            </tr>
            <tbody id="resultados">

            <?php

            $resultados = "";

                
                $cantidad_por_pagina = 6;
                $can;
                function buscar($que = null, $paginal = 1){
                    global $cantidad_por_pagina,$connection,$can;
        
                    $where = is_null( $que) ? '' : "WHERE p.descripcion  like '%$que%' or p.codproducto like '%$que%'";
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

                $productos = buscar();    

                if($productos["resultados"] != 0){

						foreach($productos["resultados"]  as $p){
						
            ?>
            

            <tr class="row<?php echo $p["codproducto"]?>">
                <td><?php echo $p["codproducto"]?></td>
                <td><?php echo $p["descripcion"]?></td>
                <td class="celPrecio"><?php echo $p["precio"]?></td>
                <td class="celExistencia"><?php echo $p["existencia"]?></td>
                <td><?php echo $p["proveedor"]?></td>               
               
                <td>
             
                <a class="link_add  agregar" product="<?php echo $p["codproducto"]?>"  href="#"><i class="fa-solid fa-plus"></i> Seleccionar</a>
                                
               <?php 
               
                   }?> </tbody> <?php }else{
                    $resultados = "no hay resultados";
                    ?>
                
                    <tr><td><?php echo $resultados?></td></tr>
                   <?php } ?>
               
                </td>
            </tr>
          
                   </tbody>
            
        </table>

        <div class="paginadorr">
        <ul>
                                    <?php
                                    
                                        if($productos["actual"] != 1){
                                    ?>
                                    <li><a data-pagina="1" href="#"><i data-pagina="1" class="fa-solid fa-backward-step"></i></a></li>
                                    <li><a data-pagina="<?php echo $productos["actual"] - 1?>" href="#"><i data-pagina="<?php echo $productos["actual"] - 1?>" class="fa-solid fa-caret-left"></i></a></li>

                                <?php 
                                        }
                                
                                if($productos["paginas"] > 1){

                                if($productos["paginas"] <= 5  ){
                                    for($i = 1; $i<= $productos["paginas"];$i++){
                                        $actual = $productos["actual"] == $i ? " class='pageSelectedDef'" : "";
  
                                    echo ' <li><a data-pagina="'.$i.'" href=""  '.$actual.'>'.$i.'</a></li>';
                                        
                                    }
                                }else{

                                if($productos["actual"] <= $productos["paginas"] -4){
                                for($i = $productos["actual"]; $i<= 4+$productos["actual"];$i++){
                                    $pag = 0;
                                    $tot = $productos["paginas"];
                                    $actual = $productos["actual"] == $i ? " class='pageSelectedDef'" : "";
                                  
                                    if($i > $tot){
                                        if($i == $productos["actual"]){
                                            echo ' <li><a '.$actual.' data-pagina="'.$i.'">'.$i.' </a></li>';
                                        }else{
                                            echo ' <li><a '.$actual.' data-pagina="'.$i.'">'.$i.' </a></li>';
                                        }
                                    
                                    }else if($i <= $tot){

                                        if($i == $productos["actual"]){
                                            echo ' <li><a '.$actual.' data-pagina="'.$i.'">'.$i.' </a></li>';
                                        }else{

                                            echo ' <li><a '.$actual.' data-pagina="'.$i.'">'.$i.' </a></li>';
                                        }
                                    }    
                                   
                                }
                            }else{

                                for($j = $productos["paginas"] - 4; $j <= $productos["paginas"];$j++){
                                    $actual = $productos["actual"] == $j ? " class='pageSelectedDef'" : "";
                                    if($j == $productos["actual"]){
                                        echo ' <li><a '.$actual.' data-pagina="'.$j.'">'.$j.' </a></li>';
                                    }else{

                                        echo ' <li><a '.$actual.' data-pagina="'.$j.'">'.$j.' </a></li>';
                                    }
                                }
                                   
                                
                            }
                        }

                                }

                                if($productos["actual"] != $productos["paginas"]) { ?>
                                <li><a data-pagina="<?php echo $productos["actual"] + 1?>" href=""><i data-pagina="<?php echo $productos["actual"] + 1?>" class="fa-solid fa-caret-right"></i></a></li>
                                <li><a data-pagina="<?php echo $productos["paginas"] ?>" href="" ><i data-pagina="<?php echo $productos["paginas"]?>" class="fa-solid fa-forward-step"></i></a></li>
                                    <?php } ?>
                                </ul>
        </div>

	</section>

    <script src="js/function-buscar.js">

    </script>
