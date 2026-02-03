<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../index.php');
    exit;
}
require_once('../config.php');
require_once('../db_pdo.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $usuario['Nombre de usuario'] = $_POST['Nombre de usuario'];
    $usuario['Nombre'] = $_POST['Nombre'];
    $usuario['Apellidos'] = $_POST['Apellidos'];
    $usuario['Domicilio'] = $_POST['Domicilio'];
    $usuario['Correo electronico'] = $_POST['Correo electronico'];
    $usuario['Teléfono'] = $_POST['Teléfono'];
    $usuario['Contraseña'] = $_POST['Contraseña'];

    $db = db_open();
    if ($db) {
        $id = db_insert($db, 'usuarios', $usuario);

        //echo "Se ha insertado un usuario con id $id";

        db_close($db);

        header('Location: index.php');
    }
}
