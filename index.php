<?php
/**
 * Multi Cell Assistência Técnica
 * Front Controller
 *
 * Fluxo:
 *  1. Checa se o sistema está instalado (config + lock).
 *  2. Se não, redireciona para /install.php (a menos que já seja ele).
 *  3. Carrega autoload, config, sessão e inicia o Router.
 */

declare(strict_types=1);

define('APP_ROOT', __DIR__);
define('APP_DIR', APP_ROOT . '/app');
define('CONFIG_DIR', APP_ROOT . '/config');
define('STORAGE_DIR', APP_ROOT . '/storage');
define('UPLOADS_DIR', APP_ROOT . '/uploads');
define('APP_START', microtime(true));

// Checa instalação. Lock file + config real devem existir.
$configFile = CONFIG_DIR . '/config.php';
$lockFile   = STORAGE_DIR . '/install.lock';

if (!is_file($configFile) || !is_file($lockFile)) {
    // Não instalado: direciona ao instalador
    if (PHP_SAPI !== 'cli') {
        header('Location: /install.php');
    }
    exit;
}

// Autoload PSR-4 próprio para App\
require APP_DIR . '/Core/Autoload.php';

// Carrega configuração real
require $configFile;

// Helpers globais
require APP_DIR . '/Core/Helpers.php';
require APP_DIR . '/Views/partials/public/icons.php';

// Modo produção: suprime erros da tela, registra no log
if (defined('APP_ENV') && APP_ENV === 'production') {
    ini_set('display_errors', '0');
    ini_set('display_startup_errors', '0');
    error_reporting(E_ALL);
    ini_set('log_errors', '1');
    ini_set('error_log', STORAGE_DIR . '/logs/php_errors.log');
} else {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}

// Timezone padrão
date_default_timezone_set(defined('APP_TIMEZONE') ? APP_TIMEZONE : 'America/Cuiaba');

// Inicia sessão segura
\App\Core\Session::start();

// Handler global de exceções
set_exception_handler(function (\Throwable $e) {
    error_log('[Unhandled] ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    http_response_code(500);
    if (defined('APP_ENV') && APP_ENV !== 'production') {
        echo '<pre style="font:14px monospace;padding:20px;background:#111;color:#eee;">';
        echo htmlspecialchars((string) $e);
        echo '</pre>';
    } else {
        echo '<h1>Erro interno</h1><p>Tente novamente em instantes.</p>';
    }
});

// Carrega rotas e despacha
$router = new \App\Core\Router();
require APP_DIR . '/routes.php';
$router->dispatch($_GET['url'] ?? '/');
