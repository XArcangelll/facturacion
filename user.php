<?php

/*

include_once 'conexion.php';

class User extends DB{

  public function userExists($user, $pass){
    $query = $this->connect()->prepare('SELECT * FROM usuario WHERE usuario = :user AND clave = :pass ');
    $query->execute(["user" => $user, "pass" => $pass ]);

    if($query->rowCount()){
        return true;
    }else{
        return false;
    }
}
}
*/
