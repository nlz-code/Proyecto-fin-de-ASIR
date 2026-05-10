<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../../login/login.php");
    exit();
}

require_once '../../db_pdo.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $opinion = $_POST['opinion'] ?? '';
    $mensaje = trim($_POST['mensaje'] ?? '');

    if (!in_array($opinion, ['me_gusta', 'no_me_gusta'], true) || $mensaje === '') {
        $_SESSION['error_contacto'] = 'Selecciona una opinion y escribe un mensaje.';
        header('Location: index.php');
        exit();
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO mensajes_contacto (nombre_usuario, opinion, mensaje) VALUES (:usuario, :opinion, :mensaje)");
        $stmt->execute([
            ':usuario' => $_SESSION['usuario'],
            ':opinion' => $opinion,
            ':mensaje' => $mensaje,
        ]);

        $_SESSION['exito_contacto'] = 'Mensaje enviado correctamente. Gracias por tu opinion.';
        header('Location: index.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error_contacto'] = 'No se pudo enviar el mensaje: ' . $e->getMessage();
        header('Location: index.php');
        exit();
    }
}

$exito = $_SESSION['exito_contacto'] ?? '';
$error = $_SESSION['error_contacto'] ?? '';
unset($_SESSION['exito_contacto'], $_SESSION['error_contacto']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto</title>
    <link href="../../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/paginas.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php">Mobility Alliance</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="../index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="../viajes_favoritos/index.php">Viajes favoritos</a></li>
                    <li class="nav-item"><a class="nav-link active" href="../contactos/index.php">Contacto</a></li>
                    <li class="nav-item"><a class="nav-link" href="../perfil/index.php">Mi perfil</a></li>
                    <li class="nav-item"><a class="nav-link" href="../../login/logout.php">Cerrar sesion</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4 contacto-container">
        <h1>Tu opinion nos ayuda</h1>
        <p>Cuéntanos si te gusta la web o que cambiarias para mejorarla.</p>

        <?php if ($exito): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($exito); ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">¿Te gusta la web?</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="opinion" id="opinionSi" value="me_gusta" required>
                            <label class="form-check-label" for="opinionSi">Si, me gusta</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="opinion" id="opinionNo" value="no_me_gusta" required>
                            <label class="form-check-label" for="opinionNo">No, se puede mejorar</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="mensaje" class="form-label">Mensaje</label>
                        <textarea class="form-control" id="mensaje" name="mensaje" rows="5" maxlength="1000" placeholder="Escribe aqui tu opinion..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Enviar mensaje</button>
                </form>
            </div>
        </div>
    </div>

    <script src="../../bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
