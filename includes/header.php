<?php
// Variables que cada página define antes de incluir este archivo:
//   $titulo_pagina  -> texto del <title>
//   $activa         -> nombre de la página actual (para resaltar el menú)
require_once __DIR__ . '/config.php';
$perfil_nav = $pdo->query('SELECT nombre_completo FROM perfil WHERE id = 1')->fetch();
$nombre_nav = $perfil_nav['nombre_completo'] ?? 'Mi sitio';

$menu = [
    'inicio'    => ['index.php',     'Inicio'],
    'acerca'    => ['acerca.php',    'Acerca de mí'],
    'atestados' => ['atestados.php', 'Atestados'],
    'galeria'   => ['galeria.php',   'Galería'],
    'contacto'  => ['contacto.php',  'Contacto'],
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($titulo_pagina ?? 'Mi sitio personal') ?> · <?= e($nombre_nav) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700;12..96,800&family=Source+Serif+4:opsz,wght@8..60,400;8..60,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>
<a class="saltar" href="#contenido">Saltar al contenido</a>
<header class="cabecera">
    <div class="contenedor cabecera-fila">
        <a class="marca" href="index.php"><?= e($nombre_nav) ?></a>
        <input type="checkbox" id="menu-toggle" class="menu-toggle" aria-label="Abrir menú">
        <label for="menu-toggle" class="menu-boton" aria-hidden="true"><span></span></label>
        <nav class="menu" aria-label="Principal">
            <?php foreach ($menu as $clave => [$archivo, $texto]): ?>
                <a href="<?= $archivo ?>"<?= ($activa ?? '') === $clave ? ' aria-current="page"' : '' ?>><?= $texto ?></a>
            <?php endforeach; ?>
        </nav>
    </div>
</header>
<main id="contenido">
