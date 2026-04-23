<?php
namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Auth;
use App\Core\Database;
use App\Models\Setting;

/**
 * DashboardController — visão geral + rankings de cliques por período (3D).
 *
 * Rankings agrupados por COALESCE(source, CONCAT(ref_type,':',ref_id)).
 * Metas lidas de settings com defaults sensatos para loja média.
 */
final class DashboardController
{
    /** Eventos considerados como "clique" (exclui page_view e submits). */
    private const CLICK_EVENTS = [
        'whatsapp_click','cta_click','phone_click','map_click',
        'promotion_click','service_click','product_click',
    ];

    /** Metas default (fallback quando a chave em settings não existe). */
    private const GOAL_DEFAULTS = [
        'day'   => 20,
        'week'  => 100,
        'month' => 400,
        'year'  => 4500,
    ];

    public function index(): string
    {
        Auth::requireLogin();

        $today = date('Y-m-d');
        $weekAgo = date('Y-m-d', strtotime('-7 days'));

        $stats = [
            'leads_total'      => (int) Database::fetch('SELECT COUNT(*) AS c FROM lead_reservations')['c'],
            'leads_new'        => (int) Database::fetch("SELECT COUNT(*) AS c FROM lead_reservations WHERE status = 'novo'")['c'],
            'leads_week'       => (int) Database::fetch(
                "SELECT COUNT(*) AS c FROM lead_reservations WHERE created_at >= :d",
                [':d' => $weekAgo . ' 00:00:00']
            )['c'],
            'leads_today'      => (int) Database::fetch(
                "SELECT COUNT(*) AS c FROM lead_reservations WHERE created_at >= :d",
                [':d' => $today . ' 00:00:00']
            )['c'],
            'pageviews_today'  => (int) Database::fetch(
                "SELECT COUNT(*) AS c FROM analytics_events WHERE event_type = 'page_view' AND created_at >= :d",
                [':d' => $today . ' 00:00:00']
            )['c'],
            'wa_clicks_today'  => (int) Database::fetch(
                "SELECT COUNT(*) AS c FROM analytics_events WHERE event_type = 'whatsapp_click' AND created_at >= :d",
                [':d' => $today . ' 00:00:00']
            )['c'],
            'reservations_today' => (int) Database::fetch(
                "SELECT COUNT(*) AS c FROM analytics_events WHERE event_type = 'reservation_submit' AND created_at >= :d",
                [':d' => $today . ' 00:00:00']
            )['c'],
        ];

        $recent = Database::fetchAll(
            "SELECT id, customer_name, phone, service_type, status, created_at
             FROM lead_reservations ORDER BY created_at DESC LIMIT 5"
        );

        // Rankings por período
        $now = new \DateTimeImmutable('now');
        $periods = [
            'day'   => ['label' => 'Hoje',       'from' => $now->setTime(0,0,0)],
            'week'  => ['label' => 'Semana',     'from' => $now->modify('-6 days')->setTime(0,0,0)],
            'month' => ['label' => 'Mês',        'from' => $now->modify('first day of this month')->setTime(0,0,0)],
            'year'  => ['label' => 'Ano',        'from' => $now->modify('first day of January this year')->setTime(0,0,0)],
        ];

        $rankings = [];
        foreach ($periods as $key => $p) {
            $from = $p['from']->format('Y-m-d H:i:s');
            $rankings[$key] = [
                'label'  => $p['label'],
                'total'  => $this->totalClicks($from),
                'goal'   => (int) Setting::get('goal_clicks_' . $key, (string) self::GOAL_DEFAULTS[$key]),
                'top'    => $this->topClicks($from),
            ];
        }

        return View::render('admin/dashboard', [
            'page_title' => 'Painel • Admin Multi Cell',
            'stats'      => $stats,
            'recent'     => $recent,
            'rankings'   => $rankings,
        ], 'admin');
    }

    private function totalClicks(string $from): int
    {
        $in = "'" . implode("','", self::CLICK_EVENTS) . "'";
        $row = Database::fetch(
            "SELECT COUNT(*) AS c FROM analytics_events
             WHERE event_type IN ($in) AND created_at >= :d",
            [':d' => $from]
        );
        return (int) ($row['c'] ?? 0);
    }

    /** Top 5 por source (com fallback ref_type:ref_id). */
    private function topClicks(string $from): array
    {
        $in = "'" . implode("','", self::CLICK_EVENTS) . "'";
        return Database::fetchAll(
            "SELECT
                COALESCE(NULLIF(source,''), CONCAT(COALESCE(ref_type,'page'),':',COALESCE(ref_id,0))) AS bucket,
                COUNT(*) AS c
             FROM analytics_events
             WHERE event_type IN ($in) AND created_at >= :d
             GROUP BY bucket
             ORDER BY c DESC
             LIMIT 5",
            [':d' => $from]
        );
    }
}
