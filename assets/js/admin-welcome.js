/**
 * admin-welcome.js
 * Animação de boas-vindas pós-login (10s) com emojis flutuando — estrelas,
 * troféus, sparkles, raios — e confete extra quando o admin entra com alguma
 * meta de período já batida (data-goal-hit="1").
 *
 * Vanilla. Sem dependências. Respeita prefers-reduced-motion.
 * Dispara apenas uma vez por sessão (sessionStorage flag).
 */
(function () {
    'use strict';

    var FLAG = 'mc_welcome_shown';
    var DURATION = 10000;

    function reduced() {
        return window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    }
    function shouldFire() {
        // Só dispara se backend marcou welcome=1 OU se ainda não foi mostrado nesta sessão e estamos no /admin (dashboard)
        var welcomeFlag = document.body.getAttribute('data-welcome') === '1';
        if (!welcomeFlag) return false;
        try {
            if (sessionStorage.getItem(FLAG) === '1') return false;
            sessionStorage.setItem(FLAG, '1');
        } catch (e) {}
        return !reduced();
    }

    // Pool de SVGs vetoriais (usa as cores da marca). Tudo inline pra evitar requests.
    var SHAPES = [
        // estrela cheia
        '<svg viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26"/></svg>',
        // troféu sólido (mesmo do dashboard, simplificado)
        '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M6 3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v1h2.5a1 1 0 0 1 1 1V7a4 4 0 0 1-3.4 3.96A6 6 0 0 1 13 14.92V17h2a2 2 0 0 1 2 2v2H7v-2a2 2 0 0 1 2-2h2v-2.08A6 6 0 0 1 6.9 10.96 4 4 0 0 1 3.5 7V5a1 1 0 0 1 1-1H6V3Zm0 3H5.5v1a2 2 0 0 0 1.5 1.94V6Zm12 0v2.94A2 2 0 0 0 19.5 7V6H18Z"/></svg>',
        // sparkle/quatro-pontas
        '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l1.6 6.4L20 10l-6.4 1.6L12 18l-1.6-6.4L4 10l6.4-1.6L12 2Z"/></svg>',
        // raio
        '<svg viewBox="0 0 24 24" fill="currentColor"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10"/></svg>',
        // coroa
        '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M3 6l4.5 4 4.5-7 4.5 7L21 6l-2 13H5L3 6Zm2 15h14v-1H5v1Z"/></svg>',
        // medalha (círculo + fita)
        '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M7 2l3 6 2-1 2 1 3-6h-3l-2 4-2-4H7Zm5 8a6 6 0 1 0 0 12 6 6 0 0 0 0-12Zm0 3a3 3 0 1 1 0 6 3 3 0 0 1 0-6Z"/></svg>'
    ];
    var COLORS = ['#14F195', '#0FD585', '#FFD24A', '#FFFFFF', '#7fecc0', '#FFB547'];

    function spawnPiece(layer) {
        var el = document.createElement('span');
        el.className = 'mc-welcome__piece';
        var svg = SHAPES[Math.floor(Math.random() * SHAPES.length)];
        el.innerHTML = svg;

        var size = 16 + Math.random() * 28; // 16–44px
        var startX = Math.random() * 100;   // %
        var driftX = (Math.random() * 60 - 30) + 'vw';
        var rot = (Math.random() * 720 + 180) + 'deg';
        var dur = (5 + Math.random() * 5);  // 5–10s
        var delay = Math.random() * 4;      // 0–4s
        var color = COLORS[Math.floor(Math.random() * COLORS.length)];

        el.style.left = startX + '%';
        el.style.width = size + 'px';
        el.style.height = size + 'px';
        el.style.color = color;
        el.style.animationDuration = dur + 's';
        el.style.animationDelay = delay + 's';
        el.style.setProperty('--mc-cx', driftX);
        el.style.setProperty('--mc-rot', rot);

        layer.appendChild(el);
    }

    function fireConfettiBurst() {
        // Reusa o admin-confetti.js se disponível, senão faz um burst rápido aqui.
        var layer = document.createElement('div');
        layer.className = 'confetti-layer';
        document.body.appendChild(layer);
        var colors = ['#14F195', '#0FD585', '#FFD24A', '#F5F6F7', '#FFB547'];
        var count = 60;
        var vw = window.innerWidth || 1024;
        for (var i = 0; i < count; i++) {
            var p = document.createElement('span');
            p.className = 'confetti-piece';
            var startX = Math.random() * vw;
            p.style.left = startX + 'px';
            p.style.background = colors[i % colors.length];
            p.style.setProperty('--cx', (Math.random() * 320 - 160) + 'px');
            p.style.setProperty('--rot', (Math.random() * 1080 + 360) + 'deg');
            p.style.animationDelay = (Math.random() * 400) + 'ms';
            if (i % 3 === 0) { p.style.borderRadius = '999px'; p.style.width = '8px'; p.style.height = '8px'; }
            layer.appendChild(p);
        }
        setTimeout(function () { if (layer.parentNode) layer.parentNode.removeChild(layer); }, 3800);
    }

    function start() {
        var name = document.body.getAttribute('data-welcome-name') || 'admin';
        var hitGoal = document.body.getAttribute('data-welcome-hit') === '1';
        var streak = parseInt(document.body.getAttribute('data-welcome-streak') || '0', 10);
        var delta  = parseInt(document.body.getAttribute('data-welcome-delta')  || '0', 10);

        var subParts = ['Olá, <strong>' + name + '</strong> · seu painel está pronto.'];
        var extras = '';
        if (streak >= 3) {
            extras += '<span class="mc-welcome__chip mc-welcome__chip--streak">' +
                '<svg viewBox="0 0 24 24" fill="currentColor" width="14" height="14"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10"/></svg>' +
                '<strong>' + streak + ' dias seguidos</strong> batendo a meta' +
                '</span>';
        }
        if (delta > 0) {
            extras += '<span class="mc-welcome__chip mc-welcome__chip--delta">' +
                '<svg viewBox="0 0 24 24" fill="currentColor" width="14" height="14"><path d="M6 3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v1h2.5a1 1 0 0 1 1 1V7a4 4 0 0 1-3.4 3.96A6 6 0 0 1 13 14.92V17h2a2 2 0 0 1 2 2v2H7v-2a2 2 0 0 1 2-2h2v-2.08A6 6 0 0 1 6.9 10.96 4 4 0 0 1 3.5 7V5a1 1 0 0 1 1-1H6V3Z"/></svg>' +
                'mês atual <strong>+' + delta + ' cliques</strong> acima do recorde' +
                '</span>';
        }
        var celebrate = hitGoal || streak >= 3 || delta > 0;

        var layer = document.createElement('div');
        layer.className = 'mc-welcome' + (celebrate ? ' is-celebrate' : '');
        layer.setAttribute('aria-hidden', 'true');
        layer.innerHTML =
            '<div class="mc-welcome__hero">' +
                '<div class="mc-welcome__badge">' +
                    '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M6 3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v1h2.5a1 1 0 0 1 1 1V7a4 4 0 0 1-3.4 3.96A6 6 0 0 1 13 14.92V17h2a2 2 0 0 1 2 2v2H7v-2a2 2 0 0 1 2-2h2v-2.08A6 6 0 0 1 6.9 10.96 4 4 0 0 1 3.5 7V5a1 1 0 0 1 1-1H6V3Zm0 3H5.5v1a2 2 0 0 0 1.5 1.94V6Zm12 0v2.94A2 2 0 0 0 19.5 7V6H18Z"/></svg>' +
                '</div>' +
                '<h2 class="mc-welcome__title">' + (celebrate ? 'Você está em chamas!' : 'Bem-vindo de volta') + '</h2>' +
                '<p class="mc-welcome__sub">' + subParts.join(' ') + '</p>' +
                (extras ? '<div class="mc-welcome__chips">' + extras + '</div>' : '') +
            '</div>' +
            '<div class="mc-welcome__rain" aria-hidden="true"></div>';
        document.body.appendChild(layer);

        var rain = layer.querySelector('.mc-welcome__rain');
        var spawned = 0;
        var SPAWN_INTERVAL = 180;
        var iv = setInterval(function () {
            spawnPiece(rain);
            spawned++;
            if (spawned > Math.floor((DURATION - 1500) / SPAWN_INTERVAL)) clearInterval(iv);
        }, SPAWN_INTERVAL);

        // Confete extra quando bateu meta OU tem streak/delta
        if (celebrate) setTimeout(fireConfettiBurst, 400);

        // Permite dispensar com clique/Esc
        function dismiss() {
            if (!layer.parentNode) return;
            layer.classList.add('is-leaving');
            setTimeout(function () { if (layer.parentNode) layer.parentNode.removeChild(layer); }, 500);
            clearInterval(iv);
            window.removeEventListener('keydown', onKey);
        }
        function onKey(e) { if (e.key === 'Escape') dismiss(); }
        layer.addEventListener('click', dismiss);
        window.addEventListener('keydown', onKey);
        setTimeout(dismiss, DURATION);
    }

    function init() { if (shouldFire()) start(); }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
    else init();
})();
