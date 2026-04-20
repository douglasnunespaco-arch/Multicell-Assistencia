<?php
namespace App\Models;

use App\Core\Database;

final class Testimonial
{
    public static function active(int $limit = 8): array
    {
        return Database::fetchAll(
            'SELECT * FROM testimonials WHERE is_active = 1
             ORDER BY sort_order ASC, id ASC LIMIT ' . (int) $limit
        );
    }
}
