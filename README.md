# Mobility Alliance

Proyecto web en PHP y MySQL para calcular tarifas de transporte, gestionar reservas de taxi, guardar viajes favoritos y administrar usuarios, taxistas, reservas, mensajes y exportaciones.

## Funcionalidades principales

- Inicio de sesion y registro de usuarios.
- Panel principal para calcular tarifas aproximadas de Uber, Cabify, Bolt y taxi.
- Guardado, edicion, uso y eliminacion de viajes favoritos.
- Reserva de taxistas desde la zona de usuario.
- Perfil de usuario editable.
- Formulario de contacto para enviar opiniones sobre la web.
- Panel de administracion para gestionar usuarios, taxistas, reservas y mensajes.
- Exportacion de datos en PDF desde administracion.
- Estilos organizados en la carpeta `css`.
- Adaptacion responsive para movil y ordenador.
- VirtualHost local con `mobility-alliance.local`.
- Publicacion temporal por HTTPS con ngrok.

## Estructura de carpetas

```txt
admin/                    Panel de administracion
apache/                   Configuracion del VirtualHost de XAMPP
bootstrap-5.3.8-dist/     Bootstrap local
css/                      Estilos de las paginas
img/                      Imagenes del proyecto
login/                    Inicio y cierre de sesion
principal/                Zona principal del usuario
usuarios/                 Registro de usuarios
proyecto_db.sql           Script de base de datos
db_pdo.php                Conexion principal a MySQL
config.php                Configuracion auxiliar de base de datos
index.php                 Pantalla de login
```

## Base de datos

La base de datos se llama:

```txt
proyecto_db
```

La conexion de XAMPP esta configurada en `db_pdo.php`:

```php
$host = 'localhost';
$db = 'proyecto_db';
$user = 'root';
$pass = '';
```

Tablas principales:

- `usuarios`
- `taxistas`
- `reservas`
- `favoritos`
- `mensajes_contacto`

## Usuarios de prueba

Despues de importar `proyecto_db.sql`, existen estos usuarios:

```txt
Usuario: admin
Clave: admin
Rol: admin
```

```txt
Usuario: nlopzay2502
Clave: admin
Rol: usuario
```

## Cambios realizados

### Viajes favoritos

Se corrigieron rutas incorrectas que provocaban errores `404` al guardar favoritos. Tambien se arreglaron las rutas de editar y eliminar.

Archivos principales:

- `principal/index.php`
- `principal/viajes_favoritos/index.php`
- `principal/viajes_favoritos/guardar_favorito.php`
- `principal/viajes_favoritos/editar_favorito.php`
- `principal/viajes_favoritos/eliminar_favorito.php`

### Panel de administracion

Se anadio una exportacion en PDF con todos los datos importantes:

- Usuarios
- Taxistas
- Reservas
- Viajes favoritos
- Mensajes de contacto

Archivo:

```txt
admin/exportar_pdf.php
```

### Contacto y opiniones

La pagina de contacto ahora permite a los usuarios mandar un mensaje diciendo si les gusta o no la web. El administrador puede verlo desde:

```txt
admin/mensajes.php
```

Tabla creada:

```txt
mensajes_contacto
```

### Registro e inicio de sesion

Se mejoro el registro de usuarios para mostrar errores claros si:

- El nombre de usuario ya existe.
- El correo ya esta registrado.
- Falta algun campo obligatorio.

Archivos:

- `usuarios/index.php`
- `usuarios/crear.php`
- `index.php`

### CSS y responsive

Se movieron estilos que estaban dentro de PHP a la carpeta `css`.

Archivos CSS:

- `css/signin.css`
- `css/principal.css`
- `css/paginas.css`
- `css/viajes_favoritos.css`
- `css/admin.css`

Tambien se hicieron ajustes para que la web se vea correctamente en movil y ordenador.

## VirtualHost local

Se configuro el dominio local:

```txt
http://mobility-alliance.local/
```

Archivo preparado:

```txt
apache/mobility-alliance.conf
```

Este bloque se copia dentro de:

```txt
C:\xampp\apache\conf\extra\httpd-vhosts.conf
```

Tambien hay que anadir en:

```txt
C:\Windows\System32\drivers\etc\hosts
```

la linea:

```txt
127.0.0.1 mobility-alliance.local
```

## Acceso publico con HTTPS

Para publicar temporalmente la web sin tocar el router se usa ngrok:

```bat
ngrok http 80
```

Mientras la ventana de ngrok este abierta, la web estara disponible con una URL HTTPS publica.

Ejemplo:

```txt
https://owl-dreamy-thyself.ngrok-free.dev
```

Importante:

- XAMPP debe estar encendido.
- Apache debe estar encendido.
- MySQL debe estar encendido.
- La ventana de ngrok no debe cerrarse.
- Si el ordenador se apaga o se suspende, la web deja de estar disponible.
