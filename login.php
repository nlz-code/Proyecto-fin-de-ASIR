<?php
session_start();
require_once 'db_pdo.php';

$gmail = $_POST['gmail'] ?? '';
$clave = $_POST['clave'] ?? '';

try {
    $stmt = $pdo->prepare("SELECT * FROM usuario WHERE gmail = :gmail AND clave = :clave");
    $stmt->execute([':gmail' => $gmail, ':clave' => $clave]);
    $usuario = $stmt->fetch();

    if ($usuario) {
        $_SESSION['usuario'] = $usuario['nombre'];
        header('Location: principal/index.html');
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