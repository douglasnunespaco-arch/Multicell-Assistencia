<?php
namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Database;

/**
 * AchievementsApiController — endpoint JSON consumido pelo admin.js
 * para mostrar Notifications API quando um novo recorde é detectado.
 *
 * Mantém a checagem barata: 2 queries pequenas, 1 minuto de cache na sessão.
 */
final class AchievementsApiController
{
    public function status(): string
    {
        Auth::requireLogin();
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');

        $in = "'whatsapp_click','cta_click','phone_click','map_click','promotion_click','service_click','product_click'";

        // Recorde semanal: últimos 7 dias > melhor semana ISO anterior
        $cur7 = (int) (Database::fetch(
            "SELECT COUNT(*) c FROM analytics_events
             WHERE event_type IN ($in) AND created_at >= (NOW() - INTERVAL 7 DAY)"
        )['c'] ?? 0);
        $bestWeek = (int) (Database::fetch(
            "SELECT COALESCE(MAX(c),0) c FROM (
                SELECT COUNT(*) c FROM analytics_events
                WHERE event_type IN ($in)
                  AND YEARWEEK(created_at, 1) < YEARWEEK(NOW(), 1)
                GROUP BY YEARWEEK(created_at, 1)) t"
        )['c'] ?? 0);

        return json_encode([
            'ok'           => true,
            'record_week'  => $cur7 > 0 && $cur7 > $bestWeek,
            'cur_week'     => $cur7,
            'best_week'    => $bestWeek,
            'sig'          => 'w-' . date('oW') . '-' . $cur7, // assinatura para evitar disparos duplicados
            'ts'           => time(),
        ]);
    }
}
