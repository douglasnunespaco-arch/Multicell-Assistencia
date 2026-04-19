<?php
namespace App\Core;

/**
 * Flash — mensagens one-shot (success/error/warning/info).
 */
final class Flash
{
    public static function add(string $type, string $message): void
    {
        $_SESSION['_flash'][] = ['type' => $type, 'message' => $message];
    }

    public static function success(string $m): void { self::add('success', $m); }
    public static function error(string $m): void   { self::add('error', $m); }
    public static function warning(string $m): void { self::add('warning', $m); }
    public static function info(string $m): void    { self::add('info', $m); }

    /** Retorna e limpa. */
    public static function pull(): array
    {
        $out = $_SESSION['_flash'] ?? [];
        unset($_SESSION['_flash']);
        return $out;
    }
}
