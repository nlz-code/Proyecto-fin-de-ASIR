<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header('Location: ../index.php');
    exit;
}

require_once('../config.php');
require_once('../db_pdo.php');

if($_SERVER['REQUEST_METHOD']=='POST'){

    $usuario['id']=$_POST['id'];
    $usuario['nombre']=$_POST['nombre'];
    $usuario['email']=$_POST['email'];
    $usuario['password']=$_POST['password'];




    $db=db_open();
    if($db){
        $id=db_update($db, 'usuarios', $usuario);
        
        //echo "Se ha insertado un usuario con id $id";

        db_close($db);

        header('Location: index.php');
    }
}