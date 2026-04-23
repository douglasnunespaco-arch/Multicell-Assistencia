<?php
/** Header fixo do público */
$currentPath = '/' . trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '', '/');
$isActive = function (string $match) use ($currentPath): string {
    if ($match === '/' && $currentPath === '/') return 'is-active';
    if ($match !== '/' && str_starts_with($currentPath, $match)) return 'is-active';
    return '';
};
?>
<header class="site-header" data-site-header>
    <div class="container site-header__inner">
        <a href="/" class="brand" aria-label="Multi Cell — Início">
            <span class="brand__mark">MC</span>
            <span class="brand__name">
                Multi Cell
                <small>Assistência Técnica</small>
            </span>
        </a>

        <nav class="main-nav" aria-label="Principal">
            <a href="/" class="<?= $isActive('/') ?>">Início</a>
            <a href="/assistencia-tecnica" class="<?= $isActive('/assistencia-tecnica') ?>">Assistência</a>
            <a href="/seminovos" class="<?= $isActive('/seminovos') ?>">Seminovos</a>
            <a href="/acessorios" class="<?= $isActive('/acessorios') ?>">Acessórios</a>
            <a href="/delivery" class="<?= $isActive('/delivery') ?>">Delivery</a>
            <a href="/promocoes" class="<?= $isActive('/promocoes') ?>">Promoções</a>
            <a href="/sobre" class="<?= $isActive('/sobre') ?>">Sobre</a>
            <a href="/contato" class="<?= $isActive('/contato') ?>">Contato</a>
            <a href="/links" class="<?= $isActive('/links') ?>">Links</a>
        </nav>

        <div class="header-actions">
            <button type="button" class="theme-toggle" data-theme-toggle aria-label="Alternar tema" aria-pressed="false">
                <?= icon('moon', 18) ?>
            </button>
            <a href="<?= whatsapp_link('header') ?>" rel="noopener" class="btn btn--primary btn--sm btn--whatsapp"
               data-track="whatsapp_click" data-track-source="header">
                <?= icon('whatsapp', 18) ?>
                WhatsApp
            </a>
            <button type="button" class="burger" data-drawer-open aria-label="Abrir menu">
                <?= icon('menu', 20) ?>
            </button>
        </div>
    </div>
</header>

<aside class="mobile-drawer" data-drawer aria-label="Menu mobile">
    <button type="button" class="mobile-drawer__close" data-drawer-close aria-label="Fechar menu">
        <?= icon('close', 18) ?>
    </button>
    <nav aria-label="Principal (mobile)">
        <a href="/" class="<?= $isActive('/') ?>">Início</a>
        <a href="/assistencia-tecnica" class="<?= $isActive('/assistencia-tecnica') ?>">Assistência Técnica</a>
        <a href="/seminovos" class="<?= $isActive('/seminovos') ?>">Seminovos</a>
        <a href="/acessorios" class="<?= $isActive('/acessorios') ?>">Acessórios</a>
        <a href="/delivery" class="<?= $isActive('/delivery') ?>">Delivery · WhatsApp</a>
        <a href="/promocoes" class="<?= $isActive('/promocoes') ?>">Promoções</a>
        <a href="/reservar" class="<?= $isActive('/reservar') ?>">Reservar</a>
        <a href="/sobre" class="<?= $isActive('/sobre') ?>">Sobre</a>
        <a href="/contato" class="<?= $isActive('/contato') ?>">Contato</a>
        <a href="/links" class="<?= $isActive('/links') ?>">Links</a>
    </nav>
    <div class="drawer-cta">
        <a href="<?= whatsapp_link('drawer') ?>" class="btn btn--primary btn--block"
           data-track="whatsapp_click" data-track-source="drawer">
            Chamar no WhatsApp
        </a>
    </div>
    <div class="drawer-meta">
        <?= e(\App\Models\Setting::get('hours_default', 'Seg a Sex 8h–18h · Sáb 8h–12h')) ?>
    </div>
</aside>
