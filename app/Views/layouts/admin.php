<?php
/**
 * Layout admin padrão — sidebar + topbar + content.
 * @var string $content
 * @var string $page_title
 */
$title = $page_title ?? 'Admin · Multi Cell';
$user  = \App\Core\Auth::user();
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
<body class="admin-body">

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
</body>
</html>
