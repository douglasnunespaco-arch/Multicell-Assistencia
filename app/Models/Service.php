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

    public static function all(): array
    {
        return Database::fetchAll('SELECT * FROM services ORDER BY sort_order ASC, id ASC');
    }
    public static function find(int $id): ?array
    {
        return Database::fetch('SELECT * FROM services WHERE id = :id', ['id' => $id]);
    }
    public static function create(array $data): int
    {
        return Database::insert('services', $data);
    }
    public static function update(int $id, array $data): int
    {
        return Database::update('services', $data, ['id' => $id]);
    }
    public static function delete(int $id): void
    {
        Database::query('DELETE FROM services WHERE id = :id', ['id' => $id]);
    }
}
