<?php
session_start();

// Solo puede acceder si NO hay usuario logueado
if (isset($_SESSION['usuario'])) {
    // Si ya está logueado, lo mandamos a la página principal
    header('Location: ../principal/index.html');
    exit;
}

require_once('../db_pdo.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Recolectamos los datos del formulario
    $usuario = [
        'nombre_usuario'      => trim($_POST['nombre_usuario'] ?? ''),
        'nombre'              => trim($_POST['nombre'] ?? ''),
        'apellidos'           => trim($_POST['apellidos'] ?? ''),
        'domicilio'           => trim($_POST['domicilio'] ?? ''),
        'correo_electronico'  => trim($_POST['correo_electronico'] ?? ''),
        'telefono'            => trim($_POST['telefono'] ?? ''),
        'clave'               => password_hash($_POST['clave'] ?? '', PASSWORD_DEFAULT),
    ];

    try {
        // Inserción con PDO
        $sql = "INSERT INTO usuarios 
                (nombre_usuario, nombre, apellidos, domicilio, correo_electronico, telefono, clave)
                VALUES 
                (:nombre_usuario, :nombre, :apellidos, :domicilio, :correo_electronico, :telefono, :clave)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($usuario);

        // Guardamos el usuario en sesión y redirigimos a la página principal
        $_SESSION['usuario'] = $usuario['nombre_usuario'];
        header('Location: ../principal/index.html');
        exit;

    } catch (PDOException $e) {
        // Mensaje de error mientras desarrollas
        die("Error al registrar usuario: " . $e->getMessage());
    }
}
?>

