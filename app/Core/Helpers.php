<?php
/**
 * Helpers globais usados nas views e controllers.
 */

if (!function_exists('e')) {
    function e($v): string {
        return \App\Core\View::e($v);
    }
}

if (!function_exists('url')) {
    function url(string $path = '/'): string {
        $base = defined('APP_URL') ? rtrim(APP_URL, '/') : '';
        return $base . '/' . ltrim($path, '/');
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string {
        return '/' . ltrim($path, '/');
    }
}

if (!function_exists('old')) {
    function old(string $key, $default = '') {
        return $_SESSION['_old'][$key] ?? $default;
    }
}

if (!function_exists('redirect')) {
    function redirect(string $path): void {
        header('Location: ' . $path);
        exit;
    }
}

if (!function_exists('back')) {
    function back(): void {
        $ref = $_SERVER['HTTP_REFERER'] ?? '/';
        redirect($ref);
    }
}

if (!function_exists('setting')) {
    /**
     * Acesso preguiçoso a settings do banco.
     * Cacheado em request.
     */
    function setting(string $key, $default = null) {
        static $cache = null;
        if ($cache === null) {
            $cache = [];
            try {
                foreach (\App\Core\Database::fetchAll('SELECT setting_key, setting_value FROM settings') as $r) {
                    $cache[$r['setting_key']] = $r['setting_value'];
                }
            } catch (\Throwable $e) {
                // Em instalação ou erro, devolve default
            }
        }
        return $cache[$key] ?? $default;
    }
}

if (!function_exists('slugify')) {
    function slugify(string $v): string {
        $v = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $v) ?: $v;
        $v = preg_replace('/[^a-zA-Z0-9]+/', '-', $v) ?? '';
        return trim(strtolower($v), '-');
    }
}

if (!function_exists('money')) {
    function money($v): string {
        if ($v === null || $v === '') return '';
        return 'R$ ' . number_format((float) $v, 2, ',', '.');
    }
}

if (!function_exists('whatsapp_link')) {
    /**
     * Monta link wa.me a partir do settings.
     * $source é registrado no analytics via rota /go/whatsapp.
     */
    function whatsapp_link(string $source = 'generic', ?string $message = null): string {
        $msg = $message ?? setting('whatsapp_message_template', 'Olá! Tenho interesse em falar com a Multi Cell.');
        return '/go/whatsapp?src=' . urlencode($source) . '&msg=' . urlencode($msg);
    }
}
