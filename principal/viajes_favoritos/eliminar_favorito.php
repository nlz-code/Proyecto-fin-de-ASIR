<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit();
}

require_once '../../db_pdo.php';

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$usuario = $_SESSION['usuario'];

if ($id <= 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'ID inválido']);
    exit();
}

try {
    // Verificar que el favorito pertenece al usuario y eliminarlo
    $sql = "DELETE FROM favoritos WHERE id = :id AND nombre_usuario = :usuario";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $id,
        ':usuario' => $usuario
    ]);

    if ($stmt->rowCount() > 0) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Favorito eliminado correctamente']);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Favorito no encontrado o no autorizado']);
    }
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
}
?>
