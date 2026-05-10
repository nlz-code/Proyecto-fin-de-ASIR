<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../login/login.php");
    exit();
}

require_once '../../db_pdo.php';

// Obtener favoritos del usuario
try {
    $sql = "SELECT id, nombre, distancia, tiempo FROM favoritos WHERE nombre_usuario = :usuario ORDER BY fecha_creacion DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':usuario' => $_SESSION['usuario']]);
    $favoritos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $favoritos = [];
    $error = "Error al cargar favoritos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Viajes favoritos</title>
    <link href="../../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/viajes_favoritos.css" rel="stylesheet">
</head>
<body>
    <!-- Barra de navegación -->
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
                    <li class="nav-item"><a class="nav-link active" href="./index.php">Viajes favoritos</a></li>
                    <li class="nav-item"><a class="nav-link" href="../contactos/index.php">Contacto</a></li>
                    <li class="nav-item"><a class="nav-link" href="../perfil/index.php">Mi perfil</a></li>
                    <li class="nav-item"><a class="nav-link" href="../../login/logout.php">Cerrar sesión</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1><i class="fas fa-star"></i> Mis viajes favoritos</h1>
        <p>Gestiona tus viajes favoritos para acceder rápidamente a ellos</p>

        <?php if (!empty($favoritos)): ?>
            <div id="favoritosList">
                <?php foreach ($favoritos as $fav): ?>
                    <div class="favorito-card" id="favorito-<?php echo $fav['id']; ?>">
                        <div class="favorito-info">
                            <div class="favorito-nombre"><?php echo htmlspecialchars($fav['nombre']); ?></div>
                            <div class="favorito-datos">
                                <i class="fas fa-road"></i> <?php echo $fav['distancia']; ?> km | 
                                <i class="fas fa-clock"></i> <?php echo $fav['tiempo']; ?> minutos
                            </div>
                        </div>
                        <div class="favorito-acciones">
                            <button class="btn-usar" onclick="usarFavorito(<?php echo $fav['distancia']; ?>, <?php echo $fav['tiempo']; ?>)">
                                <i class="fas fa-play"></i> Usar
                            </button>
                            <button class="btn-editar" onclick='abrirModalEditar(<?php echo $fav['id']; ?>, <?php echo json_encode($fav['nombre']); ?>)'>
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <button class="btn-eliminar" onclick="eliminarFavorito(<?php echo $fav['id']; ?>)">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="sin-favoritos">
                <i class="fas fa-inbox"></i>
                <h3>No tienes ningún viaje favorito todavía</h3>
                <p>Guarda tus viajes favoritos desde la página principal para acceder rápidamente a ellos.</p>
                <a href="../index.php" class="btn btn-primary mt-acciones">Ir a la página principal</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal para editar favorito -->
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarLabel">Editar nombre del favorito</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="favoritoId">
                    <label for="nuevoNombre">Nuevo nombre:</label>
                    <input type="text" id="nuevoNombre" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarEdicion()">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../../bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const modalEditar = new bootstrap.Modal(document.getElementById('modalEditar'));

        function usarFavorito(km, min) {
            // Guardar en sessionStorage y redirigir
            sessionStorage.setItem('kmFavorito', km);
            sessionStorage.setItem('minFavorito', min);
            window.location.href = '../index.php';
        }

        function abrirModalEditar(id, nombre) {
            document.getElementById('favoritoId').value = id;
            document.getElementById('nuevoNombre').value = nombre;
            modalEditar.show();
        }

        function guardarEdicion() {
            const id = document.getElementById('favoritoId').value;
            const nuevoNombre = document.getElementById('nuevoNombre').value.trim();

            if (!nuevoNombre) {
                alert('Por favor, ingresa un nombre');
                return;
            }

            fetch('./editar_favorito.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'id=' + id + '&nombre=' + encodeURIComponent(nuevoNombre)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Favorito actualizado correctamente');
                    location.reload();
                } else {
                    alert('Error al actualizar: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al actualizar el favorito');
            });
        }

        function eliminarFavorito(id) {
            if (!confirm('¿Estás seguro de que quieres eliminar este favorito?')) {
                return;
            }

            fetch('./eliminar_favorito.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'id=' + id
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('favorito-' + id).remove();
                    alert('Favorito eliminado correctamente');
                    
                    // Si no quedan favoritos, recargar la página
                    if (document.querySelectorAll('.favorito-card').length === 0) {
                        location.reload();
                    }
                } else {
                    alert('Error al eliminar: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al eliminar el favorito');
            });
        }

    </script>

</body>
</html>
