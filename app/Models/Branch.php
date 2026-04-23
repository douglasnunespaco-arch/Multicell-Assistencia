<?php
namespace App\Models;

use App\Core\Database;

final class Branch
{
    public static function primary(): ?array
    {
        return Database::fetch(
            'SELECT * FROM branches WHERE is_active = 1 ORDER BY sort_order ASC, id ASC LIMIT 1'
        );
    }

    public static function active(): array
    {
        return Database::fetchAll(
            'SELECT * FROM branches WHERE is_active = 1 ORDER BY sort_order ASC, id ASC'
        );
    }

    public static function all(): array
    {
        return Database::fetchAll('SELECT * FROM branches ORDER BY sort_order ASC, id ASC');
    }

    public static function find(int $id): ?array
    {
        return Database::fetch('SELECT * FROM branches WHERE id = :id', ['id' => $id]);
    }

    public static function create(array $data): int
    {
        return Database::insert('branches', $data);
    }

    public static function update(int $id, array $data): int
    {
        return Database::update('branches', $data, ['id' => $id]);
    }

    public static function delete(int $id): void
    {
        Database::query('DELETE FROM branches WHERE id = :id', ['id' => $id]);
    }
}
