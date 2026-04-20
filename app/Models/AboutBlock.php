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
}
