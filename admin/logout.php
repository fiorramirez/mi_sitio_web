<?php
require_once __DIR__ . '/../includes/config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_validar();
    $_SESSION = [];
    session_destroy();
}
header('Location: login.php');
exit;
