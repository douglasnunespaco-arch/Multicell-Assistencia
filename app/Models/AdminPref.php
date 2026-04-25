<?php
namespace App\Models;

use App\Core\Database;

/**
 * AdminPref — preferências por usuário admin, armazenadas como linhas
 * no `settings` keyed por `admin_pref_{key}_user_{id}`. Zero schema change.
 *
 * Hoje a única chave usada é `theme`, mas é trivial estender (ex.: locale,
 * sidebar_collapsed, dashboard_layout) sem alterar o banco.
 */
final class AdminPref
{
    private const ALLOWED_THEMES = ['dark', 'light', 'auto'];

    private static function key(string $name, int $userId): string
    {
        return 'admin_pref_' . $name . '_user_' . $userId;
    }

    public static function getTheme(int $userId, string $default = 'auto'): string
    {
        $v = (string) Setting::get(self::key('theme', $userId), $default);
        return in_array($v, self::ALLOWED_THEMES, true) ? $v : $default;
    }

    public static function setTheme(int $userId, string $value): bool
    {
        if (!in_array($value, self::ALLOWED_THEMES, true)) return false;
        Setting::set(self::key('theme', $userId), $value);
        return true;
    }
}
