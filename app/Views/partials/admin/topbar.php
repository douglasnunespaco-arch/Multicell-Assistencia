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
                class="admin-theme-toggle admin-theme-toggle--focus"
                data-focus-toggle
                aria-label="Modo foco (oculta sidebar)"
                aria-pressed="false"
                data-testid="admin-focus-toggle"
                title="Modo foco · tecla F">
            <span aria-hidden="true"><?= icon('search', 16) ?></span>
        </button>
        <button type="button"
                class="admin-theme-toggle"
                data-admin-theme-toggle
                aria-label="Alternar tema"
                aria-pressed="false"
                data-testid="admin-theme-toggle"
                title="Alternar tema (claro / escuro / auto)">
            <span class="admin-theme-toggle__icon admin-theme-toggle__icon--moon" aria-hidden="true"><?= icon('moon', 16) ?></span>
            <span class="admin-theme-toggle__icon admin-theme-toggle__icon--sun"  aria-hidden="true"><?= icon('sun',  16) ?></span>
            <span class="admin-theme-toggle__icon admin-theme-toggle__icon--auto" aria-hidden="true" title="Auto"><?= icon('sparkle', 16) ?></span>
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
    var KEY  = 'mc_theme';      // valor efetivo: dark | light
    var PREF = 'mc_theme_pref'; // preferência do usuário: dark | light | auto
    var media = window.matchMedia ? window.matchMedia('(prefers-color-scheme: dark)') : null;

    function applyEffective(pref) {
        var eff;
        if (pref === 'auto') eff = (media && media.matches) ? 'dark' : 'light';
        else eff = (pref === 'light' ? 'light' : 'dark');
        root.setAttribute('data-theme', eff);
        root.setAttribute('data-theme-pref', pref);
        try {
            localStorage.setItem(KEY, eff);
            localStorage.setItem(PREF, pref);
        } catch (e) {}
        document.querySelectorAll('[data-admin-theme-toggle]').forEach(function (b) {
            b.setAttribute('aria-pressed', eff === 'light' ? 'true' : 'false');
            b.setAttribute('aria-label',
                pref === 'auto' ? 'Tema automático (segue o sistema). Clique para alternar.' :
                pref === 'light' ? 'Tema claro. Clique para alternar.' : 'Tema escuro. Clique para alternar.');
            b.setAttribute('title',
                pref === 'auto' ? 'Auto · segue o SO' :
                pref === 'light' ? 'Claro' : 'Escuro');
        });
    }

    var saved;
    // Prioridade: SSR (vindo do servidor, dado oficial) > localStorage > default
    var ssr = root.getAttribute('data-theme-pref');
    if (ssr === 'light' || ssr === 'dark' || ssr === 'auto') {
        saved = ssr;
    } else {
        try { saved = localStorage.getItem(PREF); } catch (e) {}
        if (saved !== 'light' && saved !== 'dark' && saved !== 'auto') {
            try { saved = localStorage.getItem(KEY) === 'light' ? 'light' : 'dark'; } catch (e) { saved = 'dark'; }
        }
    }
    applyEffective(saved);

    if (media && media.addEventListener) {
        media.addEventListener('change', function () {
            var cur = root.getAttribute('data-theme-pref') || 'dark';
            if (cur === 'auto') applyEffective('auto');
        });
    }

    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-admin-theme-toggle]');
        if (!btn) return;
        var cur = root.getAttribute('data-theme-pref') || 'dark';
        var next = cur === 'dark' ? 'light' : (cur === 'light' ? 'auto' : 'dark');
        applyEffective(next);
        // Persiste no servidor (silencioso · não bloqueia UX)
        try {
            var csrf = document.body.getAttribute('data-csrf') || '';
            var fd = new FormData();
            fd.append('_csrf', csrf);
            fd.append('pref', next);
            fetch('/admin/theme/preference', { method: 'POST', body: fd, credentials: 'same-origin' });
        } catch (err) { /* offline ou csrf indisponível: localStorage cobre */ }
    });

    // Eventos vindos da página /admin/theme (radios)
    document.addEventListener('change', function (e) {
        var input = e.target.closest('input[name="theme_preference"]');
        if (!input || !input.checked) return;
        applyEffective(input.value);
    });
})();
</script>
