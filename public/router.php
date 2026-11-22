<?php

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/');

if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    $ext = strtolower(pathinfo($uri, PATHINFO_EXTENSION));
    if (in_array($ext, ['css', 'js', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'ico', 'svg', 'mp4', 'webm'])) {
        header("Cache-Control: public, max-age=2592000, no-transform");
    }
    return false;
}

require __DIR__ . '/index.php';
