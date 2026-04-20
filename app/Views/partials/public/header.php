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
            <a href="/produtos" class="<?= $isActive('/produtos') ?>">Produtos</a>
            <a href="/promocoes" class="<?= $isActive('/promocoes') ?>">Promoções</a>
            <a href="/sobre" class="<?= $isActive('/sobre') ?>">Sobre</a>
            <a href="/contato" class="<?= $isActive('/contato') ?>">Contato</a>
        </nav>

        <div class="header-actions">
            <button type="button" class="theme-toggle" data-theme-toggle aria-label="Alternar tema" aria-pressed="false">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
            </button>
            <a href="<?= whatsapp_link('header') ?>" rel="noopener" class="btn btn--primary btn--sm btn--whatsapp"
               data-track="whatsapp_click" data-track-source="header">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20.52 3.48A11.9 11.9 0 0 0 12.06 0C5.47 0 .12 5.35.12 11.94c0 2.1.55 4.15 1.6 5.97L0 24l6.27-1.65a11.93 11.93 0 0 0 5.79 1.48h.01c6.58 0 11.94-5.35 11.94-11.94 0-3.19-1.24-6.19-3.5-8.41zm-8.46 18.35h-.01a9.86 9.86 0 0 1-5.03-1.38l-.36-.21-3.72.98.99-3.63-.23-.37a9.86 9.86 0 0 1-1.51-5.28c0-5.47 4.45-9.92 9.93-9.92 2.65 0 5.14 1.03 7.01 2.91a9.84 9.84 0 0 1 2.91 7.02c-.01 5.47-4.46 9.88-9.98 9.88zm5.45-7.43c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.66.15-.2.3-.76.97-.93 1.17-.17.2-.34.22-.64.07-.3-.15-1.26-.46-2.4-1.48-.89-.79-1.49-1.77-1.66-2.07-.17-.3-.02-.46.13-.61.14-.13.3-.34.45-.51.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.07-.15-.66-1.59-.9-2.18-.24-.57-.48-.49-.66-.5-.17 0-.37-.02-.57-.02-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.48 0 1.46 1.06 2.88 1.21 3.08.15.2 2.1 3.2 5.08 4.49.71.31 1.26.49 1.69.63.71.23 1.36.2 1.87.12.57-.09 1.76-.72 2.01-1.41.25-.69.25-1.28.17-1.41-.07-.13-.27-.2-.57-.35z"/></svg>
                WhatsApp
            </a>
            <button type="button" class="burger" data-drawer-open aria-label="Abrir menu">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="7" x2="20" y2="7"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="17" x2="20" y2="17"/></svg>
            </button>
        </div>
    </div>
</header>

<aside class="mobile-drawer" data-drawer aria-label="Menu mobile">
    <button type="button" class="mobile-drawer__close" data-drawer-close aria-label="Fechar menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="6" y1="6" x2="18" y2="18"/><line x1="6" y1="18" x2="18" y2="6"/></svg>
    </button>
    <nav aria-label="Principal (mobile)">
        <a href="/" class="<?= $isActive('/') ?>">Início</a>
        <a href="/assistencia-tecnica" class="<?= $isActive('/assistencia-tecnica') ?>">Assistência Técnica</a>
        <a href="/produtos" class="<?= $isActive('/produtos') ?>">Produtos</a>
        <a href="/promocoes" class="<?= $isActive('/promocoes') ?>">Promoções</a>
        <a href="/reservar" class="<?= $isActive('/reservar') ?>">Reservar</a>
        <a href="/sobre" class="<?= $isActive('/sobre') ?>">Sobre</a>
        <a href="/contato" class="<?= $isActive('/contato') ?>">Contato</a>
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
