
var nombre = null;

$(document).ready(function(){


    $("#modalProducto").click(function(e){
        e.preventDefault();
      
    $(".modall").fadeIn();
    });

    scannear_botones();
    boton_producto_buscar();


    
    boton_cerrar();
    boton_agregar();


});




function scannear_botones(){
    const botones = document.querySelectorAll(".paginadorr a");
    for(let i=0; i<botones.length;i++){
    botones[i].addEventListener("click",function(e){
        e.preventDefault();
       
        const numero = e.target.dataset.pagina;
       // const anterior = document.querySelector(".pageSelectedDef");
       // if(anterior) anterior.classList.remove("pageSelectedDef");
       // e.target.classList.add("pageSelectedDef");
       buscar(nombre,numero);
        
      
        
        
    });
}
}

function boton_agregar(){
    const agregar = document.querySelectorAll(".agregar");
    agregar.forEach(e => { 
        e.addEventListener("click",function(ev){
            ev.preventDefault();
            var producto = $(this).attr("product");
            $("#txt_cod_producto").val(producto);

            var producto =  $("#txt_cod_producto").val();
            var action = "infoProducto";
         
            if(producto != ""){
                $.ajax({
                    url: "ajax.php",
                    type: "POST",
                    async: true,
                    data: {action:action,producto:producto},
                    success: function(response){
                       
                        if(response != "error"){
                            var info = JSON.parse(response);
    
                           
                            $("#txt_descripcion").html(info.descripcion);
                            $("#txt_existencia").html(info.existencia);
                            $("#txt_cant_producto").val("1");
                            $("#txt_precio").html(info.precio);
                            $("#txt_precio_total").html(info.precio);
                            $("#txt_codmedida").val(info.codmedida);
                            if(info.existencia == 0){
                                $("#txt_cant_producto").val("0");
                                $("#add_product_venta").slideUp();
                                $("#txt_cant_producto").attr("disabled","disabled");
                            }else{
                                 //activar cantidad
                            $("#txt_cant_producto").removeAttr("disabled");
                                
                            //mostrar boton agregar
                            $("#add_product_venta").slideDown();
                            }
    
                           
                        }else{
                            $("#txt_descripcion").html("-");
                            $("#txt_existencia").html("-");
                            $("#txt_cant_producto").val("0");
                            $("#txt_precio").html("0.00");
                            $("#txt_precio_total").html("0.00");
    
                             //bloquear cantidad
                             $("#txt_cant_producto").attr("disabled","disabled");
    
                             //mostrar boton agregar
                             $("#add_product_venta").slideUp();
                        }
        
        
                    },
                    error: function(error){
                        console.log(error);
                    }
                });
            }
    
            nombre = "";
            numero = 1;
           // const anterior = document.querySelector(".pageSelectedDef");
           // if(anterior) anterior.classList.remove("pageSelectedDef");
           // const existe = document.querySelector(".pageSelectedDef");
           // if(existe) document.querySelector(".paginador li:first-child a").classList.add("pageSelectedDef");
            buscar(nombre,numero);
            $(".modall").fadeOut();
            $("#busqueda").val("");
            ev.preventDefault();

        });
    });

}

function boton_cerrar(){
    const boton = document.querySelector("#boton_cerrar");
    boton.addEventListener("click", function(e){
        nombre = "";
        numero = 1;
       // const anterior = document.querySelector(".pageSelectedDef");
       // if(anterior) anterior.classList.remove("pageSelectedDef");
       // const existe = document.querySelector(".pageSelectedDef");
       // if(existe) document.querySelector(".paginador li:first-child a").classList.add("pageSelectedDef");
        buscar(nombre,numero);
        $(".modall").fadeOut();
        $("#busqueda").val("");
        e.preventDefault();
    });


}


function boton_producto_buscar(){
    const form = document.querySelector("#buscar_producto");
    form.addEventListener("submit", function(e){
        nombre = form.querySelector("input[type=search]").value;
        numero = 1;
       // const anterior = document.querySelector(".pageSelectedDef");
       // if(anterior) anterior.classList.remove("pageSelectedDef");
       // const existe = document.querySelector(".pageSelectedDef");
       // if(existe) document.querySelector(".paginador li:first-child a").classList.add("pageSelectedDef");
        buscar(nombre,numero);
        e.preventDefault();
    });


}

