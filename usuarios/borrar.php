<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header('Location: ../index.php');
    exit;
}

require_once('../config.php');
require_once('../db_pdo.php');

if($_SERVER['REQUEST_METHOD']=='GET'){

    $db=db_open();
    if($db){
        $id=$_GET['id'];
        db_delete_by_id($db, 'usuarios', $id);        

        db_close($db);

        header('Location: index.php');
    }
}