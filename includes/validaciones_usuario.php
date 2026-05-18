<?php

function contiene_codigo(string $valor): bool
{
    return $valor !== strip_tags($valor) || str_contains($valor, '<') || str_contains($valor, '>');
}

function validar_usuario_formulario(array $post, bool $clave_obligatoria = true): array
{
    $datos = [
        'nombre_usuario' => trim($post['nombre_usuario'] ?? ''),
        'nombre' => trim($post['nombre'] ?? ''),
        'apellidos' => trim($post['apellidos'] ?? ''),
        'domicilio' => trim($post['domicilio'] ?? ''),
        'telefono' => trim($post['telefono'] ?? ''),
        'correo_electronico' => trim($post['correo_electronico'] ?? ''),
        'clave' => $post['clave'] ?? '',
    ];
    $errores = [];

    foreach (['nombre_usuario', 'nombre', 'apellidos', 'domicilio', 'telefono', 'correo_electronico'] as $campo) {
        if (contiene_codigo($datos[$campo])) {
            $errores[] = 'No se permite introducir codigo HTML o scripts en los campos.';
            break;
        }
    }

    if ($datos['nombre_usuario'] === '') {
        $errores[] = 'El nombre de usuario es obligatorio.';
    } elseif (strlen($datos['nombre_usuario']) > 50) {
        $errores[] = 'El nombre de usuario no puede superar 50 caracteres.';
    } elseif (!preg_match('/^[A-Za-z0-9_]{3,50}$/', $datos['nombre_usuario'])) {
        $errores[] = 'El nombre de usuario solo puede tener letras, numeros y guion bajo.';
    }

    if ($datos['nombre'] === '') {
        $errores[] = 'El nombre es obligatorio.';
    } elseif (mb_strlen($datos['nombre'], 'UTF-8') > 10) {
        $errores[] = 'El nombre no puede superar 10 caracteres.';
    } elseif (!preg_match('/^[\p{L}\s\'-]+$/u', $datos['nombre'])) {
        $errores[] = 'El nombre solo puede contener letras.';
    }

    if ($datos['apellidos'] !== '') {
        if (mb_strlen($datos['apellidos'], 'UTF-8') > 100) {
            $errores[] = 'Los apellidos no pueden superar 100 caracteres.';
        } elseif (!preg_match('/^[\p{L}\s\'-]+$/u', $datos['apellidos'])) {
            $errores[] = 'Los apellidos solo pueden contener letras.';
        }
    }

    if ($datos['domicilio'] !== '' && mb_strlen($datos['domicilio'], 'UTF-8') > 255) {
        $errores[] = 'El domicilio no puede superar 255 caracteres.';
    }

    if ($datos['telefono'] !== '' && !preg_match('/^[0-9]{9}$/', $datos['telefono'])) {
        $errores[] = 'El telefono debe tener exactamente 9 digitos.';
    }

    if ($datos['correo_electronico'] === '') {
        $errores[] = 'El correo electronico es obligatorio.';
    } elseif (mb_strlen($datos['correo_electronico'], 'UTF-8') > 150) {
        $errores[] = 'El correo electronico no puede superar 150 caracteres.';
    } elseif (!filter_var($datos['correo_electronico'], FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'El correo electronico no tiene un formato valido.';
    }

    if ($clave_obligatoria && $datos['clave'] === '') {
        $errores[] = 'La contrasena es obligatoria.';
    }

    if ($datos['clave'] !== '') {
        if (strlen($datos['clave']) < 6) {
            $errores[] = 'La contrasena debe tener al menos 6 caracteres.';
        } elseif (strlen($datos['clave']) > 72) {
            $errores[] = 'La contrasena no puede superar 72 caracteres.';
        }
    }

    unset($datos['clave']);

    return [
        'datos' => $datos,
        'errores' => $errores,
    ];
}
