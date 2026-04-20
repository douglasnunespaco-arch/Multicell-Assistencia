<?php
/**
 * PHP built-in server router — APENAS para ambiente de preview Emergent.
 * Em produção (Hostinger/Apache), o .htaccess cuida do rewrite; este arquivo não é usado.
 */
$uri  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = '/' . ltrim((string) $uri, '/');

// Assets estáticos (css, js, img, fonts) — deixa o servidor embutido servir
if ($path !== '/' && is_file(__DIR__ . $path) && !preg_match('#\.php$#', $path)) {
    return false;
}

// install.php (se existir) pode rodar direto
if ($path === '/install.php' && is_file(__DIR__ . '/install.php')) {
    require __DIR__ . '/install.php';
    return true;
}

// Tudo mais passa pelo front controller com ?url=
$_GET['url'] = $path;
$_SERVER['SCRIPT_NAME'] = '/index.php';
require __DIR__ . '/index.php';
