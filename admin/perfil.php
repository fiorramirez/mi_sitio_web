<?php
require_once __DIR__ . '/../includes/config.php';
exigir_login();

$perfil = $pdo->query('SELECT * FROM perfil WHERE id = 1')->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_validar();

    $nombre = trim($_POST['nombre_completo'] ?? '');
    if ($nombre === '') {
        flash('error', 'El nombre completo es obligatorio.');
        header('Location: perfil.php');
        exit;
    }

    try {
        $foto_nueva = subir_imagen('foto');
    } catch (RuntimeException $ex) {
        flash('error', $ex->getMessage());
        header('Location: perfil.php');
        exit;
    }

    $foto = $perfil['foto'];
    if ($foto_nueva) {
        borrar_imagen($perfil['foto']);   
        $foto = $foto_nueva;
    } elseif (!empty($_POST['quitar_foto'])) {
        borrar_imagen($perfil['foto']);
        $foto = null;
    }

    $sql = 'UPDATE perfil SET nombre_completo=?, titular=?, descripcion_corta=?, presentacion=?,
            foto=?, acerca_descripcion=?, intereses=?, habilidades=?, experiencia=? WHERE id=1';
    $pdo->prepare($sql)->execute([
        $nombre,
        trim($_POST['titular'] ?? ''),
        trim($_POST['descripcion_corta'] ?? ''),
        trim($_POST['presentacion'] ?? ''),
        $foto,
        trim($_POST['acerca_descripcion'] ?? ''),
        trim($_POST['intereses'] ?? ''),
        trim($_POST['habilidades'] ?? ''),
        trim($_POST['experiencia'] ?? ''),
    ]);

    flash('ok', 'Perfil guardado.');
    header('Location: perfil.php');
    exit;
}

$titulo_pagina = 'Perfil';
$activa = 'perfil';
require __DIR__ . '/../includes/admin_header.php';
?>
<h1>Perfil y Acerca de mí</h1>

<form method="post" enctype="multipart/form-data" class="formulario">
    <?= csrf_campo() ?>

    <fieldset>
        <legend>Página de inicio</legend>

        <label>Nombre completo *
            <input type="text" name="nombre_completo" maxlength="120" required value="<?= e($perfil['nombre_completo']) ?>">
        </label>
        <label>Titular (ej: Estudiante de Ingeniería en Sistemas)
            <input type="text" name="titular" maxlength="160" value="<?= e($perfil['titular']) ?>">
        </label>
        <label>Descripción personal (una frase corta)
            <input type="text" name="descripcion_corta" maxlength="255" value="<?= e($perfil['descripcion_corta']) ?>">
        </label>
        <label>Presentación
            <textarea name="presentacion" rows="5"><?= e($perfil['presentacion']) ?></textarea>
        </label>

        <div class="foto-actual">
            <?php if ($perfil['foto']): ?>
                <img src="../uploads/<?= e($perfil['foto']) ?>" alt="Foto de perfil actual">
            <?php endif; ?>
            <div>
                <label>Fotografía de perfil (JPG, PNG, WEBP; máx. 5 MB)
                    <input type="file" name="foto" accept="image/jpeg,image/png,image/webp,image/gif">
                </label>
                <?php if ($perfil['foto']): ?>
                    <label class="casilla"><input type="checkbox" name="quitar_foto" value="1"> Quitar la foto actual</label>
                <?php endif; ?>
            </div>
        </div>
    </fieldset>

    <fieldset>
        <legend>Acerca de mí</legend>

        <label>Descripción amplia
            <textarea name="acerca_descripcion" rows="6"><?= e($perfil['acerca_descripcion']) ?></textarea>
        </label>
        <label>Intereses <small>(sepáralos con comas)</small>
            <input type="text" name="intereses" value="<?= e($perfil['intereses']) ?>">
        </label>
        <label>Habilidades <small>(sepáralas con comas)</small>
            <input type="text" name="habilidades" value="<?= e($perfil['habilidades']) ?>">
        </label>
        <label>Experiencia o conocimientos actuales
            <textarea name="experiencia" rows="5"><?= e($perfil['experiencia']) ?></textarea>
        </label>
    </fieldset>

    <button type="submit" class="btn">Guardar cambios</button>
</form>
<?php require __DIR__ . '/../includes/admin_footer.php'; ?>
