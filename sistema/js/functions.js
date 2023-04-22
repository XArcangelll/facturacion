
function confirmacion(){
    var respuesta = confirm("¿Desea realmente borrar el registro?");
    if(respuesta == true){
            return true;
    }else{
        return false;
    }

}


$(document).ready(function(){

    //--------------------- SELECCIONAR FOTO PRODUCTO ---------------------
    $("#foto").on("change",function(){
    	var uploadFoto = document.getElementById("foto").value;
        var foto       = document.getElementById("foto").files;
        var nav = window.URL || window.webkitURL;
        var contactAlert = document.getElementById('form_alert');
        
            if(uploadFoto !='')
            {
                var type = foto[0].type;
                var name = foto[0].name;
                if(type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png')
                {
                    contactAlert.innerHTML = '<p class="errorArchivo">El archivo no es válido.</p>';                        
                    $("#img").remove();
                    $(".delPhoto").addClass('notBlock');
                    $('#foto').val('');
                    return false;
                }else{  
                        contactAlert.innerHTML='';
                        $("#img").remove();
                        $(".delPhoto").removeClass('notBlock');
                        var objeto_url = nav.createObjectURL(this.files[0]);
                        $(".prevPhoto").append("<img id='img' src="+objeto_url+">");
                        $(".upimg label").remove();
                        
                    }
              }else{
              	alert("No selecciono foto");
                $("#img").remove();
                $(".delPhoto").addClass('notBlock');
              }              
    });

    $('.delPhoto').click(function(){

        var confirmacion = confirm("Seguro desea eliminar la foto?");

        if(confirmacion == true){
            
    	$('#foto').val('');
    	$(".delPhoto").addClass('notBlock');
    	$("#img").remove();

        if($("#foto_actual") && $("#foto_remove")){
            $("#foto_remove").val("img_producto.png");
        }


        }


    });


    //modal form add product

    $(".add_product").click(function(e){
        e.preventDefault();

        var producto = $(this).attr("product");
        var action = "infoProducto";

        $.ajax({
            url: "ajax.php",
            type: "POST",
            async: true,
            data:{action: action, producto:producto }
        ,
        success: function(response){
           
            if(response != "error"){
                var info = JSON.parse(response);
              

                $(".bodyModal").html('<form action="" method="post" name="form_add_product" id="form_add_product" onsubmit="event.preventDefault(); sendDataProduct();">'+
                '<h1><i class="fas fa-cubes bloki" style="font-size:45pt;"></i>AGREGAR PRODUCTO</h1>'+
                '<h2 class="nameProducto"></h2>'+
                '<div class="junto">'+
                '<label for="">Cantidad: </label>'+
                '<input type="number" name="cantidad" id="txtCantidad" placeholder="Cantidad del Producto" required>'+
                '</div>'+
                '<div class="junto">'+
                '<label for="">Precio: </label>'+
                '<input type="text" name="precio" id="txtPrecio" placeholder="Precio del Producto" required>'+
                '</div>'+
                '<input type="hidden" name="producto_id" id="producto_id"  required>'+
                '<input type="hidden" name="action" value="addProduct" required>'+
                '<div class="alert alertAddProduct" ></div>'+
                '<div class="botones2">'+
                '<button type="submit" class="btn_ok"><i class="fas fa-plus"></i> Agregar</button>'+
                '<a href="#" class="btn_ok closeModal" onclick="closeModal();"><i class="fas fa-ban"></i> Cerrar</a>'+
                '</div>'+

   '</form>')
   $("#producto_id").val(info.codproducto);
   $(".nameProducto").html(info.descripcion);
            }

        },
        error: function(error){
            console.log(error);
        }
    });

        setTimeout(() => {
            $(".modal").fadeIn();
        }, 350);
       

    });


    //Modal form delete product

    $(".del_product").click(function(e){
        e.preventDefault();

        var producto = $(this).attr("product");
        var action = "infoProducto";

        $.ajax({
            url: "ajax.php",
            type: "POST",
            async: true,
            data:{action: action, producto:producto }
        ,
        success: function(response){
           
            if(response != "error"){
                var info = JSON.parse(response);
              

                $(".bodyModal").html('<form action="" method="post" name="form_del_product" id="form_del_product" onsubmit="event.preventDefault(); delProduct();">'+
                '<h1><i class="fas fa-cubes bloki" style="font-size:45pt;"></i></h1>'+
                '<h2>¿Está seguro de eliminar el siguiente Producto?</h2>'+
                '<h2 class="nameProducto"></h2>'+
                '<input type="hidden" name="producto_id" id="producto_id"  required>'+
                '<input type="hidden" name="action" value="delProduct" required>'+
                '<div class="alert alertAddProduct" ></div>'+
                '<button type="submit" class="btn_ok btn_el" "><i class="fa-solid fa-trash"></i> Eliminar</button>'+
                '<a href="#" class="btn_cancel" onclick="closeModal();"><i class="fa-solid fa-xmark"></i> Cancelar</a>'+
   '</form>')
   $("#producto_id").val(info.codproducto);
   $(".nameProducto").html(info.descripcion);
            }

        },
        error: function(error){
            console.log(error);
        }
    });

        setTimeout(() => {
            $(".modal").fadeIn();
        }, 350);
       

    });


    $("#search_proveedor").on("change",function(e){
        e.preventDefault();
        var sistema = getUrl();
        location.href = sistema+'buscar_producto.php?proveedor='+$(this).val();
    });


}); //end ready

function getUrl(){

    var loc = window.location;
    var pathname = loc.pathname.substring(0,loc.pathname.lastIndexOf("/")+1);
    return loc.href.substring(0,loc.href.length -((loc.pathname + loc.search + loc.hash).length - pathname.length));
 
}


function sendDataProduct(){

    $(".alertAddProduct").html("");

    $.ajax({
        url: "ajax.php",
        type: "POST",
        async: true,
        data: $("#form_add_product").serialize()
    ,
    success: function(response){
       if(response == "error"){
        $(".alertAddProduct").html('<p style="color: red;">Error al agregar producto</p>');
       }else{
        var info = JSON.parse(response);
        $(".row"+info.producto_id+" .celPrecio").html(info.nuevo_precio);
        $(".row"+info.producto_id+" .celExistencia").html(info.nueva_existencia);
        $("#txtCantidad").val("");
        $("#txtPrecio").val("");
        $(".alertAddProduct").fadeIn();
        $(".alertAddProduct").html('<p>Guardado Correctamente</p>');

        setTimeout(() => {
            $(".alertAddProduct").fadeOut();
        }, 2000);
       }
    },
    error: function(error){
        console.log(error);
    }
});
}


//eliminar producto
function delProduct(){

    var pr = $("#producto_id").val();

    $(".alertAddProduct").html("");

    $.ajax({
        url: "ajax.php",
        type: "POST",
        async: true,
        data: $("#form_del_product").serialize()
    ,
    success: function(response){

        console.log(response);
        
       if(response == "error"){
        $(".alertAddProduct").html('<p style="color: red;">Error al eliminar el  producto</p>');
       }else{
            $(".row"+pr).remove();
            $("#form_del_product .btn_ok").remove();
            $("#form_del_product .btn_cancel").html("Cerrar");
        $(".alertAddProduct").fadeIn();
        $(".alertAddProduct").html('<p>Eliminado Correctamente</p>');

        setTimeout(() => {
            $(".alertAddProduct").fadeOut();
        }, 2000);
       }
    },
    error: function(error){
        console.log(error);
    }
});
}



function closeModal(){
        $(".modal").fadeOut();
        $(".alertAddProduct").html("");
        $("#producto_id").val("");
        $("#txtCantidad").val("");
        $("#txtPrecio").val("");
}

