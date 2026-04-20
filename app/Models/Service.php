<?php
namespace App\Models;

use App\Core\Database;

final class Service
{
    public static function active(): array
    {
        return Database::fetchAll(
            'SELECT * FROM services WHERE is_active = 1 ORDER BY sort_order ASC, id ASC'
        );
    }

    public static function featured(int $limit = 6): array
    {
        return Database::fetchAll(
            'SELECT * FROM services WHERE is_active = 1 AND is_featured = 1
             ORDER BY sort_order ASC, id ASC LIMIT ' . (int) $limit
        );
    }

    public static function findBySlug(string $slug): ?array
    {
        return Database::fetch(
            'SELECT * FROM services WHERE slug = :s AND is_active = 1 LIMIT 1',
            ['s' => $slug]
        );
    }
}
