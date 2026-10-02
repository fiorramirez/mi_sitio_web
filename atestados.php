<?php
$titulo_pagina = 'Atestados académicos';
$activa = 'atestados';
require 'includes/header.php';

$atestados = $pdo->query('SELECT * FROM atestados ORDER BY anio DESC, id DESC')->fetchAll();
?>
<section class="contenedor pagina">
    <h1>Atestados académicos</h1>

    <?php if (!$atestados): ?>
        <p class="vacio">Todavía no se han agregado atestados.</p>
    <?php endif; ?>

    <div class="linea-tiempo">
        <?php foreach ($atestados as $a): ?>
            <article class="atestado">
                <div class="atestado-anio"><?= e($a['anio']) ?></div>
                <div class="atestado-cuerpo">
                    <h2><?= e($a['titulo']) ?></h2>
                    <p class="atestado-institucion"><?= e($a['institucion']) ?></p>
                    <?php if ($a['descripcion']): ?>
                        <p><?= nl2br(e($a['descripcion'])) ?></p>
                    <?php endif; ?>
                </div>
                <?php if ($a['imagen']): ?>
                    <a class="atestado-imagen" href="uploads/<?= e($a['imagen']) ?>" target="_blank" rel="noopener">
                        <img src="uploads/<?= e($a['imagen']) ?>" alt="Imagen del atestado: <?= e($a['titulo']) ?>" loading="lazy">
                    </a>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php require 'includes/footer.php'; ?>
