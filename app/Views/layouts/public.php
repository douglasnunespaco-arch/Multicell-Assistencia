<?php
/** Layout master público */
/** @var string $content */
/** @var string $page_title */
/** @var string|null $page_desc */
$title = $page_title ?? \App\Models\Setting::get('seo_title', 'Multi Cell Assistência Técnica');
$desc  = $page_desc  ?? \App\Models\Setting::get('seo_description', '');
$theme = \App\Models\Setting::get('default_theme', 'dark');
// Suporte a 'auto': respeita preferência do SO via classe inicial 'auto' que será resolvida no client.
$themeAttr = ($theme === 'auto') ? 'dark' : $theme; // fallback durante SSR
?><!doctype html>
<html lang="pt-BR" data-theme="<?= e($themeAttr) ?>"<?= $theme === 'auto' ? ' data-theme-default="auto"' : '' ?>>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
<title><?= e($title) ?></title>
<meta name="description" content="<?= e($desc) ?>">
<meta name="theme-color" content="#0A0A0B">
<meta property="og:site_name" content="Multi Cell Assistência Técnica">
<meta property="og:title" content="<?= e($title) ?>">
<meta property="og:description" content="<?= e($desc) ?>">
<meta property="og:type" content="website">
<meta name="twitter:card" content="summary_large_image">
<link rel="icon" href="/assets/img/favicon.svg" type="image/svg+xml">
<link rel="preload" href="/assets/fonts/sora-700.woff2"   as="font" type="font/woff2" crossorigin>
<link rel="preload" href="/assets/fonts/manrope-400.woff2" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="/assets/css/public.css">
</head>
<body>

<?= \App\Core\View::capture('partials/public/header') ?>

<main>
<?= $content ?? '' ?>
</main>

<?= \App\Core\View::capture('partials/public/footer') ?>
<?= \App\Core\View::capture('partials/public/whatsapp-float') ?>

<script src="/assets/js/public.js" defer></script>
</body>
</html>
