<?php
/** @var array|null $user */
?>
<header class="admin-topbar" data-testid="admin-topbar">
    <div class="admin-topbar__title">
        <span class="admin-eyebrow">Painel admin</span>
        <h1><?= e($page_title ?? 'Painel') ?></h1>
    </div>
    <div class="admin-topbar__actions">
        <button type="button"
                class="admin-theme-toggle"
                data-admin-theme-toggle
                aria-label="Alternar tema"
                aria-pressed="false"
                data-testid="admin-theme-toggle"
                title="Alternar tema claro/escuro">
            <span class="admin-theme-toggle__icon admin-theme-toggle__icon--moon" aria-hidden="true"><?= icon('moon', 16) ?></span>
            <span class="admin-theme-toggle__icon admin-theme-toggle__icon--sun"  aria-hidden="true"><?= icon('sun',  16) ?></span>
        </button>
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
<script>
(function () {
    var root = document.documentElement;
    var KEY = 'mc_theme';
    var saved = null;
    try { saved = localStorage.getItem(KEY); } catch (e) {}
    if (saved === 'light' || saved === 'dark') root.setAttribute('data-theme', saved);
    function sync() {
        var t = root.getAttribute('data-theme') || 'dark';
        document.querySelectorAll('[data-admin-theme-toggle],[data-theme-toggle]').forEach(function (b) {
            b.setAttribute('aria-pressed', t === 'light' ? 'true' : 'false');
            b.setAttribute('aria-label', 'Alternar para tema ' + (t === 'light' ? 'escuro' : 'claro'));
        });
    }
    sync();
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-admin-theme-toggle]');
        if (!btn) return;
        var cur = root.getAttribute('data-theme') || 'dark';
        var next = cur === 'dark' ? 'light' : 'dark';
        root.setAttribute('data-theme', next);
        try { localStorage.setItem(KEY, next); } catch (err) {}
        sync();
    });
})();
</script>
