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

    public static function all(): array
    {
        return Database::fetchAll('SELECT * FROM promotions ORDER BY sort_order ASC, id ASC');
    }
    public static function find(int $id): ?array
    {
        return Database::fetch('SELECT * FROM promotions WHERE id = :id', ['id' => $id]);
    }
    public static function create(array $data): int
    {
        return Database::insert('promotions', $data);
    }
    public static function update(int $id, array $data): int
    {
        return Database::update('promotions', $data, ['id' => $id]);
    }
    public static function delete(int $id): void
    {
        Database::query('DELETE FROM promotions WHERE id = :id', ['id' => $id]);
    }
}
