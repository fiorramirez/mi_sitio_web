<?php
require_once __DIR__ . '/../includes/config.php';
exigir_login();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_validar();
    $accion = $_POST['accion'] ?? '';

    
    if ($accion === 'eliminar') {
        $id = (int)($_POST['id'] ?? 0);
        $stmt = $pdo->prepare('SELECT imagen FROM atestados WHERE id = ?');
        $stmt->execute([$id]);
        if ($fila = $stmt->fetch()) {
            borrar_imagen($fila['imagen']);
            $pdo->prepare('DELETE FROM atestados WHERE id = ?')->execute([$id]);
            flash('ok', 'Atestado eliminado.');
        }
        header('Location: atestados.php');
        exit;
    }


    if ($accion === 'guardar') {
        $id          = (int)($_POST['id'] ?? 0);
        $titulo      = trim($_POST['titulo'] ?? '');
        $institucion = trim($_POST['institucion'] ?? '');
        $anio        = (int)($_POST['anio'] ?? 0);
        $descripcion = trim($_POST['descripcion'] ?? '');

        if ($titulo === '' || $institucion === '' || $anio < 1950 || $anio > (int)date('Y') + 5) {
            flash('error', 'Completa título, institución y un año válido.');
            header('Location: atestados.php' . ($id ? "?editar=$id" : ''));
            exit;
        }

        try {
            $imagen_nueva = subir_imagen('imagen');
        } catch (RuntimeException $ex) {
            flash('error', $ex->getMessage());
            header('Location: atestados.php' . ($id ? "?editar=$id" : ''));
            exit;
        }

        if ($id) {
            $stmt = $pdo->prepare('SELECT imagen FROM atestados WHERE id = ?');
            $stmt->execute([$id]);
            $actual = $stmt->fetch();
            $imagen = $actual['imagen'] ?? null;
            if ($imagen_nueva) {
                borrar_imagen($imagen);
                $imagen = $imagen_nueva;
            }
            $pdo->prepare('UPDATE atestados SET titulo=?, institucion=?, anio=?, descripcion=?, imagen=? WHERE id=?')
                ->execute([$titulo, $institucion, $anio, $descripcion, $imagen, $id]);
            flash('ok', 'Atestado actualizado.');
        } else {
            $pdo->prepare('INSERT INTO atestados (titulo, institucion, anio, descripcion, imagen) VALUES (?,?,?,?,?)')
                ->execute([$titulo, $institucion, $anio, $descripcion, $imagen_nueva]);
            flash('ok', 'Atestado agregado.');
        }
        header('Location: atestados.php');
        exit;
    }
}

$editando = null;
if (isset($_GET['editar'])) {
    $stmt = $pdo->prepare('SELECT * FROM atestados WHERE id = ?');
    $stmt->execute([(int)$_GET['editar']]);
    $editando = $stmt->fetch() ?: null;
}
$atestados = $pdo->query('SELECT * FROM atestados ORDER BY anio DESC, id DESC')->fetchAll();

$titulo_pagina = 'Atestados';
$activa = 'atestados';
require __DIR__ . '/../includes/admin_header.php';
?>
<h1>Atestados académicos</h1>

<form method="post" enctype="multipart/form-data" class="formulario">
    <?= csrf_campo() ?>
    <input type="hidden" name="accion" value="guardar">
    <input type="hidden" name="id" value="<?= (int)($editando['id'] ?? 0) ?>">

    <fieldset>
        <legend><?= $editando ? 'Editar atestado' : 'Agregar atestado' ?></legend>
        <label>Título, curso o certificación *
            <input type="text" name="titulo" maxlength="160" required value="<?= e($editando['titulo'] ?? '') ?>">
        </label>
        <label>Institución *
            <input type="text" name="institucion" maxlength="160" required value="<?= e($editando['institucion'] ?? '') ?>">
        </label>
        <label>Año *
            <input type="number" name="anio" min="1950" max="<?= date('Y') + 5 ?>" required value="<?= e($editando['anio'] ?? date('Y')) ?>">
        </label>
        <label>Descripción
            <textarea name="descripcion" rows="4"><?= e($editando['descripcion'] ?? '') ?></textarea>
        </label>
        <label>Imagen del atestado<?= $editando && $editando['imagen'] ? ' (deja vacío para conservar la actual)' : '' ?>
            <input type="file" name="imagen" accept="image/jpeg,image/png,image/webp,image/gif">
        </label>
    </fieldset>

    <div class="acciones">
        <button type="submit" class="btn"><?= $editando ? 'Guardar cambios' : 'Agregar atestado' ?></button>
        <?php if ($editando): ?><a class="btn btn-claro" href="atestados.php">Cancelar</a><?php endif; ?>
    </div>
</form>

<h2>Atestados registrados</h2>
<?php if (!$atestados): ?>
    <p class="vacio">Aún no has agregado atestados.</p>
<?php else: ?>
<div class="tabla-contenedor">
<table class="tabla">
    <thead><tr><th>Imagen</th><th>Título</th><th>Institución</th><th>Año</th><th>Acciones</th></tr></thead>
    <tbody>
    <?php foreach ($atestados as $a): ?>
        <tr>
            <td><?php if ($a['imagen']): ?><img class="mini" src="../uploads/<?= e($a['imagen']) ?>" alt=""><?php else: ?>—<?php endif; ?></td>
            <td><?= e($a['titulo']) ?></td>
            <td><?= e($a['institucion']) ?></td>
            <td><?= e($a['anio']) ?></td>
            <td class="celda-acciones">
                <a class="btn btn-claro btn-chico" href="?editar=<?= (int)$a['id'] ?>">Editar</a>
                <form method="post" onsubmit="return confirm('¿Eliminar este atestado? No se puede deshacer.');">
                    <?= csrf_campo() ?>
                    <input type="hidden" name="accion" value="eliminar">
                    <input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
                    <button type="submit" class="btn btn-peligro btn-chico">Eliminar</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php endif; ?>
<?php require __DIR__ . '/../includes/admin_footer.php'; ?>