function buscar(nombre,numero){
    var action = "buscarProducto";

        $.ajax({
            url: "ajax.php",
            type: "POST",
            async: true,
            data: {action:action,nombre:nombre,numero:numero},
            success: function(response){
                var info = JSON.parse(response);   
                //esto resetea el listado de resultados

                if(info.resultados != 0){
                    
                    $("#resultados").html("");

                  
                   

                    info.resultados.forEach(e => {
                       
                       document.getElementById("resultados").innerHTML += `
                        <tr class="row`+e.codproducto+`">
                        <td>`+e.codproducto+`</td>
                <td>`+e.descripcion+`</td>
                <td class="celPrecio">`+e.precio+`</td>
                <td class="celExistencia">`+e.existencia+`</td>
                <td>`+e.proveedor+`</td>               
               
                <td>
             
                <a class="link_add  agregar" product="`+e.codproducto+`"  href="#"><i class="fa-solid fa-plus"></i> Seleccionar</a>
                        </td>
                        <tr>      `;
                    });

                     //resetear la botonera del paginador
                     document.querySelector(".paginadorr ul").innerHTML = "";
                 

                     if(info.actual != 1){
                        document.querySelector(".paginadorr ul").innerHTML += `
                        <li><a data-pagina="1" href="" ><i data-pagina="1" class="fa-solid fa-backward-step"></i></a></li>  
                          
                        <li><a data-pagina="${parseInt(info.actual) - 1}" href=""><i data-pagina="${parseInt(info.actual) - 1}" class="fa-solid fa-caret-left"></i></a></li>
                        `;
                     }
                     if(info.paginas > 1){

                     if(info.paginas <= 5  ){

                     for(let i = 1; i<= parseInt(info.paginas);i++){
                        let actual = info.actual == i ? " class='pageSelectedDef'" : "";
                         document.querySelector(".paginadorr ul").innerHTML += `
                             <li><a data-pagina='`+i+`' href='#' ${actual} >`+i+`</a></li>    
                         `;
                     }

                    }else{
                        if(info.actual <= info.paginas -4){
                           
                            for(let i = info.actual; i<= 4 + parseInt(info.actual);i++){
                                let pag = 0;
                                let tot = info.paginas;
                                let actual = info.actual == i ? " class='pageSelectedDef'" : "";
                                if(i > tot){
                                    if(i == info.actual){
                                        document.querySelector(".paginadorr ul").innerHTML += ` <li><a ${actual} data-pagina="${i}">${i} </a></li>`;
                                    }else{
                                        document.querySelector(".paginadorr ul").innerHTML += ` <li><a ${actual} data-pagina="${i}">${i} </a></li>`;

                                    }
                                
                                }else if(i <= tot){

                                    if(i == info.actual){
                                        document.querySelector(".paginadorr ul").innerHTML += ` <li><a ${actual} data-pagina="${i}">${i}</a></li>`;
                                    }else{

                                        document.querySelector(".paginadorr ul").innerHTML += ` <li><a ${actual} data-pagina="${i}">${i}</a></li>`;
                                    }
                                }  
        
                            }
                        }else{
                            for(let j = parseInt(info.paginas) - 4; j <= parseInt(info.paginas);j++){
                                let actual = info.actual == j ? " class='pageSelectedDef'" : "";
                                if(j == info.actual){
                                    document.querySelector(".paginadorr ul").innerHTML += ` <li><a ${actual} data-pagina="${j}">${j}</a></li>`;
                                }else{

                                    document.querySelector(".paginadorr ul").innerHTML += ` <li><a ${actual} data-pagina="${j}">${j}</a></li>`;
                                }
                            }
                        }
                    }

                }

                    if(parseInt(info.actual) != parseInt(info.paginas)){
                        document.querySelector(".paginadorr ul").innerHTML += `
                        <li><a data-pagina="${parseInt(info.actual) + 1}" href=""><i data-pagina="${parseInt(info.actual) + 1}" class="fa-solid fa-caret-right"></i></a></li>
                        <li><a data-pagina="${info.paginas}" href="" ><i data-pagina="${info.paginas}" class="fa-solid fa-forward-step"></i></a></li>`;  
                    }

                     scannear_botones();
                     boton_agregar();
                     boton_cerrar();

                    
                 
                      

                }else{
                    $("#resultados").html("");
                    $(".paginadorr ul").html("");

                    $("#resultados").html("<tr><td>No hay resultados</td></tr>");
                }

            },
            error: function(error){
                console.log(error);
            }
        });




}