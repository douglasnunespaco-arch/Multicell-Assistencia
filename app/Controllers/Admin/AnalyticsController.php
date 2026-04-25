<?php
namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Database;
use App\Core\View;

/**
 * AnalyticsController — agregações sobre `analytics_events`.
 * Range parametrizável via `?range=7|30|90` (default 30).
 * Tudo bounded por LIMIT/INTERVAL pra rodar bem em qualquer host.
 */
final class AnalyticsController
{
    private const ALLOWED_RANGES = [7, 30, 90];
    private const CLICK_EVENTS   = ['whatsapp_click','cta_click','phone_click','map_click','promotion_click','service_click','product_click'];

    public function index(): string
    {
        Auth::requireLogin();
        $range = (int) ($_GET['range'] ?? 30);
        if (!in_array($range, self::ALLOWED_RANGES, true)) $range = 30;

        $clickIn = "'" . implode("','", self::CLICK_EVENTS) . "'";
        $start   = date('Y-m-d 00:00:00', strtotime("-" . ($range - 1) . " days"));

        // KPIs
        $kpis = [
            'clicks'    => (int) (Database::fetch(
                "SELECT COUNT(*) c FROM analytics_events
                 WHERE event_type IN ($clickIn) AND created_at >= :s",
                [':s' => $start]
            )['c'] ?? 0),
            'pageviews' => (int) (Database::fetch(
                "SELECT COUNT(*) c FROM analytics_events
                 WHERE event_type = 'page_view' AND created_at >= :s",
                [':s' => $start]
            )['c'] ?? 0),
            'sessions'  => (int) (Database::fetch(
                "SELECT COUNT(DISTINCT session_id) c FROM analytics_events
                 WHERE session_id IS NOT NULL AND created_at >= :s",
                [':s' => $start]
            )['c'] ?? 0),
            'leads'     => (int) (Database::fetch(
                "SELECT COUNT(*) c FROM lead_reservations WHERE created_at >= :s",
                [':s' => $start]
            )['c'] ?? 0),
        ];
        $kpis['conv_rate'] = $kpis['clicks'] > 0
            ? round(($kpis['leads'] / max(1, $kpis['clicks'])) * 100, 1)
            : 0;

        // Linha do tempo · cliques por dia
        $rows = Database::fetchAll(
            "SELECT DATE(created_at) d, COUNT(*) c FROM analytics_events
             WHERE event_type IN ($clickIn) AND created_at >= :s
             GROUP BY DATE(created_at)",
            [':s' => $start]
        );
        $map = []; foreach ($rows as $r) $map[(string) $r['d']] = (int) $r['c'];
        $timeline = [];
        for ($i = $range - 1; $i >= 0; $i--) {
            $k = date('Y-m-d', strtotime("-$i days"));
            $timeline[] = ['d' => $k, 'c' => (int) ($map[$k] ?? 0)];
        }

        // Distribuição por tipo de evento (todos, não só cliques)
        $byType = Database::fetchAll(
            "SELECT event_type, COUNT(*) c FROM analytics_events
             WHERE created_at >= :s
             GROUP BY event_type ORDER BY c DESC",
            [':s' => $start]
        );

        // Top fontes (source)
        $sources = Database::fetchAll(
            "SELECT COALESCE(NULLIF(source,''),'(direto)') AS source, COUNT(*) c
             FROM analytics_events
             WHERE event_type IN ($clickIn) AND created_at >= :s
             GROUP BY source ORDER BY c DESC LIMIT 10",
            [':s' => $start]
        );

        // Top páginas
        $pages = Database::fetchAll(
            "SELECT COALESCE(NULLIF(page_path,''),'/') AS page_path, COUNT(*) c
             FROM analytics_events
             WHERE event_type = 'page_view' AND created_at >= :s
             GROUP BY page_path ORDER BY c DESC LIMIT 10",
            [':s' => $start]
        );

        // Top items (produto/serviço/promo)
        $items = Database::fetchAll(
            "SELECT
                e.ref_type AS type, e.ref_id AS id,
                COUNT(*) AS clicks,
                COALESCE(p.name, s.name, pr.title, CONCAT(e.ref_type,':',e.ref_id)) AS title
             FROM analytics_events e
             LEFT JOIN products   p  ON e.ref_type='product'   AND p.id  = e.ref_id
             LEFT JOIN services   s  ON e.ref_type='service'   AND s.id  = e.ref_id
             LEFT JOIN promotions pr ON e.ref_type='promotion' AND pr.id = e.ref_id
             WHERE e.event_type IN ('product_click','service_click','promotion_click')
               AND e.ref_type IN ('product','service','promotion')
               AND e.created_at >= :s
             GROUP BY e.ref_type, e.ref_id
             ORDER BY clicks DESC
             LIMIT 10",
            [':s' => $start]
        );

        return View::render('admin/analytics/index', [
            'page_title' => 'Analytics · Multi Cell',
            'range'      => $range,
            'kpis'       => $kpis,
            'timeline'   => $timeline,
            'by_type'    => $byType,
            'sources'    => $sources,
            'pages'      => $pages,
            'items'      => $items,
        ], 'admin');
    }
}
