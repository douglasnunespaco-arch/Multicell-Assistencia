<?php
/**
 * Layout admin padrão — sidebar + topbar + content.
 * @var string $content
 * @var string $page_title
 */
$title = $page_title ?? 'Admin · Multi Cell';
$user  = \App\Core\Auth::user();

// Tema: lê da sessão (preenchida no login). Se SSR já tem o valor certo,
// elimina o "flash" de tema errado antes do JS rodar.
$themePref = $_SESSION['theme_pref'] ?? 'auto';
if (!in_array($themePref, ['dark','light','auto'], true)) $themePref = 'auto';
// Tema efetivo no SSR: 'auto' cai pra 'dark' (o JS reconcilia com o SO no client).
$themeEffective = $themePref === 'auto' ? 'dark' : $themePref;

// Welcome flag e badges de gamification
$welcomeShow = !empty($_SESSION['_welcome_show']) ? 1 : 0;
$welcomeName = htmlspecialchars($user['name'] ?? 'admin', ENT_QUOTES, 'UTF-8');
$welcomeHit = 0; $welcomeStreak = 0; $welcomeDelta = 0;
if ($welcomeShow) {
    try {
        $in = "'whatsapp_click','cta_click','phone_click','map_click','promotion_click','service_click','product_click'";
        $today = (int) (\App\Core\Database::fetch(
            "SELECT COUNT(*) c FROM analytics_events WHERE event_type IN ($in) AND created_at >= :d",
            [':d' => date('Y-m-d') . ' 00:00:00']
        )['c'] ?? 0);
        $goal = (int) \App\Models\Setting::get('goal_clicks_day', '20');
        if ($today >= max(1, $goal)) $welcomeHit = 1;

        // Streak rápido (mesmo algoritmo do Dashboard, inline pra não acoplar)
        $rows = \App\Core\Database::fetchAll(
            "SELECT DATE(created_at) d, COUNT(*) c FROM analytics_events
             WHERE event_type IN ($in) AND created_at >= (CURDATE() - INTERVAL 90 DAY)
             GROUP BY DATE(created_at)"
        );
        $map = []; foreach ($rows as $r) $map[(string) $r['d']] = (int) $r['c'];
        $cursor = new \DateTimeImmutable('today');
        for ($i = 0; $i < 90; $i++) {
            $k = $cursor->format('Y-m-d'); $c = $map[$k] ?? 0;
            if ($i === 0 && $c < max(1,$goal)) { $cursor = $cursor->modify('-1 day'); continue; }
            if ($c >= max(1,$goal)) { $welcomeStreak++; $cursor = $cursor->modify('-1 day'); }
            else break;
        }
        // Delta mensal vs melhor mês
        $cur = (int) (\App\Core\Database::fetch(
            "SELECT COUNT(*) c FROM analytics_events
             WHERE event_type IN ($in) AND DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')"
        )['c'] ?? 0);
        $best = (int) (\App\Core\Database::fetch(
            "SELECT COALESCE(MAX(c),0) c FROM (
                SELECT COUNT(*) c FROM analytics_events
                WHERE event_type IN ($in) AND DATE_FORMAT(created_at, '%Y-%m') < DATE_FORMAT(NOW(), '%Y-%m')
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')) t"
        )['c'] ?? 0);
        $welcomeDelta = max(0, $cur - $best);
    } catch (\Throwable $e) { /* segue welcome simples */ }
    unset($_SESSION['_welcome_show']);
}
?><!doctype html>
<html lang="pt-BR" data-theme="<?= e($themeEffective) ?>" data-theme-pref="<?= e($themePref) ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title><?= e($title) ?></title>
<link rel="icon" href="/assets/img/favicon.svg" type="image/svg+xml">
<link rel="stylesheet" href="/assets/css/public.css">
<link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="admin-body"
      data-welcome="<?= (int) $welcomeShow ?>"
      data-welcome-name="<?= $welcomeName ?>"
      data-welcome-hit="<?= (int) $welcomeHit ?>"
      data-welcome-streak="<?= (int) $welcomeStreak ?>"
      data-welcome-delta="<?= (int) $welcomeDelta ?>"
      data-csrf="<?= e(\App\Core\Csrf::token()) ?>">

<div class="admin-shell">
    <?= \App\Core\View::capture('partials/admin/sidebar', ['user' => $user]) ?>

    <div class="admin-main">
        <?= \App\Core\View::capture('partials/admin/topbar', ['user' => $user]) ?>

        <main class="admin-content">
            <?= \App\Core\View::capture('partials/admin/flash') ?>
            <?= $content ?? '' ?>
        </main>
    </div>
</div>

<script src="/assets/js/admin.js" defer></script>
<script src="/assets/js/admin-confetti.js" defer></script>
<script src="/assets/js/admin-welcome.js" defer></script>
</body>
</html>
