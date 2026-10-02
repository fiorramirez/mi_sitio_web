<?php
require_once __DIR__ . '/../includes/config.php';
exigir_login();

$redes = ['instagram' => 'Instagram', 'facebook' => 'Facebook', 'linkedin' => 'LinkedIn', 'github' => 'GitHub'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_validar();

    $correo   = trim($_POST['correo'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $valores  = [];
    $errores  = [];

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'Escribe un correo electrónico válido.';
    }
    foreach ($redes as $campo => $nombre) {
        $url = trim($_POST[$campo] ?? '');
        if (!url_valida($url)) {
            $errores[] = "El enlace de $nombre debe empezar con http:// o https://";
        }
        $valores[$campo] = $url;
    }

    if ($errores) {
        flash('error', implode(' ', $errores));
    } else {
        $pdo->prepare('UPDATE contacto SET correo=?, telefono=?, instagram=?, facebook=?, linkedin=?, github=? WHERE id=1')
            ->execute([$correo, $telefono, $valores['instagram'], $valores['facebook'], $valores['linkedin'], $valores['github']]);
        flash('ok', 'Datos de contacto guardados.');
    }
    header('Location: contacto.php');
    exit;
}

$c = $pdo->query('SELECT * FROM contacto WHERE id = 1')->fetch();

$titulo_pagina = 'Contacto';
$activa = 'contacto';
require __DIR__ . '/../includes/admin_header.php';
?>
<h1>Contacto</h1>

<form method="post" class="formulario">
    <?= csrf_campo() ?>
    <fieldset>
        <legend>Datos de contacto</legend>
        <label>Correo electrónico *
            <input type="email" name="correo" maxlength="120" required value="<?= e($c['correo']) ?>">
        </label>
        <label>Teléfono (opcional)
            <input type="text" name="telefono" maxlength="40" value="<?= e($c['telefono']) ?>">
        </label>
        <?php foreach ($redes as $campo => $nombre): ?>
            <label><?= $nombre ?> (opcional, enlace completo)
                <input type="url" name="<?= $campo ?>" maxlength="255" placeholder="https://" value="<?= e($c[$campo]) ?>">
            </label>
        <?php endforeach; ?>
    </fieldset>
    <button type="submit" class="btn">Guardar cambios</button>
</form>
<?php require __DIR__ . '/../includes/admin_footer.php'; ?>
