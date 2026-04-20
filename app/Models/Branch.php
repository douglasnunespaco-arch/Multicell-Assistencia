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
}
