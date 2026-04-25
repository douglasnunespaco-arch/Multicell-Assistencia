<?php
/**
 * Layout admin padrão — sidebar + topbar + content.
 * @var string $content
 * @var string $page_title
 */
$title = $page_title ?? 'Admin · Multi Cell';
$user  = \App\Core\Auth::user();
// Welcome flag: setado pelo AuthController e consumido aqui (uma vez).
$welcomeShow = !empty($_SESSION['_welcome_show']) ? 1 : 0;
$welcomeName = htmlspecialchars($user['name'] ?? 'admin', ENT_QUOTES, 'UTF-8');
// Hit-goal flag: alguma meta de período batida hoje? Lemos só na rota /admin (dashboard).
$welcomeHit = 0;
if ($welcomeShow) {
    try {
        $in = "'whatsapp_click','cta_click','phone_click','map_click','promotion_click','service_click','product_click'";
        $today = (int) (\App\Core\Database::fetch(
            "SELECT COUNT(*) c FROM analytics_events WHERE event_type IN ($in) AND created_at >= :d",
            [':d' => date('Y-m-d') . ' 00:00:00']
        )['c'] ?? 0);
        $goal = (int) \App\Models\Setting::get('goal_clicks_day', '20');
        if ($today >= max(1, $goal)) $welcomeHit = 1;
    } catch (\Throwable $e) { /* ignora — animação roda sem o efeito de meta */ }
    unset($_SESSION['_welcome_show']);
}
?><!doctype html>
<html lang="pt-BR" data-theme="dark">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title><?= e($title) ?></title>
<link rel="icon" href="/assets/img/favicon.svg" type="image/svg+xml">
<link rel="stylesheet" href="/assets/css/public.css">
<link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="admin-body" data-welcome="<?= (int) $welcomeShow ?>" data-welcome-name="<?= $welcomeName ?>" data-welcome-hit="<?= (int) $welcomeHit ?>">

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
