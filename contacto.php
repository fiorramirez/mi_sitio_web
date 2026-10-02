<?php
$titulo_pagina = 'Contacto';
$activa = 'contacto';
require 'includes/header.php';

$c = $pdo->query('SELECT * FROM contacto WHERE id = 1')->fetch();

$redes = [
    'instagram' => 'Instagram',
    'facebook'  => 'Facebook',
    'linkedin'  => 'LinkedIn',
    'github'    => 'GitHub',
];
?>
<section class="contenedor pagina">
    <h1>Contacto</h1>
    <p class="texto-largo estrecho">Puedes contactarme por cualquiera de estos medios.</p>

    <dl class="contacto-lista">
        <div>
            <dt>Correo electrónico</dt>
            <dd><a href="mailto:<?= e($c['correo']) ?>"><?= e($c['correo']) ?></a></dd>
        </div>

        <?php if (!empty($c['telefono'])): ?>
        <div>
            <dt>Teléfono</dt>
            <dd><a href="tel:<?= e(preg_replace('/[^0-9+]/', '', $c['telefono'])) ?>"><?= e($c['telefono']) ?></a></dd>
        </div>
        <?php endif; ?>

        <?php foreach ($redes as $campo => $nombre): ?>
            <?php if (!empty($c[$campo])): ?>
            <div>
                <dt><?= $nombre ?></dt>
                <dd><a href="<?= e($c[$campo]) ?>" target="_blank" rel="noopener noreferrer"><?= e($c[$campo]) ?></a></dd>
            </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </dl>
</section>
<?php require 'includes/footer.php'; ?>
