<?php
require_once __DIR__ . '/../includes/config.php';
exigir_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_validar();
    $actual = $_POST['actual'] ?? '';
    $nueva  = $_POST['nueva'] ?? '';
    $repite = $_POST['repite'] ?? '';

    $stmt = $pdo->prepare('SELECT password_hash FROM usuarios WHERE id = ?');
    $stmt->execute([$_SESSION['admin_id']]);
    $fila = $stmt->fetch();

    if (!$fila || !password_verify($actual, $fila['password_hash'])) {
        flash('error', 'La contraseña actual no es correcta.');
    } elseif (strlen($nueva) < 8) {
        flash('error', 'La nueva contraseña debe tener al menos 8 caracteres.');
    } elseif ($nueva !== $repite) {
        flash('error', 'Las contraseñas nuevas no coinciden.');
    } else {
        $pdo->prepare('UPDATE usuarios SET password_hash = ? WHERE id = ?')
            ->execute([password_hash($nueva, PASSWORD_DEFAULT), $_SESSION['admin_id']]);
        flash('ok', 'Contraseña actualizada.');
    }
    header('Location: cuenta.php');
    exit;
}

$titulo_pagina = 'Mi cuenta';
$activa = 'cuenta';
require __DIR__ . '/../includes/admin_header.php';
?>
<h1>Mi cuenta</h1>

<form method="post" class="formulario">
    <?= csrf_campo() ?>
    <fieldset>
        <legend>Cambiar contraseña</legend>
        <label>Contraseña actual
            <input type="password" name="actual" required autocomplete="current-password">
        </label>
        <label>Nueva contraseña (mínimo 8 caracteres)
            <input type="password" name="nueva" minlength="8" required autocomplete="new-password">
        </label>
        <label>Repite la nueva contraseña
            <input type="password" name="repite" minlength="8" required autocomplete="new-password">
        </label>
    </fieldset>
    <button type="submit" class="btn">Cambiar contraseña</button>
</form>
<?php require __DIR__ . '/../includes/admin_footer.php'; ?>
