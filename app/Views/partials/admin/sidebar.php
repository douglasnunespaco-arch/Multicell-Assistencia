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

        <div class="admin-nav__section">Gestão</div>

        <span class="admin-nav__item is-disabled" data-testid="nav-leads-placeholder" title="Disponível na Sub-rodada 3C">
            <?= icon('calendar', 18) ?> <span>Reservas</span>
            <small class="admin-badge">em breve · 3C</small>
        </span>
        <span class="admin-nav__item is-disabled" title="Disponível na Sub-rodada 3B">
            <?= icon('image', 18) ?> <span>Slides (Hero)</span>
            <small class="admin-badge">3B</small>
        </span>
        <span class="admin-nav__item is-disabled" title="Disponível na Sub-rodada 3B">
            <?= icon('wrench', 18) ?> <span>Serviços</span>
            <small class="admin-badge">3B</small>
        </span>
        <span class="admin-nav__item is-disabled" title="Disponível na Sub-rodada 3B">
            <?= icon('package', 18) ?> <span>Produtos</span>
            <small class="admin-badge">3B</small>
        </span>
        <span class="admin-nav__item is-disabled" title="Disponível na Sub-rodada 3B">
            <?= icon('tag', 18) ?> <span>Promoções</span>
            <small class="admin-badge">3B</small>
        </span>

        <div class="admin-nav__section">Sistema</div>

        <span class="admin-nav__item is-disabled" title="Disponível na Sub-rodada 3B">
            <?= icon('tools', 18) ?> <span>Configurações</span>
            <small class="admin-badge">3B</small>
        </span>

        <form method="post" action="/admin/logout" class="admin-logout" data-testid="admin-logout-form">
            <?= \App\Core\Csrf::field() ?>
            <button type="submit" class="admin-nav__item admin-nav__item--logout" data-testid="admin-logout-btn">
                <?= icon('close', 18) ?> <span>Sair</span>
            </button>
        </form>
    </nav>
</aside>
