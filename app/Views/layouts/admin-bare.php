<?php
/**
 * Layout admin "bare" — sem sidebar/topbar (login, recuperação, etc).
 * @var string $content
 * @var string $page_title
 */
$title = $page_title ?? 'Admin · Multi Cell';
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
<body class="admin-body admin-body--bare">
    <main class="admin-bare">
        <?= \App\Core\View::capture('partials/admin/flash') ?>
        <?= $content ?? '' ?>
    </main>
<script src="/assets/js/admin.js" defer></script>
</body>
</html>
