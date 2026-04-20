<?php
namespace App\Models;

use App\Core\Database;

final class Setting
{
    /** @return array<string,string> */
    public static function all(): array
    {
        static $cache = null;
        if ($cache !== null) return $cache;
        $cache = [];
        foreach (Database::fetchAll('SELECT setting_key, setting_value FROM settings') as $r) {
            $cache[$r['setting_key']] = $r['setting_value'];
        }
        return $cache;
    }

    public static function get(string $key, $default = null)
    {
        return self::all()[$key] ?? $default;
    }
}
