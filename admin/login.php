<?php
require_once __DIR__ . '/../includes/config.php';

if (!empty($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_validar();
    $usuario  = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT id, password_hash FROM usuarios WHERE usuario = ?');
    $stmt->execute([$usuario]);
    $fila = $stmt->fetch();

    if ($fila && password_verify($password, $fila['password_hash'])) {
        session_regenerate_id(true);          // evita robo de sesión
        $_SESSION['admin_id'] = $fila['id'];
        header('Location: index.php');
        exit;
    }
    sleep(1);                                  
    $error = 'Usuario o contraseña incorrectos.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Iniciar sesión · Administración</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="pagina-login">
    <form class="tarjeta-login" method="post" autocomplete="on">
        <h1>Iniciar sesión</h1>
        <?php if ($error): ?><div class="aviso aviso-error" role="alert"><?= e($error) ?></div><?php endif; ?>
        <?= csrf_campo() ?>
        <label>Usuario
            <input type="text" name="usuario" required autofocus autocomplete="username">
        </label>
        <label>Contraseña
            <input type="password" name="password" required autocomplete="current-password">
        </label>
        <button type="submit" class="btn">Entrar</button>
        <p class="ayuda"><a href="../index.php">Volver al sitio</a></p>
    </form>
</body>
</html>
