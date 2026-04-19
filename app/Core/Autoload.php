<?php
/**
 * Autoload PSR-4 minimalista para o namespace App\
 * Mapeia App\Foo\Bar -> /app/Foo/Bar.php
 */

spl_autoload_register(function (string $class): void {
    $prefix  = 'App\\';
    $baseDir = APP_DIR . '/';

    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }
    $relative = substr($class, strlen($prefix));
    $file = $baseDir . str_replace('\\', '/', $relative) . '.php';
    if (is_file($file)) {
        require $file;
    }
});
