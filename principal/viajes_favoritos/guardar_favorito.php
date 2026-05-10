<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit();
}

require_once '../../db_pdo.php';

// Obtener datos del POST
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$km = isset($_POST['km']) ? floatval($_POST['km']) : 0;
$min = isset($_POST['min']) ? intval($_POST['min']) : 0;
$usuario = $_SESSION['usuario'];

// Validar datos
if (empty($nombre) || $km <= 0 || $min <= 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
    exit();
}

try {
    // Insertar favorito
    $sql = "INSERT INTO favoritos (nombre_usuario, nombre, distancia, tiempo, fecha_creacion) 
            VALUES (:usuario, :nombre, :km, :min, NOW())";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':usuario' => $usuario,
        ':nombre' => $nombre,
        ':km' => $km,
        ':min' => $min
    ]);

    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Favorito guardado correctamente']);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
}
?>
