<?php
namespace App\Models;

use App\Core\Database;

final class AboutBlock
{
    public static function active(): array
    {
        return Database::fetchAll(
            'SELECT * FROM about_blocks WHERE is_active = 1 ORDER BY sort_order ASC, id ASC'
        );
    }

    public static function all(): array
    {
        return Database::fetchAll('SELECT * FROM about_blocks ORDER BY sort_order ASC, id ASC');
    }

    public static function find(int $id): ?array
    {
        return Database::fetch('SELECT * FROM about_blocks WHERE id = :id', ['id' => $id]);
    }

    public static function create(array $data): int
    {
        return Database::insert('about_blocks', $data);
    }

    public static function update(int $id, array $data): int
    {
        return Database::update('about_blocks', $data, ['id' => $id]);
    }

    public static function delete(int $id): void
    {
        Database::query('DELETE FROM about_blocks WHERE id = :id', ['id' => $id]);
    }
}
