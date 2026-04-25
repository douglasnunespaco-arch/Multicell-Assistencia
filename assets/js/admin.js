/**
 * Multi Cell — admin.js
 * Sub-rodada 3A: interações mínimas (sem tracking, sem AJAX).
 * Fica pronto para crescer em 3B/3C (uploads, reorder, etc).
 */
(function () {
    'use strict';

    // Fecha flashes automaticamente após 5s
    document.querySelectorAll('.admin-flash').forEach(function (el) {
        setTimeout(function () {
            el.style.transition = 'opacity .4s ease, transform .4s ease';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-4px)';
            setTimeout(function () { el.remove(); }, 450);
        }, 5000);
    });

    // Confirmação de logout suave (evita POST acidental via teclado)
    var logoutForm = document.querySelector('form.admin-logout');
    if (logoutForm) {
        logoutForm.addEventListener('submit', function (e) {
            if (!confirm('Encerrar sessão?')) e.preventDefault();
        });
    }

    // Preview de imagem em inputs file do admin
    document.querySelectorAll('input[type=file][data-file-preview]').forEach(function (inp) {
        inp.addEventListener('change', function () {
            var file = inp.files && inp.files[0];
            if (!file) return;
            var wrap = inp.closest('.admin-field');
            if (!wrap) return;
            var current = wrap.querySelector('[data-file-preview-current]');
            var reader = new FileReader();
            reader.onload = function (e) {
                if (current) { current.src = e.target.result; return; }
                var host = wrap.querySelector('.admin-field__current');
                if (!host) {
                    host = document.createElement('div');
                    host.className = 'admin-field__current';
                    wrap.appendChild(host);
                }
                host.innerHTML = '<img src="' + e.target.result + '" alt="" data-file-preview-current>';
            };
            reader.readAsDataURL(file);
        });
    });

    // Sincroniza color picker <-> input hex (campos de tema)
    document.querySelectorAll('input[type="color"][data-color-target]').forEach(function (picker) {
        var targetId = picker.getAttribute('data-color-target');
        var target = document.getElementById(targetId);
        if (!target) return;
        picker.addEventListener('input', function () { target.value = picker.value.toUpperCase(); });
        target.addEventListener('input', function () {
            var v = (target.value || '').trim();
            if (/^#[0-9a-fA-F]{6}$/.test(v)) picker.value = v;
        });
    });

    /* ----------------------------------------------------------------
     * Modo Foco — colapsa sidebar e dá fullwidth ao content.
     * Persistido em localStorage. Atalho: tecla F (fora de inputs).
     * ---------------------------------------------------------------- */
    var FOCUS_KEY = 'mc_focus_mode';
    var shell = document.querySelector('.admin-shell');
    function applyFocus(on) {
        if (!shell) return;
        shell.classList.toggle('admin-shell--focus', !!on);
        document.querySelectorAll('[data-focus-toggle]').forEach(function (b) {
            b.setAttribute('aria-pressed', on ? 'true' : 'false');
            b.classList.toggle('is-active', !!on);
        });
        try { localStorage.setItem(FOCUS_KEY, on ? '1' : '0'); } catch (e) {}
    }
    try { if (localStorage.getItem(FOCUS_KEY) === '1') applyFocus(true); } catch (e) {}
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-focus-toggle]');
        if (!btn || !shell) return;
        applyFocus(!shell.classList.contains('admin-shell--focus'));
    });
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'f' && e.key !== 'F') return;
        var t = e.target;
        if (t && (t.tagName === 'INPUT' || t.tagName === 'TEXTAREA' || t.isContentEditable)) return;
        if (e.metaKey || e.ctrlKey || e.altKey) return;
        if (!shell) return;
        applyFocus(!shell.classList.contains('admin-shell--focus'));
    });

    /* ----------------------------------------------------------------
     * Notifications API + polling leve — alternativa enxuta a Web Push.
     * Pede permissão 1× quando admin está logado; a cada 60s consulta
     * /admin/api/achievements; dispara Notification se record_week mudou
     * desde a última vez disparada nesta sessão. Funciona em qualquer host.
     * ---------------------------------------------------------------- */
    if ('Notification' in window && document.body.getAttribute('data-csrf')) {
        var SIG_KEY = 'mc_last_record_sig';
        function fireRecord(payload) {
            try {
                var lastSig = sessionStorage.getItem(SIG_KEY);
                if (lastSig === payload.sig) return;
                sessionStorage.setItem(SIG_KEY, payload.sig);
            } catch (e) {}
            try {
                new Notification('Multi Cell · novo recorde semanal!', {
                    body: payload.cur_week + ' cliques em 7 dias (recorde anterior: ' + payload.best_week + ').',
                    icon: '/assets/img/favicon.svg',
                    tag: 'mc-record',
                });
            } catch (e) {}
        }
        function poll() {
            fetch('/admin/api/achievements', { credentials: 'same-origin' })
                .then(function (r) { return r.ok ? r.json() : null; })
                .then(function (j) { if (j && j.ok && j.record_week) fireRecord(j); })
                .catch(function () {});
        }
        function maybeAskPermission() {
            if (Notification.permission === 'default') {
                // pede só uma vez por sessão (evita spam)
                try {
                    if (sessionStorage.getItem('mc_notif_asked') === '1') return;
                    sessionStorage.setItem('mc_notif_asked', '1');
                } catch (e) {}
                // delay 4s pra não atropelar a animação de welcome
                setTimeout(function () {
                    Notification.requestPermission().then(function (p) {
                        if (p === 'granted') poll();
                    });
                }, 4000);
            } else if (Notification.permission === 'granted') {
                setTimeout(poll, 4000);
            }
        }
        maybeAskPermission();
        setInterval(function () {
            if (Notification.permission === 'granted' && !document.hidden) poll();
        }, 60000);
    }
})();
