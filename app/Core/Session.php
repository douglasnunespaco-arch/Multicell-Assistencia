<?php
namespace App\Core;

/**
 * Session — inicialização segura da sessão PHP.
 */
final class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }
        $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

        session_name('MC_SID');
        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'domain'   => '',
            'secure'   => $secure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_start();

        // Fingerprint leve para dificultar session hijacking
        $fp = hash('sha256', ($_SERVER['HTTP_USER_AGENT'] ?? '') . '|' . APP_KEY);
        if (!isset($_SESSION['_fp'])) {
            $_SESSION['_fp'] = $fp;
        } elseif ($_SESSION['_fp'] !== $fp) {
            session_unset();
            session_regenerate_id(true);
            $_SESSION['_fp'] = $fp;
        }
    }

    public static function regenerate(): void
    {
        session_regenerate_id(true);
    }

    public static function get(string $k, $default = null)
    {
        return $_SESSION[$k] ?? $default;
    }

    public static function set(string $k, $v): void
    {
        $_SESSION[$k] = $v;
    }

    public static function forget(string $k): void
    {
        unset($_SESSION[$k]);
    }

    public static function destroy(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
    }

    /** ID único da sessão (para analytics — hash, não o session_id real) */
    public static function sid(): string
    {
        if (!isset($_SESSION['_tid'])) {
            $_SESSION['_tid'] = bin2hex(random_bytes(16));
        }
        return $_SESSION['_tid'];
    }
}
