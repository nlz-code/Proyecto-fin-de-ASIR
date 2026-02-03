<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../index.php');
    exit;
}

require_once('../db/config.php');
require_once('../db/db_pdo.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario['nombre_usuario'] = $_POST['Nombre de usuario'];
    $usuario['nombre'] = $_POST['Nombre'];
    $usuario['Apellidos'] = $_POST['Apellidos'];
    $usuario['Domicilio'] = $_POST['Domicilio'];
    $usuario['correo_electronico'] = $_POST['Correo electronico'];
    $usuario['telefono'] = $_POST['Teléfono'];
    $usuario['clave'] = password_hash($_POST['Contraseña'], PASSWORD_DEFAULT);

    $db = db_open();
    if ($db) {
        $id = db_insert($db, 'usuarios', $usuario);
        db_close($db);
    }

    header('Location: index.php');
}
?>
