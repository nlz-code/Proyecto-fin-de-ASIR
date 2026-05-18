<?php
session_start();

if (isset($_SESSION['usuario'])) {
    header('Location: ../principal/index.php');
    exit;
}

require_once '../db_pdo.php';
require_once '../includes/validaciones_usuario.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clave = $_POST['clave'] ?? '';
    $validacion = validar_usuario_formulario($_POST, true);
    $usuario = $validacion['datos'];
    $usuario['clave'] = password_hash($clave, PASSWORD_DEFAULT);

    try {
        if (!empty($validacion['errores'])) {
            $_SESSION['error'] = implode(' ', $validacion['errores']);
            header('Location: index.php');
            exit;
        }

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE nombre_usuario = :nombre_usuario");
        $stmt->execute([':nombre_usuario' => $usuario['nombre_usuario']]);
        if ($stmt->fetchColumn() > 0) {
            $_SESSION['error'] = 'Ese nombre de usuario ya existe';
            header('Location: index.php');
            exit;
        }

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE correo_electronico = :correo_electronico");
        $stmt->execute([':correo_electronico' => $usuario['correo_electronico']]);
        if ($stmt->fetchColumn() > 0) {
            $_SESSION['error'] = 'Ese correo electronico ya esta registrado';
            header('Location: index.php');
            exit;
        }

        $sql = "INSERT INTO usuarios
                (nombre_usuario, nombre, apellidos, domicilio, correo_electronico, telefono, clave)
                VALUES
                (:nombre_usuario, :nombre, :apellidos, :domicilio, :correo_electronico, :telefono, :clave)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($usuario);

        $_SESSION['exito'] = 'Usuario registrado correctamente. Ya puedes iniciar sesion.';
        header('Location: ../index.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error al registrar usuario: ' . $e->getMessage();
        header('Location: index.php');
        exit;
    }
}
?>
