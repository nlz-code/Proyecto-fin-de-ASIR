<?php
session_start();
require_once '../db_pdo.php';

// Si el usuario ya está logueado, lo enviamos a principal
if (isset($_SESSION['usuario'])) {
    header('Location: ../principal/index.php');
    exit;
}

// Solo procesamos POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
    $clave = $_POST['clave'] ?? '';

    if ($nombre_usuario && $clave) {
        try {
            //Busca si existe el usuario en la base de datos
            $sql = "SELECT * FROM usuarios WHERE nombre_usuario = :nombre_usuario LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':nombre_usuario' => $nombre_usuario]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario) {
                // Verificar contraseña
                if (password_verify($clave, $usuario['clave'])) {
                    // Login correcto
                    $_SESSION['usuario'] = $usuario['nombre_usuario'];
                    $_SESSION['rol'] = $usuario['rol'];
                    
                    // Si es admin, redirige a admin, sino a principal
                    if ($usuario['rol'] === 'admin') {
                        header('Location: ../admin/index.php');
                    } else {
                        header('Location: ../principal/index.php');
                    }
                    exit;
                } else {
                    $_SESSION['error'] = 'Usuario o contraseña incorrectos';
                }
            } else {
                $_SESSION['error'] = 'Usuario o contraseña incorrectos';
            }

        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error en la base de datos: ' . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = 'Por favor, completa todos los campos';
    }
}

// Si no es POST, o hubo error, volvemos al formulario de login
header('Location: ../index.php');
exit;