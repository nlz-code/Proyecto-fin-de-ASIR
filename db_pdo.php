<?php
$host = 'proyecto-db'; // nombre del contenedor MySQL en Docker
$db   = 'proyecto';    // nombre de la base de datos
$user = 'root';        // usuario de MySQL
$pass = '1234'; // reemplaza con la contraseña real
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

$pdo = new PDO($dsn, $user, $pass, $options);