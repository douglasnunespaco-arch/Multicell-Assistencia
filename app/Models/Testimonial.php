<?php
namespace App\Models;

use App\Core\Database;

final class Testimonial
{
    public static function active(int $limit = 8): array
    {
        // Regra pública: apenas comentários positivos (rating >= 5, equivalente a ≥ 4.5 em inteiros)
        // e de origem social pública (google/instagram/facebook/tiktok). Schema NÃO é alterado.
        return Database::fetchAll(
            "SELECT * FROM testimonials
             WHERE is_active = 1
               AND rating >= 5
               AND source IN ('google','instagram','facebook','tiktok')
             ORDER BY sort_order ASC, id ASC LIMIT " . (int) $limit
        );
    }

    public static function all(): array
    {
        return Database::fetchAll('SELECT * FROM testimonials ORDER BY sort_order ASC, id ASC');
    }
    public static function find(int $id): ?array
    {
        return Database::fetch('SELECT * FROM testimonials WHERE id = :id', ['id' => $id]);
    }
    public static function create(array $data): int
    {
        return Database::insert('testimonials', $data);
    }
    public static function update(int $id, array $data): int
    {
        return Database::update('testimonials', $data, ['id' => $id]);
    }
    public static function delete(int $id): void
    {
        Database::query('DELETE FROM testimonials WHERE id = :id', ['id' => $id]);
    }
}
