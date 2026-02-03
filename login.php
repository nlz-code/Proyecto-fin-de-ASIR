<?php
session_start();
require_once 'db_pdo.php';

$nombre_usuario = $_POST['nombre_usuario'] ?? '';
$clave = $_POST['clave'] ?? '';

try {
    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE nombre_usuario = :nombre_usuario AND clave = :clave");
    $stmt->execute([':nombre_usuario' => $nombre_usuario, ':clave' => $clave]);
    $usuario = $stmt->fetch();

    if ($usuario) {
        $_SESSION['usuario'] = $usuario['nombre'];
        header('Location: principal/index.php');
        exit;
    } else {
        $_SESSION['error'] = 'Credenciales incorrectas';
        header('Location: index.php');
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['error'] = 'Error en la conexión';
    header('Location: index.php');
    exit;
}