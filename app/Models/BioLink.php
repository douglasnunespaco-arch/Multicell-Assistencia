<?php
namespace App\Models;

use App\Core\Database;

final class BioLink
{
    public static function active(): array
    {
        return Database::fetchAll(
            'SELECT * FROM bio_links WHERE is_active = 1 ORDER BY sort_order ASC, id ASC'
        );
    }

    public static function all(): array
    {
        return Database::fetchAll('SELECT * FROM bio_links ORDER BY sort_order ASC, id ASC');
    }

    public static function find(int $id): ?array
    {
        return Database::fetch('SELECT * FROM bio_links WHERE id = :id', ['id' => $id]);
    }

    public static function create(array $data): int
    {
        return Database::insert('bio_links', $data);
    }

    public static function update(int $id, array $data): int
    {
        return Database::update('bio_links', $data, ['id' => $id]);
    }

    public static function delete(int $id): void
    {
        Database::query('DELETE FROM bio_links WHERE id = :id', ['id' => $id]);
    }
}
