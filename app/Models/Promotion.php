<?php
namespace App\Models;

use App\Core\Database;

final class Promotion
{
    /** Promoções ativas cujo período é válido (ou sem datas). */
    public static function active(?int $limit = null): array
    {
        $sql = 'SELECT * FROM promotions
                 WHERE is_active = 1
                   AND (starts_at IS NULL OR starts_at <= CURDATE())
                   AND (ends_at   IS NULL OR ends_at   >= CURDATE())
                 ORDER BY (ends_at IS NULL) ASC, ends_at ASC, sort_order ASC, id ASC';
        if ($limit) {
            $sql .= ' LIMIT ' . (int) $limit;
        }
        return Database::fetchAll($sql);
    }

    public static function findBySlug(string $slug): ?array
    {
        return Database::fetch(
            'SELECT * FROM promotions WHERE slug = :s AND is_active = 1 LIMIT 1',
            ['s' => $slug]
        );
    }
}
