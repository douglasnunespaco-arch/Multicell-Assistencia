<?php
/** Layout mínimo para /links (link-in-bio). Sem header/footer. */
/** @var string $content */
/** @var string $page_title */
$title = $page_title ?? '@multicell';
$desc  = $page_desc  ?? 'Todos os links da Multi Cell';
$theme = \App\Models\Setting::get('default_theme', 'dark');
?><!doctype html>
<html lang="pt-BR" data-theme="<?= e($theme) ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
<title><?= e($title) ?></title>
<meta name="description" content="<?= e($desc) ?>">
<meta name="theme-color" content="#0A0A0B">
<meta property="og:title" content="<?= e($title) ?>">
<meta property="og:description" content="<?= e($desc) ?>">
<link rel="icon" href="/assets/img/favicon.svg" type="image/svg+xml">
<link rel="preload" href="/assets/fonts/sora-700.woff2"   as="font" type="font/woff2" crossorigin>
<link rel="preload" href="/assets/fonts/manrope-400.woff2" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="/assets/css/public.css">
</head>
<body>
<?= $content ?? '' ?>
<script src="/assets/js/public.js" defer></script>
</body>
</html>
