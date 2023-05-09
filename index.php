<?php 

error_reporting(E_ALL); // Error/Exception engine, always use E_ALL

ini_set('ignore_repeated_errors', TRUE); // always use TRUE

ini_set('display_errors', FALSE); // Error/Exception display, use FALSE only in production environment or real server. Use TRUE in development environment

ini_set('log_errors', TRUE); // Error/Exception file logging engine.

ini_set("error_log", "php-error.log");
error_log( "Inicia App" );



/*


require_once("user.php");

$user = new User();

if(isset($_POST["usuario"]) && isset($_POST["clave"])){
    //  echo "validación de login";

      $userForm = $_POST["usuario"];
      $passForm = $_POST["clave"];

      if($user->userExists($userForm,$passForm)){
         // echo "usuario validado";

              header("location: sistema");

      }else{
          //echo "Nombre y/o password incorrecto";
          $errorLogin = "Nombre y/o password incorrecto";
          include_once "login.php";
      }

}else{
  //echo "Login";
  include_once "login.php";
}

?>
*/

$alert = "";

session_start();
if(!empty($_SESSION["active"])){
    header("location: sistema/");   
}
else{
    if(!empty($_POST)){
        if(empty($_POST["usuario"]) || empty($_POST["clave"])){
            $alert = "Ingrese su usuario y/o su clave";
        }
        else{
            $alert = "";

            require_once "conexion.php";

            $user = mysqli_real_escape_string($connection, $_POST["usuario"]);
            $pass = md5(mysqli_real_escape_string($connection, $_POST["clave"]));

            $query = mysqli_query($connection, "SELECT u.idusuario,u.nombre,u.correo,u.usuario,u.rol as idrol , r.rol as nombrerol FROM usuario u inner join rol r on u.rol = r.idrol WHERE usuario = '$user' and clave = '$pass' and estatus = 1");
            mysqli_close($connection);
            $result = mysqli_num_rows($query);

             if($result > 0){
                    $data =  mysqli_fetch_array($query);
               
                        $_SESSION["active"] = true;
                        $_SESSION["idUser"] = $data["idusuario"];
                        $_SESSION["nombre"] = $data["nombre"];
                        $_SESSION["email"] = $data["correo"];
                        $_SESSION["user"] = $data["usuario"];
                        $_SESSION["rol"] = $data["idrol"];
                        $_SESSION["nombreRol"] = $data["nombrerol"];
                        header("location: sistema/");                    
            }else{

                $alert = "El usuario y/o clave son incorrectos";
           
             session_destroy();
            }
        }
    }

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login | Sistema Facturación </title>

    <link rel="stylesheet" type="text/css" href="css/style.css">

</head>
<body>

    <section id="container">

        <form action="" method="post" >

            <h3>Iniciar Sesión</h3>
            <img src="img/login.png" alt="login" >

                <input type="text" name="usuario" placeholder="Usuario" >
                <input type="password" name="clave" placeholder="Contraseña" >
                <p class="alert">
                <?php
                    echo (isset($alert) ? $alert : "");
                    ?>
                </p>
                <input type="submit" value="INGRESAR" >

        </form>

    </section>

    
</body>
</html>