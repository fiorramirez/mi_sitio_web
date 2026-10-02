<?php
require_once __DIR__ . '/../includes/config.php';
exigir_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_validar();
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'subir') {
        $titulo      = trim($_POST['titulo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');

        if ($titulo === '') {
            flash('error', 'Escribe un título para la foto.');
        } else {
            try {
                $imagen = subir_imagen('imagen');
                if (!$imagen) {
                    flash('error', 'Selecciona una imagen.');
                } else {
                    $pdo->prepare('INSERT INTO galeria (titulo, descripcion, imagen) VALUES (?,?,?)')
                        ->execute([$titulo, $descripcion, $imagen]);
                    flash('ok', 'Foto agregada a la galería.');
                }
            } catch (RuntimeException $ex) {
                flash('error', $ex->getMessage());
            }
        }
    }

    if ($accion === 'eliminar') {
        $id = (int)($_POST['id'] ?? 0);
        $stmt = $pdo->prepare('SELECT imagen FROM galeria WHERE id = ?');
        $stmt->execute([$id]);
        if ($fila = $stmt->fetch()) {
            borrar_imagen($fila['imagen']);
            $pdo->prepare('DELETE FROM galeria WHERE id = ?')->execute([$id]);
            flash('ok', 'Foto eliminada.');
        }
    }

    header('Location: galeria.php');
    exit;
}

$fotos = $pdo->query('SELECT * FROM galeria ORDER BY fecha_subida DESC, id DESC')->fetchAll();

$titulo_pagina = 'Galería';
$activa = 'galeria';
require __DIR__ . '/../includes/admin_header.php';
?>
<h1>Galería</h1>

<form method="post" enctype="multipart/form-data" class="formulario">
    <?= csrf_campo() ?>
    <input type="hidden" name="accion" value="subir">
    <fieldset>
        <legend>Subir una foto</legend>
        <label>Título *
            <input type="text" name="titulo" maxlength="160" required>
        </label>
        <label>Descripción
            <input type="text" name="descripcion" maxlength="255">
        </label>
        <label>Imagen * (JPG, PNG, WEBP; máx. 5 MB)
            <input type="file" name="imagen" accept="image/jpeg,image/png,image/webp,image/gif" required>
        </label>
    </fieldset>
    <button type="submit" class="btn">Subir foto</button>
</form>

<h2>Fotos publicadas</h2>
<?php if (!$fotos): ?>
    <p class="vacio">Aún no hay fotos.</p>
<?php else: ?>
<div class="rejilla-fotos">
    <?php foreach ($fotos as $f): ?>
        <figure>
            <img src="../uploads/<?= e($f['imagen']) ?>" alt="<?= e($f['titulo']) ?>" loading="lazy">
            <figcaption>
                <span><?= e($f['titulo']) ?></span>
                <form method="post" onsubmit="return confirm('¿Eliminar esta foto?');">
                    <?= csrf_campo() ?>
                    <input type="hidden" name="accion" value="eliminar">
                    <input type="hidden" name="id" value="<?= (int)$f['id'] ?>">
                    <button type="submit" class="btn btn-peligro btn-chico">Eliminar</button>
                </form>
            </figcaption>
        </figure>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<?php require __DIR__ . '/../includes/admin_footer.php'; ?>
