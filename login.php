<?php
require 'db_pdo.php';

if (isset($_POST['login'])) {

    $nombre_usuario = $_POST['nombre_usuario'];
    $contrasenya = $_POST['contrasenya'];

    // Buscar usuario en la BD
    $sql = "SELECT * FROM usuario WHERE nombre_usuario = :nombre_usuario";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([':nombre_usuario' => $nombre_usuario]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($contrasenya, $usuario['contrasenya'])) {

        // Crear sesión
        $_SESSION['usuario'] = $usuario['nombre_usuario'];

        // Redirigir a principal
        header("Location: /Principal/index.html");
        exit();

    } else {
        echo "<p style='color:red;'>Usuario o contraseña incorrectos</p>";
    }
}
?>
