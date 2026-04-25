<?php
/**
 * Sidebar admin.
 * @var array|null $user
 */
$current = $_SERVER['REQUEST_URI'] ?? '';
?>
<aside class="admin-sidebar" data-testid="admin-sidebar">
    <a class="admin-brand" href="/admin" data-testid="admin-brand-link">
        <span class="admin-brand__dot"></span>
        <span class="admin-brand__text">Multi Cell<small>painel</small></span>
    </a>

    <nav class="admin-nav" aria-label="Menu admin">
        <a class="admin-nav__item<?= ($current === '/admin' || $current === '/admin/') ? ' is-active' : '' ?>"
           href="/admin" data-testid="nav-dashboard">
            <?= icon('bolt', 18) ?> <span>Painel</span>
        </a>
        <a class="admin-nav__item<?= str_starts_with($current, '/admin/analytics') ? ' is-active' : '' ?>"
           href="/admin/analytics" data-testid="nav-analytics">
            <?= icon('chart', 18) ?> <span>Analytics</span>
        </a>

        <div class="admin-nav__section">Gestão</div>

        <a class="admin-nav__item<?= str_starts_with($current, '/admin/leads') ? ' is-active' : '' ?>"
           href="/admin/leads" data-testid="nav-leads">
            <?= icon('calendar', 18) ?> <span>Reservas</span>
        </a>
        <a class="admin-nav__item<?= str_starts_with($current, '/admin/slides') ? ' is-active' : '' ?>"
           href="/admin/slides" data-testid="nav-slides">
            <?= icon('image', 18) ?> <span>Slides (Hero)</span>
        </a>
        <a class="admin-nav__item<?= str_starts_with($current, '/admin/services') ? ' is-active' : '' ?>"
           href="/admin/services" data-testid="nav-services">
            <?= icon('wrench', 18) ?> <span>Serviços</span>
        </a>
        <a class="admin-nav__item<?= str_starts_with($current, '/admin/products') ? ' is-active' : '' ?>"
           href="/admin/products" data-testid="nav-products">
            <?= icon('package', 18) ?> <span>Produtos</span>
        </a>
        <a class="admin-nav__item<?= str_starts_with($current, '/admin/promotions') ? ' is-active' : '' ?>"
           href="/admin/promotions" data-testid="nav-promotions">
            <?= icon('tag', 18) ?> <span>Promoções</span>
        </a>
        <a class="admin-nav__item<?= str_starts_with($current, '/admin/testimonials') ? ' is-active' : '' ?>"
           href="/admin/testimonials" data-testid="nav-testimonials">
            <?= icon('award', 18) ?> <span>Depoimentos</span>
        </a>
        <a class="admin-nav__item<?= str_starts_with($current, '/admin/about') ? ' is-active' : '' ?>"
           href="/admin/about" data-testid="nav-about">
            <?= icon('info', 18) ?> <span>Sobre</span>
        </a>
        <a class="admin-nav__item<?= str_starts_with($current, '/admin/links') ? ' is-active' : '' ?>"
           href="/admin/links" data-testid="nav-links">
            <?= icon('globe', 18) ?> <span>Links / Bio</span>
        </a>
        <a class="admin-nav__item<?= str_starts_with($current, '/admin/units') ? ' is-active' : '' ?>"
           href="/admin/units" data-testid="nav-units">
            <?= icon('pin', 18) ?> <span>Unidades</span>
        </a>

        <div class="admin-nav__section">Sistema</div>

        <a class="admin-nav__item<?= str_starts_with($current, '/admin/theme') ? ' is-active' : '' ?>"
           href="/admin/theme" data-testid="nav-theme">
            <?= icon('sparkle', 18) ?> <span>Tema</span>
        </a>
        <a class="admin-nav__item<?= str_starts_with($current, '/admin/seo') ? ' is-active' : '' ?>"
           href="/admin/seo" data-testid="nav-seo">
            <?= icon('search', 18) ?> <span>SEO</span>
        </a>
        <a class="admin-nav__item<?= str_starts_with($current, '/admin/sections') ? ' is-active' : '' ?>"
           href="/admin/sections" data-testid="nav-sections">
            <?= icon('bolt', 18) ?> <span>Seções da home</span>
        </a>
        <a class="admin-nav__item<?= str_starts_with($current, '/admin/settings') ? ' is-active' : '' ?>"
           href="/admin/settings" data-testid="nav-settings">
            <?= icon('tools', 18) ?> <span>Configurações</span>
        </a>

        <form method="post" action="/admin/logout" class="admin-logout" data-testid="admin-logout-form">
            <?= \App\Core\Csrf::field() ?>
            <button type="submit" class="admin-nav__item admin-nav__item--logout" data-testid="admin-logout-btn">
                <?= icon('close', 18) ?> <span>Sair</span>
            </button>
        </form>
    </nav>
</aside>
