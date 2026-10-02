<?php
$titulo_pagina = 'Inicio';
$activa = 'inicio';
require 'includes/header.php';

$p = $pdo->query('SELECT * FROM perfil WHERE id = 1')->fetch();
?>
<section class="portada contenedor">
    <div class="portada-texto">
        <p class="portada-titular"><?= e($p['titular']) ?></p>
        <h1><?= e($p['nombre_completo']) ?></h1>
        <p class="portada-corta"><?= e($p['descripcion_corta']) ?></p>
        <div class="portada-acciones">
            <a class="boton" href="acerca.php">Conóceme</a>
            <a class="boton boton-suave" href="contacto.php">Escríbeme</a>
        </div>
    </div>

    <div class="portada-foto">
        <?php if ($p['foto']): ?>
            <img src="uploads/<?= e($p['foto']) ?>" alt="Fotografía de <?= e($p['nombre_completo']) ?>">
        <?php else: ?>
            <div class="foto-vacia" aria-hidden="true"><?= e(mb_substr($p['nombre_completo'], 0, 1)) ?></div>
        <?php endif; ?>
    </div>
</section>

<section class="presentacion contenedor">
    <h2>Presentación</h2>
    <div class="texto-largo">
        <?= nl2br(e($p['presentacion'])) ?>
    </div>
</section>
<?php require 'includes/footer.php'; ?>
