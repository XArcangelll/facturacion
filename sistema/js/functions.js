function confirmacion(){
    var respuesta = confirm("¿Desea realmente borrar el registro?");
    if(respuesta == true){
            return true;
    }else{
        return false;
    }
}