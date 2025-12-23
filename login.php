<?php
require_once('config.php');
require_once('db_pdo.php');

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $db=db_open();

    $res=db_query($db, "SELECT * FROM usuarios WHERE Gmail=?", [$_POST['Gmail']]);

    if($res===false){
        $_SESSION['error']="Se ha producido un error";
        header('Location: index.php');
        exit;
    }

    if(count($res)==1)
    {
        $usuario=$res[0];
        if($usuario['Contraseña']==$_POST['Contraseña']){
            $_SESSION['usuario']=$usuario;
            header('Location: principal/index.php');
            exit;
        }else{
            $_SESSION['error']="La contraseña es incorrecta";
            header('Location: index.php');
        }

    }else{
        $_SESSION['error']="El usuario no existe";
        header('Location: index.php');
    }    
}