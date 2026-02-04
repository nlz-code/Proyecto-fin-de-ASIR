<?php
session_start();
require_once '../db_pdo.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
    $clave = $_POST['clave'] ?? '';

    if ($nombre_usuario && $clave) {
        try {
            $sql = "SELECT * FROM usuarios WHERE nombre_usuario = :nombre_usuario LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':nombre_usuario' => $nombre_usuario]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($clave, $usuario['clave'])) {
                $_SESSION['usuario'] = $usuario['nombre_usuario'];
                header('Location: ../principal/index.html');
                exit;
            } else {
                $_SESSION['error'] = 'Usuario o contraseña incorrectos';
                header('Location: ../principal/index.php');
                exit;
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error en la base de datos: ' . $e->getMessage();
            header('Location: ../principal/index.php');
            exit;
        }
    } else {
        $_SESSION['error'] = 'Por favor, completa todos los campos';
        header('Location: ../principal/index.php');
        exit;
    }
} else {
    header('Location: ../principal/index.php');
    exit;
}
