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
        $prevEnd   = date('Y-m-d 23:59:59', strtotime("-{$range} days"));
        $prevStart = date('Y-m-d 00:00:00', strtotime("-" . (($range * 2) - 1) . " days"));

        // KPIs (período corrente)
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

        // KPIs período anterior (em 1 query agregada · placeholders distintos)
        $prevAgg = Database::fetch(
            "SELECT
                (SELECT COUNT(*) FROM analytics_events
                 WHERE event_type IN ($clickIn) AND created_at BETWEEN :a1 AND :b1) AS clicks,
                (SELECT COUNT(*) FROM analytics_events
                 WHERE event_type = 'page_view' AND created_at BETWEEN :a2 AND :b2) AS pageviews,
                (SELECT COUNT(DISTINCT session_id) FROM analytics_events
                 WHERE session_id IS NOT NULL AND created_at BETWEEN :a3 AND :b3) AS sessions,
                (SELECT COUNT(*) FROM lead_reservations
                 WHERE created_at BETWEEN :a4 AND :b4) AS leads",
            [
                ':a1' => $prevStart, ':b1' => $prevEnd,
                ':a2' => $prevStart, ':b2' => $prevEnd,
                ':a3' => $prevStart, ':b3' => $prevEnd,
                ':a4' => $prevStart, ':b4' => $prevEnd,
            ]
        ) ?: ['clicks'=>0,'pageviews'=>0,'sessions'=>0,'leads'=>0];
        $prevConv = (int) $prevAgg['clicks'] > 0
            ? round(((int)$prevAgg['leads'] / (int)$prevAgg['clicks']) * 100, 1)
            : 0;
        $pct = function (int $cur, int $prev): ?int {
            if ($prev <= 0) return $cur > 0 ? 100 : null;
            return (int) round((($cur - $prev) / $prev) * 100);
        };
        $kpisPrev = [
            'clicks'    => (int) $prevAgg['clicks'],
            'pageviews' => (int) $prevAgg['pageviews'],
            'sessions'  => (int) $prevAgg['sessions'],
            'leads'     => (int) $prevAgg['leads'],
            'conv_rate' => $prevConv,
        ];
        $kpisDelta = [
            'clicks'    => $pct($kpis['clicks'],    $kpisPrev['clicks']),
            'pageviews' => $pct($kpis['pageviews'], $kpisPrev['pageviews']),
            'sessions'  => $pct($kpis['sessions'],  $kpisPrev['sessions']),
            'leads'     => $pct($kpis['leads'],     $kpisPrev['leads']),
            'conv_rate' => $kpis['conv_rate'] - $kpisPrev['conv_rate'], // pontos percentuais
        ];

        // Alerta de queda · só pra range 7 (sinal forte) e 30 (revisão mensal)
        $alert = null;
        $deltaCli = $kpisDelta['clicks'];
        if ($deltaCli !== null && $deltaCli <= -25 && $kpisPrev['clicks'] >= 10) {
            $alert = [
                'type' => 'drop',
                'msg'  => "Cliques caíram {$deltaCli}% vs os {$range} dias anteriores. Vale revisar slides, promo do hero e fontes de tráfego.",
            ];
        } elseif ($deltaCli !== null && $deltaCli >= 50) {
            $alert = [
                'type' => 'spike',
                'msg'  => "Bom sinal! Cliques subiram +{$deltaCli}% vs os {$range} dias anteriores. Aproveita pra aumentar a meta diária.",
            ];
        }

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

        // Heatmap · cliques por (dia da semana × hora)
        // DAYOFWEEK: 1=Domingo .. 7=Sábado · normalizamos pra 0..6 com seg=0
        $hm = Database::fetchAll(
            "SELECT MOD(DAYOFWEEK(created_at) + 5, 7) AS dow, HOUR(created_at) AS h, COUNT(*) AS c
             FROM analytics_events
             WHERE event_type IN ($clickIn) AND created_at >= :s
             GROUP BY dow, h",
            [':s' => $start]
        );
        $heatmap = array_fill(0, 7, array_fill(0, 24, 0));
        $hmMax = 0;
        foreach ($hm as $r) {
            $d = (int) $r['dow']; $h = (int) $r['h']; $c = (int) $r['c'];
            if ($d < 0 || $d > 6 || $h < 0 || $h > 23) continue;
            $heatmap[$d][$h] = $c;
            if ($c > $hmMax) $hmMax = $c;
        }

        return View::render('admin/analytics/index', [
            'page_title'  => 'Analytics · Multi Cell',
            'range'       => $range,
            'kpis'        => $kpis,
            'kpis_prev'   => $kpisPrev,
            'kpis_delta'  => $kpisDelta,
            'alert'       => $alert,
            'timeline'    => $timeline,
            'by_type'     => $byType,
            'sources'     => $sources,
            'pages'       => $pages,
            'items'       => $items,
            'heatmap'     => $heatmap,
            'heatmap_max' => $hmMax,
        ], 'admin');
    }

    /**
     * Drill-down · todos os eventos de um dia específico (limit 500).
     * Rota: /admin/analytics/day/:date (YYYY-MM-DD).
     */
    public function day(): string
    {
        Auth::requireLogin();
        $date = (string) ($_GET['date'] ?? '');
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            $date = date('Y-m-d');
        }
        $start = $date . ' 00:00:00';
        $end   = $date . ' 23:59:59';

        $events = Database::fetchAll(
            "SELECT e.created_at, e.event_type, e.page_path, e.ref_type, e.ref_id, e.source, e.session_id,
                    COALESCE(p.name, s.name, pr.title, '') AS item_title
             FROM analytics_events e
             LEFT JOIN products   p  ON e.ref_type='product'   AND p.id  = e.ref_id
             LEFT JOIN services   s  ON e.ref_type='service'   AND s.id  = e.ref_id
             LEFT JOIN promotions pr ON e.ref_type='promotion' AND pr.id = e.ref_id
             WHERE e.created_at BETWEEN :a AND :b
             ORDER BY e.created_at DESC
             LIMIT 500",
            [':a' => $start, ':b' => $end]
        );

        $clickIn = "'" . implode("','", self::CLICK_EVENTS) . "'";
        $totals = Database::fetch(
            "SELECT
                (SELECT COUNT(*) FROM analytics_events
                 WHERE event_type IN ($clickIn) AND created_at BETWEEN :a1 AND :b1) AS clicks,
                (SELECT COUNT(*) FROM analytics_events
                 WHERE event_type = 'page_view' AND created_at BETWEEN :a2 AND :b2) AS pageviews,
                (SELECT COUNT(DISTINCT session_id) FROM analytics_events
                 WHERE session_id IS NOT NULL AND created_at BETWEEN :a3 AND :b3) AS sessions",
            [
                ':a1' => $start, ':b1' => $end,
                ':a2' => $start, ':b2' => $end,
                ':a3' => $start, ':b3' => $end,
            ]
        ) ?: ['clicks'=>0,'pageviews'=>0,'sessions'=>0];

        return View::render('admin/analytics/day', [
            'page_title' => 'Analytics · ' . $date,
            'date'       => $date,
            'events'     => $events,
            'totals'     => $totals,
        ], 'admin');
    }

    /**
     * Export CSV — eventos brutos do período (limitado a 10k linhas pra
     * evitar timeout em hospedagem compartilhada). Charset BOM UTF-8 pra
     * abrir limpo no Excel pt-BR.
     */
    public function exportCsv(): void
    {
        Auth::requireLogin();
        $range = (int) ($_GET['range'] ?? 30);
        if (!in_array($range, self::ALLOWED_RANGES, true)) $range = 30;
        $start = date('Y-m-d 00:00:00', strtotime("-" . ($range - 1) . " days"));

        $rows = Database::fetchAll(
            "SELECT
                e.created_at        AS data_hora,
                e.event_type        AS evento,
                e.page_path         AS pagina,
                e.ref_type          AS tipo_ref,
                COALESCE(p.name, s.name, pr.title, '')          AS item,
                COALESCE(NULLIF(e.source,''), '(direto)')        AS fonte,
                e.session_id        AS sessao
             FROM analytics_events e
             LEFT JOIN products   p  ON e.ref_type='product'   AND p.id  = e.ref_id
             LEFT JOIN services   s  ON e.ref_type='service'   AND s.id  = e.ref_id
             LEFT JOIN promotions pr ON e.ref_type='promotion' AND pr.id = e.ref_id
             WHERE e.created_at >= :s
             ORDER BY e.created_at DESC
             LIMIT 10000",
            [':s' => $start]
        );

        $filename = 'multicell-analytics-' . $range . 'd-' . date('Ymd-His') . '.csv';
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-store');

        $out = fopen('php://output', 'w');
        // BOM UTF-8 (Excel pt-BR)
        fwrite($out, "\xEF\xBB\xBF");
        fputcsv($out, ['data_hora','evento','pagina','tipo_ref','item','fonte','sessao'], ';');
        foreach ($rows as $r) {
            fputcsv($out, [
                (string) ($r['data_hora'] ?? ''),
                (string) ($r['evento']    ?? ''),
                (string) ($r['pagina']    ?? ''),
                (string) ($r['tipo_ref']  ?? ''),
                (string) ($r['item']      ?? ''),
                (string) ($r['fonte']     ?? ''),
                (string) ($r['sessao']    ?? ''),
            ], ';');
        }
        fclose($out);
        exit;
    }
}
