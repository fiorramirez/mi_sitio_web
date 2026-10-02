<?php
// ============================================
//  Funciones de ayuda
// ============================================

/** Escapa texto para mostrarlo de forma segura en HTML (evita XSS). */
function e($texto): string {
    return htmlspecialchars((string)$texto, ENT_QUOTES, 'UTF-8');
}

/** Convierte "PHP, HTML, CSS" en un arreglo limpio. */
function lista_desde_texto(?string $texto): array {
    if (!$texto) return [];
    return array_values(array_filter(array_map('trim', explode(',', $texto))));
}

/* ---------- Mensajes temporales (flash) ---------- */
function flash(string $tipo, string $mensaje): void {
    $_SESSION['flash'] = ['tipo' => $tipo, 'mensaje' => $mensaje];
}
function mostrar_flash(): void {
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        echo '<div class="aviso aviso-' . e($f['tipo']) . '" role="status">' . e($f['mensaje']) . '</div>';
    }
}

/* ---------- Protección CSRF en formularios ---------- */
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}
function csrf_campo(): string {
    return '<input type="hidden" name="csrf" value="' . csrf_token() . '">';
}
function csrf_validar(): void {
    $enviado = $_POST['csrf'] ?? '';
    if (!hash_equals($_SESSION['csrf'] ?? '', $enviado)) {
        http_response_code(403);
        die('Solicitud no válida. Vuelve atrás y recarga la página.');
    }
}

/* ---------- Sesión del administrador ---------- */
function exigir_login(): void {
    if (empty($_SESSION['admin_id'])) {
        header('Location: login.php');
        exit;
    }
}

/* ---------- Subida de imágenes ---------- */
/**
 * Sube una imagen desde $_FILES[$campo] a /uploads.
 * Devuelve el nombre del archivo guardado, null si no se envió archivo,
 * o lanza una excepción con un mensaje si hay un problema.
 */
function subir_imagen(string $campo): ?string {
    if (!isset($_FILES[$campo]) || $_FILES[$campo]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    $archivo = $_FILES[$campo];

    if ($archivo['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Error al subir la imagen (código ' . $archivo['error'] . ').');
    }
    if ($archivo['size'] > 5 * 1024 * 1024) {
        throw new RuntimeException('La imagen supera el máximo de 5 MB.');
    }

    // Verificamos el tipo REAL del archivo, no solo su extensión
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($archivo['tmp_name']);
    $permitidos = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
    ];
    if (!isset($permitidos[$mime])) {
        throw new RuntimeException('Formato no permitido. Usa JPG, PNG, WEBP o GIF.');
    }

    $nombre  = bin2hex(random_bytes(12)) . '.' . $permitidos[$mime];
    $destino = __DIR__ . '/../uploads/' . $nombre;
    if (!move_uploaded_file($archivo['tmp_name'], $destino)) {
        throw new RuntimeException('No se pudo guardar la imagen en la carpeta uploads/.');
    }
    return $nombre;
}

/** Borra una imagen de /uploads (si existe). */
function borrar_imagen(?string $nombre): void {
    if (!$nombre) return;
    $ruta = __DIR__ . '/../uploads/' . basename($nombre);
    if (is_file($ruta)) {
        unlink($ruta);
    }
}

/* ---------- Validación de enlaces (redes sociales) ---------- */
/** Devuelve true si el texto está vacío o es una URL http/https válida. */
function url_valida(string $url): bool {
    if ($url === '') return true;
    if (!filter_var($url, FILTER_VALIDATE_URL)) return false;
    $esquema = strtolower(parse_url($url, PHP_URL_SCHEME) ?? '');
    return in_array($esquema, ['http', 'https'], true);
}
