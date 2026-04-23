/**
 * admin-confetti.js — Dispara confete leve uma vez por sessão quando
 * alguma meta de período é batida. Vanilla, sem dependências.
 */
(function () {
    'use strict';

    function shouldFire() {
        var hit = document.querySelector('.period-card[data-goal-hit="1"]');
        if (!hit) return false;
        try {
            if (sessionStorage.getItem('mc_confetti_shown') === '1') return false;
            sessionStorage.setItem('mc_confetti_shown', '1');
        } catch (e) { /* ignore storage errors */ }
        // Respeita reduced motion
        if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return false;
        return true;
    }

    function burst() {
        var layer = document.createElement('div');
        layer.className = 'confetti-layer';
        document.body.appendChild(layer);

        var colors = ['#14F195', '#0FD585', '#F5F6F7', '#7fecc0'];
        var count  = 42;
        var vw     = window.innerWidth || 1024;

        for (var i = 0; i < count; i++) {
            var p = document.createElement('span');
            p.className = 'confetti-piece';
            var startX = Math.random() * vw;
            var driftX = (Math.random() * 280 - 140) + 'px';
            var rot    = (Math.random() * 1080 + 360) + 'deg';
            var delay  = (Math.random() * 280) + 'ms';
            p.style.left  = startX + 'px';
            p.style.background = colors[i % colors.length];
            p.style.setProperty('--cx',  driftX);
            p.style.setProperty('--rot', rot);
            p.style.animationDelay = delay;
            if (i % 3 === 0) { p.style.borderRadius = '999px'; p.style.width = '8px'; p.style.height = '8px'; }
            layer.appendChild(p);
        }

        setTimeout(function () { if (layer && layer.parentNode) layer.parentNode.removeChild(layer); }, 3800);
    }

    function init() { if (shouldFire()) burst(); }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
