<?php
namespace App\Models;

use App\Core\Database;

final class HeroSlide
{
    public const TABLE = 'hero_slides';

    public static function active(): array
    {
        return Database::fetchAll(
            'SELECT * FROM hero_slides WHERE is_active = 1 ORDER BY sort_order ASC, id ASC'
        );
    }

    public static function all(): array
    {
        return Database::fetchAll(
            'SELECT * FROM hero_slides ORDER BY sort_order ASC, id ASC'
        );
    }

    public static function find(int $id): ?array
    {
        return Database::fetch('SELECT * FROM hero_slides WHERE id = :id', ['id' => $id]);
    }

    public static function create(array $data): int
    {
        return Database::insert('hero_slides', $data);
    }

    public static function update(int $id, array $data): int
    {
        return Database::update('hero_slides', $data, ['id' => $id]);
    }

    public static function delete(int $id): void
    {
        Database::query('DELETE FROM hero_slides WHERE id = :id', ['id' => $id]);
    }
}
