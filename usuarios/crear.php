<?php
require '../db_pdo.php'; // Asegúrate de que esta ruta es correcta

// Recoger datos del formulario
$usuario = [
    'nombre_usuario'      => $_POST['nombre_usuario'],
    'nombre'              => $_POST['nombre'],
    'apellidos'           => $_POST['apellidos'],
    'domicilio'           => $_POST['domicilio'],
    'correo_electronico'  => $_POST['correo_electronico'],
    'telefono'            => $_POST['telefono'],
    'contrasenya'         => password_hash($_POST['contrasenya'], PASSWORD_DEFAULT) // Cifrado seguro
];

// Consulta SQL adaptada a la tabla 'usuario'
$sql = "INSERT INTO usuario 
(nombre_usuario, nombre, apellidos, domicilio, correo_electronico, telefono, contrasenya) 
VALUES 
(:nombre_usuario, :nombre, :apellidos, :domicilio, :correo_electronico, :telefono, :contrasenya)";

try {
    $stmt = $conexion->prepare($sql);
    $stmt->execute([
        ':nombre_usuario'     => $usuario['nombre_usuario'],
        ':nombre'             => $usuario['nombre'],
        ':apellidos'          => $usuario['apellidos'],
        ':domicilio'          => $usuario['domicilio'],
        ':correo_electronico' => $usuario['correo_electronico'],
        ':telefono'           => $usuario['telefono'],
        ':contrasenya'        => $usuario['contrasenya']
    ]);

    // Redirección limpia a la página principal
    header("Location: ../Principal/index.html");
    exit();

} catch (PDOException $e) {
    // Manejo de errores sin interferir con las cabeceras
    echo "<p>Error al insertar usuario: " . htmlspecialchars($e->getMessage()) . "</p>";
}
