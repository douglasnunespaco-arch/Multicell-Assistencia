<?php
/**
 * WeeklyDigest — script CLI que envia um resumo semanal por email
 * para todos os admins ativos. Pensado para rodar via cron na Hostinger:
 *
 *   0 9 * * 1 /usr/bin/php /home/USER/public_html/app/Console/WeeklyDigest.php >> /home/USER/public_html/storage/logs/digest.log 2>&1
 *
 * Idempotência: gravamos `weekly_digest_last_run` em settings; se a última
 * execução foi há menos de 5 dias, sai sem enviar (proteção contra spam
 * caso o cron seja agendado errado em mais de uma máquina).
 */
declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Use via CLI.\n");
}

// Bootstrap mínimo (sem session, sem router)
$root = dirname(__DIR__, 2);
define('APP_ROOT',     $root);
define('APP_DIR',      $root . '/app');
define('CONFIG_DIR',   $root . '/config');
define('STORAGE_DIR',  $root . '/storage');

require APP_DIR . '/Core/Autoload.php';
require CONFIG_DIR . '/config.php';
require APP_DIR . '/Core/Helpers.php';

date_default_timezone_set(defined('APP_TIMEZONE') ? APP_TIMEZONE : 'America/Cuiaba');

use App\Core\Database;
use App\Models\Setting;

$lastRun = Setting::get('weekly_digest_last_run', '');
if ($lastRun && (time() - strtotime($lastRun)) < (5 * 86400)) {
    echo "[WeeklyDigest] skipped (last run $lastRun)\n";
    exit(0);
}

// ── Coleta dados da semana ─────────────────────────────────────────────
$in = "'whatsapp_click','cta_click','phone_click','map_click','promotion_click','service_click','product_click'";

$totalWeek = (int) (Database::fetch(
    "SELECT COUNT(*) c FROM analytics_events
     WHERE event_type IN ($in) AND created_at >= (NOW() - INTERVAL 7 DAY)"
)['c'] ?? 0);

$prevWeek = (int) (Database::fetch(
    "SELECT COUNT(*) c FROM analytics_events
     WHERE event_type IN ($in)
       AND created_at >= (NOW() - INTERVAL 14 DAY)
       AND created_at <  (NOW() - INTERVAL 7 DAY)"
)['c'] ?? 0);

$leadsWeek = (int) (Database::fetch(
    "SELECT COUNT(*) c FROM lead_reservations WHERE created_at >= (NOW() - INTERVAL 7 DAY)"
)['c'] ?? 0);

$goal = max(1, (int) Setting::get('goal_clicks_day', '20'));
$daysHit = (int) (Database::fetch(
    "SELECT COUNT(*) c FROM (
        SELECT DATE(created_at) d, COUNT(*) c FROM analytics_events
        WHERE event_type IN ($in) AND created_at >= (NOW() - INTERVAL 7 DAY)
        GROUP BY DATE(created_at) HAVING c >= :g
    ) t",
    [':g' => $goal]
)['c'] ?? 0);

$top = Database::fetchAll(
    "SELECT COALESCE(p.name, s.name, pr.title, CONCAT(e.ref_type,':',e.ref_id)) AS title,
            e.ref_type AS type, COUNT(*) AS c
     FROM analytics_events e
     LEFT JOIN products   p  ON e.ref_type='product'   AND p.id  = e.ref_id
     LEFT JOIN services   s  ON e.ref_type='service'   AND s.id  = e.ref_id
     LEFT JOIN promotions pr ON e.ref_type='promotion' AND pr.id = e.ref_id
     WHERE e.event_type IN ('product_click','service_click','promotion_click')
       AND e.ref_type IN ('product','service','promotion')
       AND e.created_at >= (NOW() - INTERVAL 7 DAY)
     GROUP BY e.ref_type, e.ref_id
     ORDER BY c DESC
     LIMIT 5"
);

$delta = $totalWeek - $prevWeek;
$pct = $prevWeek > 0 ? round(($delta / $prevWeek) * 100) : ($totalWeek > 0 ? 100 : 0);
$weekNum = (int) date('W');
$siteName = (string) Setting::get('site_name', 'Multi Cell');
$appUrl   = defined('APP_URL') ? rtrim((string) APP_URL, '/') : '';

// ── Render template ────────────────────────────────────────────────────
ob_start();
include APP_DIR . '/Views/emails/weekly-digest.php';
$html = (string) ob_get_clean();

// ── Destinatários: admins ativos ───────────────────────────────────────
$admins = Database::fetchAll("SELECT email, name FROM admins WHERE is_active = 1 AND email LIKE '%@%'");
if (empty($admins)) {
    echo "[WeeklyDigest] no recipients\n";
    exit(0);
}

$subject = "Resumo " . $siteName . " · semana " . $weekNum . " · " . $totalWeek . " cliques";
$fromEmail = 'no-reply@' . preg_replace('/^https?:\/\//', '', $appUrl) ?: 'no-reply@localhost';
$headers   = "MIME-Version: 1.0\r\n"
           . "Content-Type: text/html; charset=UTF-8\r\n"
           . "From: " . $siteName . " <" . $fromEmail . ">\r\n"
           . "X-Mailer: MultiCell-WeeklyDigest";

$sent = 0; $failed = 0;
foreach ($admins as $a) {
    $ok = @mail((string) $a['email'], $subject, $html, $headers);
    if ($ok) $sent++; else $failed++;
}

Setting::set('weekly_digest_last_run', date('Y-m-d H:i:s'));
echo "[WeeklyDigest] sent=$sent failed=$failed at " . date('Y-m-d H:i:s') . "\n";
