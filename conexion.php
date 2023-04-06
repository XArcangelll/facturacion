<?php 

$host  = "localhost";
$user = "root";
$pwd = "";
$db = "facturacion";
//$charset = "utf8mb4";

$connection = @mysqli_connect($host,$user,$pwd,$db);

if(!$connection){
    echo "error en la conexion";
}else{
    echo "conexion exitosa";
}
