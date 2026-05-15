<?php
session_start();

if (!isset($_SESSION['usuario'])) {
  header("Location: ../login/login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mobility Alliance</title>
  <link href="../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/principal.css" rel="stylesheet">
</head>

<body>
  <!-- Barra de navegación -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Mobility Alliance</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link active" href="./index.php">Inicio</a></li>
          <li class="nav-item"><a class="nav-link" href="./viajes_favoritos/index.php">Viajes favoritos</a></li>
          <li class="nav-item"><a class="nav-link" href="./contactos/index.php">Contacto</a></li>
          <li class="nav-item"><a class="nav-link" href="./perfil/index.php">Mi perfíl</a></li>
          <li class="nav-item"><a class="nav-link" href="../login/logout.php">Cerrar sesión</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container">
    <h1>Bienvenido a </h1>
    <h1>Mobility Alliance</h1>
    <p class="intro-text">¿A dónde quieres viajar hoy?</p>
    <div class="route-box">
      <label for="origen">Origen</label>
      <input type="text" id="origen" placeholder="Ej: Calle Larios, Malaga">

      <label for="destino">Destino</label>
      <input type="text" id="destino" placeholder="Ej: Aeropuerto de Malaga">

      <button type="button" class="route-button" onclick="calcularRuta()">Calcular ruta</button>
      <p id="rutaEstado" class="route-status"></p>
    </div>
    
    <button type="button" class="manual-toggle" onclick="mostrarDatosManuales()">Introducir datos manualmente</button>
    <div id="datosManuales" class="manual-fields">

    <label for="km">¿Cuántos kilómetros quieres recorrer?</label>
    <input type="number" id="km" placeholder="Introduce los kilómetros" min="0.01" required>

    <label for="min">¿Cuántos minutos dura el viaje?</label>
    <input type="number" id="min" placeholder="Introduce los minutos" min="1" required>

    <button onclick="calcularTarifas()">Calcular Tarifas</button>
    </div>

    <!-- Botón estrella para guardar favorito (se muestra después de calcular) -->
    <div id="favoritoContainer" class="favorito-container">
      <button type="button" class="btn btn-warning btn-sm" onclick="abrirModalFavorito()">
        <i class="fas fa-star"></i> Guardar como favorito
      </button>
    </div>

    <div id="resultados"></div>
  </div>

  <!-- Modal para guardar favorito -->
  <div class="modal fade" id="modalFavorito" tabindex="-1" aria-labelledby="modalFavoritoLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalFavoritoLabel">Guardar viaje como favorito</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <label for="nombreFavorito">Nombre del favorito:</label>
          <input type="text" id="nombreFavorito" class="form-control" placeholder="Ej: Viaje al trabajo" required>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" onclick="guardarFavorito()">Guardar</button>
        </div>
      </div>
    </div>
  </div>

  <script src="../bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
  <script>
    let kmActual = 0;
    let minActual = 0;
    const modal = new bootstrap.Modal(document.getElementById('modalFavorito'));

    async function buscarCoordenadas(direccion) {
      const url = 'https://nominatim.openstreetmap.org/search?format=json&limit=1&countrycodes=es&q=' + encodeURIComponent(direccion);
      const response = await fetch(url, {
        headers: {
          'Accept': 'application/json'
        }
      });
      const data = await response.json();

      if (!data.length) {
        throw new Error('No se encontro la direccion: ' + direccion);
      }

      return {
        lat: parseFloat(data[0].lat),
        lon: parseFloat(data[0].lon)
      };
    }

    async function calcularRuta() {
      const origen = document.getElementById('origen').value.trim();
      const destino = document.getElementById('destino').value.trim();
      const estado = document.getElementById('rutaEstado');

      if (!origen || !destino) {
        estado.textContent = 'Introduce origen y destino para calcular la ruta.';
        estado.className = 'route-status route-error';
        return;
      }

      estado.textContent = 'Calculando ruta...';
      estado.className = 'route-status';

      try {
        const origenCoords = await buscarCoordenadas(origen);
        const destinoCoords = await buscarCoordenadas(destino);
        const rutaUrl = `https://router.project-osrm.org/route/v1/driving/${origenCoords.lon},${origenCoords.lat};${destinoCoords.lon},${destinoCoords.lat}?overview=false`;
        const response = await fetch(rutaUrl);
        const data = await response.json();

        if (!data.routes || !data.routes.length) {
          throw new Error('No se pudo calcular una ruta entre esos puntos.');
        }

        const ruta = data.routes[0];
        const km = ruta.distance / 1000;
        const min = Math.ceil(ruta.duration / 60);

        document.getElementById('km').value = km.toFixed(2);
        document.getElementById('min').value = min;
        estado.textContent = `Ruta calculada: ${km.toFixed(2)} km y ${min} minutos aprox.`;
        estado.className = 'route-status route-success';
        calcularTarifas();
      } catch (error) {
        console.error(error);
        estado.textContent = error.message || 'No se pudo calcular la ruta. Revisa las direcciones.';
        estado.className = 'route-status route-error';
      }
    }

    function mostrarDatosManuales() {
      const datosManuales = document.getElementById('datosManuales');
      const visible = datosManuales.style.display === 'block';
      datosManuales.style.display = visible ? 'none' : 'block';
    }

    function calcularTarifas() {
      const km = parseFloat(document.getElementById('km').value);
      const min = parseInt(document.getElementById('min').value);
      const res = document.getElementById('resultados');
      res.innerHTML = '';

      if (isNaN(km) || isNaN(min) || km <= 0 || min <= 0) {
        res.innerHTML = "<p class='error-message'>Por favor, ingresa valores válidos para kilómetros y minutos.</p>";
        document.getElementById('favoritoContainer').style.display = 'none';
        return;
      }

      kmActual = km;
      minActual = min;

      const uber = Math.max(4.5, km * 1.05 + min * 0.22);
      const cabifyBase = km * 0.75 + min * 0.30;
      const cabify = Math.max(4.33, cabifyBase * 1.05);
      const bolt = Math.max(4.0, km * 0.95 + min * 0.18);

      let taxiDia = Math.max(4.61, km * 1.05 + 1.88);
      let taxiNoche = Math.max(5.61, km * 1.25 + 2.27);
      let taxiFinSemanaNoche = Math.max(6.40, km * 1.43 + 2.59);
      let taxiAeroDia = Math.max(18.24, km * 1.05 + 1.88 + 6.49);
      let taxiAeroNoche = Math.max(22.48, km * 1.25 + 2.27 + 6.49);

      res.innerHTML += `<div class="price-notice">Precios orientativos. Uber, Cabify y Bolt pueden cambiar por demanda, trafico, ruta, suplementos o disponibilidad. El taxi se calcula con tarifas urbanas de Malaga 2026, sin incluir todos los posibles suplementos.</div>`;
      res.innerHTML += `<div class="result"><span>Uber aprox.: ${uber.toFixed(2)} &euro;</span> <a href="https://www.uber.com/es/" target="_blank">Reservar</a></div>`;
      res.innerHTML += `<div class="result"><span>Cabify aprox.: ${cabify.toFixed(2)} &euro;</span> <a href="https://cabify.com/es" target="_blank">Reservar</a></div>`;
      res.innerHTML += `<div class="result"><span>Bolt aprox.: ${bolt.toFixed(2)} &euro;</span> <a href="https://bolt.eu/es-es/" target="_blank">Reservar</a></div>`;
      res.innerHTML += `<div class="result"><span>Taxi tarifa 1 (laborables 06:00-22:00): ${taxiDia.toFixed(2)} &euro;</span><a href="./taxistas/index.php" target="_blank">Contactar</a></div>`;
      res.innerHTML += `<div class="result"><span>Taxi tarifa 2 (noches, festivos y fines de semana): ${taxiNoche.toFixed(2)} &euro;</span><a href="./taxistas/index.php" target="_blank">Contactar</a></div>`;
      res.innerHTML += `<div class="result"><span>Taxi tarifa 3 (viernes/festivos noche): ${taxiFinSemanaNoche.toFixed(2)} &euro;</span><a href="./taxistas/index.php" target="_blank">Contactar</a></div>`;
      res.innerHTML += `<div class="result"><span>Taxi aeropuerto tarifa 1: ${taxiAeroDia.toFixed(2)} &euro;</span><a href="./taxistas/index.php" target="_blank">Contactar</a></div>`;
      res.innerHTML += `<div class="result"><span>Taxi aeropuerto tarifa 2: ${taxiAeroNoche.toFixed(2)} &euro;</span><a href="./taxistas/index.php" target="_blank">Contactar</a></div>`;

      document.getElementById('favoritoContainer').style.display = 'block';
      return;

      res.innerHTML += `<div class="result"><span>Uber: ${uber.toFixed(2)} €</span> <a href="https://www.uber.com/es/" target="_blank">Reservar</a></div>`;
      res.innerHTML += `<div class="result"><span>Cabify: ${cabify.toFixed(2)} €</span> <a href="https://cabify.com/es" target="_blank">Reservar</a></div>`;
      res.innerHTML += `<div class="result"><span>Bolt: ${bolt.toFixed(2)} €</span> <a href="https://bolt.eu/es-es/" target="_blank">Reservar</a></div>`;
      res.innerHTML += `<div class="result"><span>Taxi (día y entre semana): ${taxiDia.toFixed(2)} €</span><a href="./taxistas/index.php" target="_blank">Contactar</a></div>`;
      res.innerHTML += `<div class="result"><span>Taxi (noche, fines y festivos): ${taxiNoche.toFixed(2)} €</span><a href="./taxistas/index.php" target="_blank">Contactar</a></div>`;
      if (taxiAeroDia !== null) {
        res.innerHTML += `<div class="result"><span>Taxi desde aeropuerto (día): ${taxiAeroDia.toFixed(2)} €</span><a href="./taxistas/index.php" target="_blank">Contactar</a></div>`;
        res.innerHTML += `<div class="result"><span>Taxi desde aeropuerto (noche, fines y festivos): ${taxiAeroNoche.toFixed(2)} €</span><a href="./taxistas/index.php" target="_blank">Contactar</a></div>`;
      }

      // Mostrar botón para guardar favorito
      document.getElementById('favoritoContainer').style.display = 'block';
    }

    function abrirModalFavorito() {
      document.getElementById('nombreFavorito').value = '';
      modal.show();
    }

    function guardarFavorito() {
      const nombre = document.getElementById('nombreFavorito').value.trim();

      if (!nombre) {
        alert('Por favor, ingresa un nombre para el favorito');
        return;
      }

      // Enviar datos al servidor
      fetch('./viajes_favoritos/guardar_favorito.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: 'nombre=' + encodeURIComponent(nombre) + '&km=' + kmActual + '&min=' + minActual
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('¡Favorito guardado correctamente!');
            modal.hide();
          } else {
            alert('Error al guardar el favorito');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Error al guardar el favorito');
        });
    }

    window.addEventListener('load', function() {
      const kmFavorito = sessionStorage.getItem('kmFavorito');
      const minFavorito = sessionStorage.getItem('minFavorito');

      if (kmFavorito && minFavorito) {
        document.getElementById('datosManuales').style.display = 'block';
        document.getElementById('km').value = kmFavorito;
        document.getElementById('min').value = minFavorito;
        calcularTarifas();
        sessionStorage.removeItem('kmFavorito');
        sessionStorage.removeItem('minFavorito');
      }
    });
  </script>

</body>

</html>

