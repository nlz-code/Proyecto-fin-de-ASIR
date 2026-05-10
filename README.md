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
Importante:

- XAMPP debe estar encendido.
- Apache debe estar encendido.
- MySQL debe estar encendido.
- La ventana de ngrok no debe cerrarse.
- Si el ordenador se apaga o se suspende, la web deja de estar disponible.
