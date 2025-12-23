<?php
require_once('config.php');
require_once('db_pdo.php');

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $db=db_open();

    $res=db_query($db, "SELECT * FROM usuarios WHERE email=?", [$_POST['email']]);

    if($res===false){
        $_SESSION['error']="Se ha producido un error";
        header('Location: index.php');
        exit;
    }

    if(count($res)==1)
    {
        $usuario=$res[0];
        if($usuario['password']==$_POST['password']){
            $_SESSION['usuario']=$usuario;
            header('Location: alumnos/index.php');
            exit;
        }else{
            $_SESSION['error']="El password es incorrecto";
            header('Location: index.php');
        }

    }else{
        $_SESSION['error']="El usuario no existe";
        header('Location: index.php');
    }    
}