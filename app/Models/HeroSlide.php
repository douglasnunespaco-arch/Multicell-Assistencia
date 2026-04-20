<?php
namespace App\Models;

use App\Core\Database;

final class HeroSlide
{
    public static function active(): array
    {
        return Database::fetchAll(
            'SELECT * FROM hero_slides WHERE is_active = 1 ORDER BY sort_order ASC, id ASC'
        );
    }
}
