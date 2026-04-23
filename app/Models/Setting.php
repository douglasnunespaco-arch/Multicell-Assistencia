<?php
namespace App\Models;

use App\Core\Database;

final class Setting
{
    /** @return array<string,string> */
    public static function all(bool $fresh = false): array
    {
        static $cache = null;
        if ($cache !== null && !$fresh) return $cache;
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

    /** Insere ou atualiza um setting (upsert manual). */
    public static function set(string $key, ?string $value): void
    {
        $existing = Database::fetch(
            'SELECT id FROM settings WHERE setting_key = :k',
            ['k' => $key]
        );
        if ($existing) {
            Database::update('settings', ['setting_value' => (string) $value], ['id' => (int) $existing['id']]);
        } else {
            Database::insert('settings', ['setting_key' => $key, 'setting_value' => (string) $value]);
        }
        self::all(true); // limpa cache
    }
}
