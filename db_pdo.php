<?php
$host = 'db';
$usuario = 'usuario';
$contrasena = 'usuario123';
$baseDatos = 'proyecto';

try {
    $conexion = new PDO("mysql:host=$host;dbname=$baseDatos;charset=utf8mb4", $usuario, $contrasena);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // No imprimir nada aquí
} catch (PDOException $e) {
    // Puedes lanzar una excepción o redirigir a una página de error
    die("Error de conexión: " . $e->getMessage());
}

