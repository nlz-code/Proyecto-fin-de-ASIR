# Mobility Alliance

Aplicacion web desarrollada en PHP para gestionar rutas, usuarios, taxistas y reservas de taxi. El proyecto forma parte del trabajo final de ASIR y esta preparado para ejecutarse en un entorno local con Apache, PHP y MySQL/MariaDB.

## Caracteristicas principales

- Registro e inicio de sesion de usuarios.
- Calculo de rutas mediante direcciones de origen y destino.
- Calculo manual de tarifas por kilometros y minutos.
- Gestion de viajes favoritos por usuario.
- Listado de taxistas disponibles segun su horario.
- Reserva de taxis con fecha, hora y direccion de recogida.
- Formulario de contacto y almacenamiento de mensajes.
- Panel de administracion para gestionar usuarios, taxistas, reservas y mensajes.
- Exportacion de informacion a PDF desde el panel de administracion.

## Tecnologias utilizadas

- PHP
- MySQL/MariaDB
- PDO para la conexion con la base de datos
- HTML, CSS y JavaScript
- Bootstrap 5.3.8
- Apache
- OpenStreetMap Nominatim y OSRM para busqueda de direcciones y calculo de rutas

## Estructura del proyecto

```text
.
+-- admin/                  # Panel de administracion
+-- apache/                 # Configuracion de VirtualHost
+-- bootstrap-5.3.8-dist/   # Archivos locales de Bootstrap
+-- css/                    # Estilos propios
+-- img/                    # Imagenes del proyecto
+-- includes/               # Funciones auxiliares y validaciones
+-- login/                  # Inicio y cierre de sesion
+-- principal/              # Zona privada de usuarios
+-- usuarios/               # Registro de usuarios
+-- config.php              # Configuracion general
+-- db_pdo.php              # Conexion PDO a MySQL
+-- index.php               # Pantalla inicial de login
+-- proyecto_db.sql         # Script de base de datos
```

## Requisitos

- Apache con soporte para PHP
- PHP 8 o superior
- MySQL o MariaDB
- Navegador web moderno

Tambien se puede usar un paquete local como XAMPP, WAMP o Laragon.

## Instalacion

1. Clona el repositorio o copia el proyecto en la carpeta publica de tu servidor local.

```bash
git clone <url-del-repositorio>
```

2. Crea una base de datos llamada `proyecto_db` en MySQL/MariaDB.

3. Importa el archivo `proyecto_db.sql`.

```bash
mysql -u root -p proyecto_db < proyecto_db.sql
```

Tambien puedes importarlo desde phpMyAdmin.

4. Revisa los datos de conexion en `db_pdo.php` y `config.php`.

Por defecto el proyecto usa:

```php
host: localhost
base de datos: proyecto_db
usuario: root
contrasena: vacia
puerto: 3306
```

5. Abre el proyecto desde el navegador.

Si usas Apache directamente:

```text
http://localhost/Proyecto-fin-de-ASIR/
```

Si configuras el VirtualHost incluido en `apache/mobility-alliance.conf`, puedes usar:

```text
http://mobility-alliance.local/
```

## Configuracion del VirtualHost

El archivo `apache/mobility-alliance.conf` incluye una configuracion de ejemplo para Apache. Si quieres usarla:

1. Copia o enlaza el archivo en la carpeta de sitios disponibles de Apache.
2. Ajusta `DocumentRoot` y el bloque `Directory` a la ruta real del proyecto en tu equipo.
3. Anade `mobility-alliance.local` al archivo `hosts` del sistema.
4. Reinicia Apache.

## Base de datos

El proyecto usa la base de datos `proyecto_db`, que contiene las siguientes tablas:

- `usuarios`
- `taxistas`
- `reservas`
- `favoritos`
- `mensajes_contacto`

El archivo `proyecto_db.sql` incluye la estructura necesaria y datos de ejemplo para arrancar el proyecto.

## Uso basico

1. Accede a la pagina inicial.
2. Registra un usuario o inicia sesion con un usuario existente de la base de datos.
3. Calcula una ruta introduciendo origen y destino, o introduce kilometros y minutos manualmente.
4. Guarda rutas como favoritas si quieres reutilizarlas.
5. Consulta los taxistas disponibles y realiza una reserva.
6. Accede con un usuario administrador para gestionar usuarios, taxistas, reservas y mensajes.

## Notas

- La aplicacion utiliza servicios externos para calcular rutas, por lo que algunas funciones necesitan conexion a Internet.
- Las credenciales de base de datos estan pensadas para desarrollo local. En produccion deben cambiarse y protegerse adecuadamente.
- Si cambias el nombre de la carpeta o configuras un dominio local, revisa las rutas absolutas usadas en los enlaces a estilos, imagenes y scripts.

## Autor

Proyecto final de ASIR.
