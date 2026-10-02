<?php
// Cabecera común de todas las páginas del panel (excepto login).
require_once __DIR__ . '/config.php';
exigir_login();
$paginas_admin = [
    'panel'     => ['index.php',     'Resumen'],
    'perfil'    => ['perfil.php',    'Perfil y Acerca de mí'],
    'atestados' => ['atestados.php', 'Atestados'],
    'galeria'   => ['galeria.php',   'Galería'],
    'contacto'  => ['contacto.php',  'Contacto'],
    'cuenta'    => ['cuenta.php',    'Mi cuenta'],
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title><?= e($titulo_pagina ?? 'Panel') ?> · Administración</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin">
    <aside class="lateral">
        <p class="lateral-titulo">Administración</p>
        <nav aria-label="Panel">
            <?php foreach ($paginas_admin as $clave => [$archivo, $texto]): ?>
                <a href="<?= $archivo ?>"<?= ($activa ?? '') === $clave ? ' aria-current="page"' : '' ?>><?= $texto ?></a>
            <?php endforeach; ?>
        </nav>
        <div class="lateral-pie">
            <a href="../index.php" target="_blank" rel="noopener">Ver sitio público</a>
            <form method="post" action="logout.php">
                <?= csrf_campo() ?>
                <button type="submit" class="enlace-boton">Cerrar sesión</button>
            </form>
        </div>
    </aside>
    <main class="principal">
        <?php mostrar_flash(); ?>
