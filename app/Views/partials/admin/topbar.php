<?php
/** @var array|null $user */
?>
<header class="admin-topbar" data-testid="admin-topbar">
    <div class="admin-topbar__title">
        <span class="admin-eyebrow">Painel admin</span>
        <h1><?= e($page_title ?? 'Painel') ?></h1>
    </div>
    <div class="admin-topbar__actions">
        <a href="/" target="_blank" rel="noopener" class="btn btn--ghost btn--sm" data-testid="view-public-site">
            <?= icon('globe', 16) ?> Ver site
        </a>
        <span class="admin-user" data-testid="admin-user-badge">
            <?= icon('user', 16) ?>
            <span><?= e($user['name'] ?? 'Admin') ?></span>
            <small><?= e($user['email'] ?? '') ?></small>
        </span>
    </div>
</header>
