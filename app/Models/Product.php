<?php
namespace App\Models;

use App\Core\Database;

final class Product
{
    public static function active(?string $category = null): array
    {
        if ($category) {
            return Database::fetchAll(
                'SELECT * FROM products WHERE is_active = 1 AND category = :c
                 ORDER BY sort_order ASC, id ASC',
                ['c' => $category]
            );
        }
        return Database::fetchAll(
            'SELECT * FROM products WHERE is_active = 1 ORDER BY sort_order ASC, id ASC'
        );
    }

    public static function featured(int $limit = 8): array
    {
        return Database::fetchAll(
            'SELECT * FROM products WHERE is_active = 1 AND is_featured = 1
             ORDER BY sort_order ASC, id ASC LIMIT ' . (int) $limit
        );
    }

    public static function findBySlug(string $slug): ?array
    {
        return Database::fetch(
            'SELECT * FROM products WHERE slug = :s AND is_active = 1 LIMIT 1',
            ['s' => $slug]
        );
    }

    /** @return string[] */
    public static function categories(): array
    {
        $rows = Database::fetchAll(
            'SELECT DISTINCT category FROM products
             WHERE is_active = 1 AND category IS NOT NULL AND category <> ""
             ORDER BY category ASC'
        );
        return array_column($rows, 'category');
    }
}
