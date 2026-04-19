<?php $path = current_path(); ?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? setting('seo_title', 'Multi Cell')) ?></title>
    <meta name="description" content="<?= htmlspecialchars($description ?? setting('seo_description', 'Assistência técnica em celulares e promoções.')) ?>">
    <link rel="icon" href="<?= asset_url('assets/img/logo.png') ?>">
    <link rel="stylesheet" href="<?= asset_url('assets/css/site.css') ?>">
</head>
<body>
    <div class="announcement">
        <div class="container"><?= htmlspecialchars(setting('announcement_bar', 'Atendimento rápido, promoções e orçamento via WhatsApp.')) ?></div>
    </div>
    <header class="site-header">
        <div class="container nav-wrap">
            <a class="brand" href="<?= base_url('/') ?>">
                <img src="<?= asset_url('assets/img/logo.png') ?>" alt="Multi Cell">
                <span>
                    <strong><?= htmlspecialchars(setting('brand_name', 'Multi Cell')) ?></strong>
                    <small>Assistência Técnica</small>
                </span>
            </a>
            <button class="menu-toggle" type="button" aria-label="Abrir menu">☰</button>
            <nav class="site-nav">
                <a href="<?= base_url('/') ?>" class="<?= $path === '/' ? 'active' : '' ?>">Home</a>
                <a href="<?= base_url('/assistencia') ?>" class="<?= $path === '/assistencia' ? 'active' : '' ?>">Assistência</a>
                <a href="<?= base_url('/produtos') ?>" class="<?= $path === '/produtos' ? 'active' : '' ?>">Produtos</a>
                <a href="<?= base_url('/promocoes') ?>" class="<?= $path === '/promocoes' ? 'active' : '' ?>">Promoções</a>
                <a href="<?= base_url('/reservar') ?>" class="<?= $path === '/reservar' ? 'active' : '' ?>">Reservar</a>
                <a href="<?= base_url('/sobre') ?>" class="<?= $path === '/sobre' ? 'active' : '' ?>">Sobre</a>
                <a href="<?= base_url('/contato') ?>" class="<?= $path === '/contato' ? 'active' : '' ?>">Contato</a>
            </nav>
            <a class="btn btn-primary hide-mobile trackable" href="<?= htmlspecialchars(setting('primary_cta_url', 'https://wa.me/5500000000000')) ?>" target="_blank" rel="noopener" data-event="whatsapp_click" data-cta="<?= htmlspecialchars(setting('primary_cta_label', 'Chamar no WhatsApp')) ?>">WhatsApp</a>
        </div>
    </header>
    <main><?= $content ?></main>
    <footer class="site-footer">
        <div class="container footer-grid">
            <div>
                <div class="footer-brand">
                    <img src="<?= asset_url('assets/img/logo.png') ?>" alt="Multi Cell">
                    <div>
                        <strong><?= htmlspecialchars(setting('brand_name', 'Multi Cell')) ?></strong>
                        <p><?= htmlspecialchars(setting('tagline', 'Assistência técnica especializada e ofertas reais para seu celular.')) ?></p>
                    </div>
                </div>
            </div>
            <div>
                <h4>Atendimento</h4>
                <p><?= nl2br(htmlspecialchars(setting('address', 'Endereço não configurado'))) ?></p>
                <p><?= htmlspecialchars(setting('business_hours', 'Horário não configurado')) ?></p>
            </div>
            <div>
                <h4>Links rápidos</h4>
                <a href="<?= base_url('/reservar') ?>">Reservar atendimento</a>
                <a href="<?= base_url('/promocoes') ?>">Promoções</a>
                <a href="<?= htmlspecialchars(setting('instagram_url', '#')) ?>" target="_blank" rel="noopener">Instagram</a>
                <a href="<?= base_url('/admin/login') ?>">Admin</a>
            </div>
        </div>
        <div class="container footer-copy"><?= htmlspecialchars(setting('footer_note', 'Multi Cell')) ?></div>
    </footer>
    <script>window.APP_BASE_URL = <?= json_encode(base_url('/')) ?>;</script>
    <script src="<?= asset_url('assets/js/site.js') ?>"></script>
</body>
</html>
