<?php
$titulo_pagina = 'Acerca de mí';
$activa = 'acerca';
require 'includes/header.php';

$p = $pdo->query('SELECT * FROM perfil WHERE id = 1')->fetch();
$intereses   = lista_desde_texto($p['intereses']);
$habilidades = lista_desde_texto($p['habilidades']);
?>
<section class="contenedor pagina">
    <h1>Acerca de mí</h1>

    <div class="texto-largo estrecho">
        <?= nl2br(e($p['acerca_descripcion'])) ?>
    </div>

    <div class="dos-columnas">
        <div>
            <h2>Intereses</h2>
            <?php if ($intereses): ?>
                <ul class="etiquetas">
                    <?php foreach ($intereses as $i): ?><li><?= e($i) ?></li><?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="vacio">Aún no hay intereses registrados.</p>
            <?php endif; ?>
        </div>
        <div>
            <h2>Habilidades</h2>
            <?php if ($habilidades): ?>
                <ul class="etiquetas etiquetas-fuertes">
                    <?php foreach ($habilidades as $h): ?><li><?= e($h) ?></li><?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="vacio">Aún no hay habilidades registradas.</p>
            <?php endif; ?>
        </div>
    </div>

    <h2>Experiencia y conocimientos actuales</h2>
    <div class="texto-largo estrecho">
        <?= nl2br(e($p['experiencia'])) ?>
    </div>
</section>
<?php require 'includes/footer.php'; ?>
