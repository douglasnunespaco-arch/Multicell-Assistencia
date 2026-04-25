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
            'page_title'   => 'Painel • Admin Multi Cell',
            'stats'        => $stats,
            'recent'       => $recent,
            'rankings'     => $rankings,
            'achievements' => $this->computeAchievements(),
            'streak'       => $this->computeStreak(),
            'monthly_lead' => $this->computeMonthlyLead(),
            'hot_path'     => $this->computeHotPath(),
            'top_bucket'   => $this->computeTopBucket(),
            'yesterday'    => $this->computeYesterdayRecap(),
        ], 'admin');
    }

    /**
     * Recap de ontem · clicks + leads + top item + meta. Aparece como faixa
     * compacta no topo do dashboard pra reforçar hábito diário. Renderiza
     * só se houver atividade (clicks>0 OU leads>0).
     */
    private function computeYesterdayRecap(): array
    {
        $in = "'" . implode("','", self::CLICK_EVENTS) . "'";
        $start = date('Y-m-d 00:00:00', strtotime('-1 day'));
        $end   = date('Y-m-d 23:59:59', strtotime('-1 day'));
        $startPrev = date('Y-m-d 00:00:00', strtotime('-2 day'));
        $endPrev   = date('Y-m-d 23:59:59', strtotime('-2 day'));

        $clicks = (int) (Database::fetch(
            "SELECT COUNT(*) c FROM analytics_events
             WHERE event_type IN ($in) AND created_at BETWEEN :a AND :b",
            [':a' => $start, ':b' => $end]
        )['c'] ?? 0);

        $leads = (int) (Database::fetch(
            "SELECT COUNT(*) c FROM lead_reservations
             WHERE created_at BETWEEN :a AND :b",
            [':a' => $start, ':b' => $end]
        )['c'] ?? 0);

        // Anteontem (pra delta)
        $prev = Database::fetch(
            "SELECT
                (SELECT COUNT(*) FROM analytics_events
                 WHERE event_type IN ($in) AND created_at BETWEEN :a1 AND :b1) AS clicks,
                (SELECT COUNT(*) FROM lead_reservations
                 WHERE created_at BETWEEN :a2 AND :b2) AS leads",
            [':a1' => $startPrev, ':b1' => $endPrev, ':a2' => $startPrev, ':b2' => $endPrev]
        ) ?: ['clicks' => 0, 'leads' => 0];

        $top = Database::fetch(
            "SELECT COALESCE(p.name, s.name, pr.title, CONCAT(e.ref_type,':',e.ref_id)) AS title
             FROM analytics_events e
             LEFT JOIN products   p  ON e.ref_type='product'   AND p.id  = e.ref_id
             LEFT JOIN services   s  ON e.ref_type='service'   AND s.id  = e.ref_id
             LEFT JOIN promotions pr ON e.ref_type='promotion' AND pr.id = e.ref_id
             WHERE e.event_type IN ('product_click','service_click','promotion_click')
               AND e.ref_type IN ('product','service','promotion')
               AND e.created_at BETWEEN :a AND :b
             GROUP BY e.ref_type, e.ref_id
             ORDER BY COUNT(*) DESC
             LIMIT 1",
            [':a' => $start, ':b' => $end]
        );

        $goal = max(1, (int) Setting::get('goal_clicks_day', '20'));

        // Sparkline · 7 últimos dias de cliques (ordem cronológica)
        $startWeek = date('Y-m-d 00:00:00', strtotime('-6 days'));
        $rows = Database::fetchAll(
            "SELECT DATE(created_at) d, COUNT(*) c FROM analytics_events
             WHERE event_type IN ($in) AND created_at >= :s
             GROUP BY DATE(created_at)",
            [':s' => $startWeek]
        );
        $map = []; foreach ($rows as $r) $map[(string) $r['d']] = (int) $r['c'];
        $spark = [];
        for ($i = 6; $i >= 0; $i--) {
            $k = date('Y-m-d', strtotime("-$i days"));
            $spark[] = (int) ($map[$k] ?? 0);
        }

        return [
            'clicks'        => $clicks,
            'leads'         => $leads,
            'clicks_delta'  => $clicks - (int) ($prev['clicks'] ?? 0),
            'leads_delta'   => $leads - (int) ($prev['leads'] ?? 0),
            'top'           => $top['title'] ?? null,
            'goal'          => $goal,
            'goal_hit'      => $clicks >= $goal,
            'show'          => ($clicks > 0 || $leads > 0),
            'spark'         => $spark,
        ];
    }

    /**
     * Hot path · top 5 itens (produto/serviço/promo) mais clicados nos últimos 7 dias.
     * Faz LEFT JOIN nas 3 tabelas pra resolver o título humano. Bounded por LIMIT.
     */
    private function computeHotPath(): array
    {
        $sql = "SELECT
                    ref_type AS type,
                    ref_id   AS id,
                    COUNT(*) AS clicks,
                    COALESCE(p.name, s.name, pr.title, CONCAT(ref_type,':',ref_id)) AS title,
                    COALESCE(p.slug,  s.slug,  pr.slug,  '') AS slug
                FROM analytics_events e
                LEFT JOIN products   p  ON e.ref_type='product'   AND p.id  = e.ref_id
                LEFT JOIN services   s  ON e.ref_type='service'   AND s.id  = e.ref_id
                LEFT JOIN promotions pr ON e.ref_type='promotion' AND pr.id = e.ref_id
                WHERE e.event_type IN ('product_click','service_click','promotion_click')
                  AND e.ref_type IN ('product','service','promotion')
                  AND e.created_at >= (NOW() - INTERVAL 7 DAY)
                GROUP BY ref_type, ref_id
                ORDER BY clicks DESC
                LIMIT 5";
        return Database::fetchAll($sql);
    }

    /**
     * Top bucket dos últimos 7 dias para personalizar o welcome.
     * Retorna ['title' => string|null, 'type' => string|null, 'clicks' => int].
     */
    private function computeTopBucket(): array
    {
        $rows = $this->computeHotPath();
        if (empty($rows)) return ['title' => null, 'type' => null, 'clicks' => 0];
        return [
            'title'  => (string) $rows[0]['title'],
            'type'   => (string) $rows[0]['type'],
            'clicks' => (int) $rows[0]['clicks'],
        ];
    }

    /**
     * Streak = dias consecutivos (últimos 90) onde o total de cliques diários
     * atingiu/superou a meta diária (`goal_clicks_day`). Retorna [days=>int].
     * Anda de hoje pra trás, para no primeiro dia que não bate.
     */
    private function computeStreak(): array
    {
        $goal = max(1, (int) Setting::get('goal_clicks_day', '20'));
        $in = "'" . implode("','", self::CLICK_EVENTS) . "'";
        $rows = Database::fetchAll(
            "SELECT DATE(created_at) AS d, COUNT(*) AS c
             FROM analytics_events
             WHERE event_type IN ($in)
               AND created_at >= (CURDATE() - INTERVAL 90 DAY)
             GROUP BY DATE(created_at)"
        );
        $map = [];
        foreach ($rows as $r) $map[(string) $r['d']] = (int) $r['c'];

        $days = 0;
        $cursor = new \DateTimeImmutable('today');
        for ($i = 0; $i < 90; $i++) {
            $key = $cursor->format('Y-m-d');
            $count = $map[$key] ?? 0;
            // Permite começar streak hoje OU ontem (caso ainda não tenha cliques hoje).
            if ($i === 0 && $count < $goal) {
                $cursor = $cursor->modify('-1 day'); continue;
            }
            if ($count >= $goal) { $days++; $cursor = $cursor->modify('-1 day'); }
            else break;
        }
        return ['days' => $days, 'goal' => $goal];
    }

    /**
     * Biggest monthly delta = quantos cliques o mês corrente está acima
     * (ou abaixo) do melhor mês histórico anterior. Se positivo, é uma
     * conquista digna de mostrar no welcome.
     */
    private function computeMonthlyLead(): array
    {
        $in = "'" . implode("','", self::CLICK_EVENTS) . "'";
        $cur = (int) (Database::fetch(
            "SELECT COUNT(*) AS c FROM analytics_events
             WHERE event_type IN ($in)
               AND DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')"
        )['c'] ?? 0);
        $best = (int) (Database::fetch(
            "SELECT COALESCE(MAX(c), 0) AS c FROM (
                SELECT COUNT(*) AS c FROM analytics_events
                WHERE event_type IN ($in)
                  AND DATE_FORMAT(created_at, '%Y-%m') < DATE_FORMAT(NOW(), '%Y-%m')
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
             ) t"
        )['c'] ?? 0);
        return ['current' => $cur, 'best' => $best, 'delta' => $cur - $best];
    }

    /**
     * Detecta conquistas ativas: recorde semanal (7 dias) e mensal (mês corrente)
     * vs melhor marca histórica em períodos comparáveis anteriores.
     *
     * Retorna array de banners prontos para render. Dismiss é via cookie no client.
     */
    private function computeAchievements(): array
    {
        $in = "'" . implode("','", self::CLICK_EVENTS) . "'";
        $out = [];

        // ── 1. Recorde semanal (últimos 7 dias vs melhor semana ISO anterior) ─────
        $cur7 = (int) (Database::fetch(
            "SELECT COUNT(*) AS c FROM analytics_events
             WHERE event_type IN ($in) AND created_at >= (NOW() - INTERVAL 7 DAY)"
        )['c'] ?? 0);
        $bestWeek = (int) (Database::fetch(
            "SELECT COALESCE(MAX(c), 0) AS c FROM (
                SELECT COUNT(*) AS c
                FROM analytics_events
                WHERE event_type IN ($in)
                  AND YEARWEEK(created_at, 1) < YEARWEEK(NOW(), 1)
                GROUP BY YEARWEEK(created_at, 1)
             ) t"
        )['c'] ?? 0);
        if ($cur7 > 0 && $cur7 > $bestWeek) {
            $out[] = [
                'key'     => 'record_week_' . date('oW'),   // 1 banner por semana ISO
                'eyebrow' => 'Conquista',
                'title'   => 'Novo recorde semanal',
                'body'    => 'Últimos 7 dias superaram a melhor marca histórica.',
                'value'   => $cur7,
                'prev'    => $bestWeek,
                'unit'    => 'cliques',
            ];
        }

        // ── 2. Recorde mensal (mês corrente vs melhor mês anterior) ──────────────
        $curMonth = (int) (Database::fetch(
            "SELECT COUNT(*) AS c FROM analytics_events
             WHERE event_type IN ($in)
               AND DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')"
        )['c'] ?? 0);
        $bestMonth = (int) (Database::fetch(
            "SELECT COALESCE(MAX(c), 0) AS c FROM (
                SELECT COUNT(*) AS c
                FROM analytics_events
                WHERE event_type IN ($in)
                  AND DATE_FORMAT(created_at, '%Y-%m') < DATE_FORMAT(NOW(), '%Y-%m')
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
             ) t"
        )['c'] ?? 0);
        if ($curMonth > 0 && $curMonth > $bestMonth) {
            $out[] = [
                'key'     => 'record_month_' . date('Y-m'),
                'eyebrow' => 'Conquista',
                'title'   => 'Novo recorde mensal',
                'body'    => 'O mês atual superou a melhor marca histórica.',
                'value'   => $curMonth,
                'prev'    => $bestMonth,
                'unit'    => 'cliques',
            ];
        }

        return $out;
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
