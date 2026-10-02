<?php
$titulo_pagina = 'Resumen';
$activa = 'panel';
require __DIR__ . '/../includes/admin_header.php';

$total_atestados = $pdo->query('SELECT COUNT(*) FROM atestados')->fetchColumn();
$total_fotos     = $pdo->query('SELECT COUNT(*) FROM galeria')->fetchColumn();
$perfil          = $pdo->query('SELECT nombre_completo FROM perfil WHERE id = 1')->fetch();
?>
<h1>Hola, <?= e($perfil['nombre_completo']) ?></h1>
<p>Desde aquí administras todo el contenido de tu sitio.</p>

<div class="resumen">
    <a class="dato" href="atestados.php"><strong><?= (int)$total_atestados ?></strong><span>Atestados</span></a>
    <a class="dato" href="galeria.php"><strong><?= (int)$total_fotos ?></strong><span>Fotos en galería</span></a>
</div>

<h2>¿Qué quieres hacer?</h2>
<ul class="lista-simple">
    <li><a href="perfil.php">Cambiar mi foto, presentación, intereses y habilidades</a></li>
    <li><a href="atestados.php">Agregar un título, curso o certificación</a></li>
    <li><a href="galeria.php">Subir fotos a la galería</a></li>
    <li><a href="contacto.php">Actualizar mis datos de contacto</a></li>
</ul>
<?php require __DIR__ . '/../includes/admin_footer.php'; ?>
