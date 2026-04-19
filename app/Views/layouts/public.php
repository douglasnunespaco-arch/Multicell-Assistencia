<?php
/** @var string $content */
/** @var string $title */
?><!doctype html>
<html lang="pt-BR" data-theme="dark">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($title ?? 'Multi Cell Assistência Técnica') ?></title>
<meta name="description" content="Assistência técnica especializada em celulares em Várzea Grande/MT. Troca de tela, bateria, placa e acessórios.">
<meta name="theme-color" content="#0A0A0B">
<link rel="icon" href="/assets/img/favicon.svg" type="image/svg+xml">
<link rel="stylesheet" href="/assets/css/public.css">
</head>
<body>
<?= $content ?? '' ?>
</body>
</html>
