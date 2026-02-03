<?php
session_start();
require_once '../db/db_pdo.php';

$pdo = db_open();

if (!$pdo) {
    $_SESSION['error'] = 'Error en la conexión con la base de datos';
    header('Location: ../index.php');
    exit;
}


$nombre_usuario = $_POST['nombre_usuario'] ?? '';
$clave          = $_POST['clave'] ?? '';

try {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nombre_usuario = :nombre_usuario");
    $stmt->execute([':nombre_usuario' => $nombre_usuario]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($clave, $usuario['clave'])) {
        session_regenerate_id(true);
        $_SESSION['usuario'] = $usuario['nombre'];
        header('Location: principal/index.php');
        exit;
    } else {
        $_SESSION['error'] = 'Credenciales incorrectas';
        header('Location: ../index.php');
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['error'] = 'Error en la conexión';
    header('Location: ../index.php');
    exit;
}
