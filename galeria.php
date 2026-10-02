<?php
$titulo_pagina = 'Galería';
$activa = 'galeria';
require 'includes/header.php';

$fotos = $pdo->query('SELECT * FROM galeria ORDER BY fecha_subida DESC, id DESC')->fetchAll();
?>
<section class="contenedor pagina">
    <h1>Galería</h1>

    <?php if (!$fotos): ?>
        <p class="vacio">La galería está vacía por ahora.</p>
    <?php endif; ?>

    <div class="galeria">
        <?php foreach ($fotos as $f): ?>
            <figure>
                <a href="uploads/<?= e($f['imagen']) ?>" target="_blank" rel="noopener">
                    <img src="uploads/<?= e($f['imagen']) ?>" alt="<?= e($f['titulo']) ?>" loading="lazy">
                </a>
                <figcaption>
                    <strong><?= e($f['titulo']) ?></strong>
                    <?php if ($f['descripcion']): ?><span><?= e($f['descripcion']) ?></span><?php endif; ?>
                </figcaption>
            </figure>
        <?php endforeach; ?>
    </div>
</section>
<?php require 'includes/footer.php'; ?>
