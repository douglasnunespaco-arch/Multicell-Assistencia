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
}
