# VirtualHost de Mobility Alliance en XAMPP

El archivo `mobility-alliance.conf` esta preparado para XAMPP en Windows y apunta a:

```txt
C:/Users/USER/OneDrive/Documentos/proyecto
```

## 1. Copiar la configuracion

Copia el contenido de `mobility-alliance.conf` al final de este archivo de XAMPP:

```txt
C:\xampp\apache\conf\extra\httpd-vhosts.conf
```

## 2. Anadir el dominio local

Abre el Bloc de notas como administrador y edita:

```txt
C:\Windows\System32\drivers\etc\hosts
```

Anade esta linea:

```txt
127.0.0.1 mobility-alliance.local
```

## 3. Reiniciar Apache

En el panel de XAMPP, pulsa `Stop` en Apache y despues `Start`.

## 4. Abrir la web

```txt
http://mobility-alliance.local/
```
