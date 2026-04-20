/* Multi Cell · public.js · Fase 2
   Vanilla JS leve: tema, header scrolled, drawer mobile, slider hero,
   reveal on scroll e hooks de analytics (beacon p/ /api/track quando existir). */

(function () {
    'use strict';

    // ---------- Theme toggle ----------
    var root = document.documentElement;
    var storageKey = 'mc_theme';
    var saved = null;
    try { saved = localStorage.getItem(storageKey); } catch (e) {}

    var prefersLight = window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches;
    var serverDefault = root.getAttribute('data-theme') || 'dark';
    var initial = saved || serverDefault;
    applyTheme(initial);

    function applyTheme(t) {
        root.setAttribute('data-theme', t);
        document.querySelectorAll('[data-theme-toggle]').forEach(function (b) {
            b.setAttribute('aria-pressed', t === 'light' ? 'true' : 'false');
            b.setAttribute('aria-label', 'Alternar para tema ' + (t === 'light' ? 'escuro' : 'claro'));
        });
    }

    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-theme-toggle]');
        if (!btn) return;
        var cur = root.getAttribute('data-theme') || 'dark';
        var next = cur === 'dark' ? 'light' : 'dark';
        applyTheme(next);
        try { localStorage.setItem(storageKey, next); } catch (err) {}
    });

    // ---------- Header scrolled state ----------
    var header = document.querySelector('.site-header');
    if (header) {
        var onScroll = function () {
            if (window.scrollY > 8) header.classList.add('is-scrolled');
            else header.classList.remove('is-scrolled');
        };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    // ---------- Mobile drawer ----------
    var drawer = document.querySelector('[data-drawer]');
    document.addEventListener('click', function (e) {
        if (e.target.closest('[data-drawer-open]')) {
            drawer && drawer.classList.add('is-open');
            document.body.style.overflow = 'hidden';
        }
        if (e.target.closest('[data-drawer-close]') || e.target.closest('.mobile-drawer nav a')) {
            drawer && drawer.classList.remove('is-open');
            document.body.style.overflow = '';
        }
    });

    // ---------- Hero slider ----------
    var slider = document.querySelector('[data-hero-slider]');
    if (slider) {
        var slides = slider.querySelectorAll('.hero__slide');
        var dots   = slider.querySelectorAll('.hero__dots button');
        var cur = 0, timer = null;
        var autoplayMs = parseInt(slider.getAttribute('data-autoplay') || '6000', 10);

        function go(i) {
            cur = (i + slides.length) % slides.length;
            slides.forEach(function (s, idx) { s.classList.toggle('is-active', idx === cur); });
            dots.forEach(function (d, idx) { d.classList.toggle('is-active', idx === cur); });
        }
        function next() { go(cur + 1); }
        function start() {
            if (slides.length <= 1) return;
            stop();
            timer = setInterval(next, autoplayMs);
        }
        function stop() { if (timer) { clearInterval(timer); timer = null; } }

        dots.forEach(function (d, idx) {
            d.addEventListener('click', function () { go(idx); start(); });
        });
        slider.addEventListener('mouseenter', stop);
        slider.addEventListener('mouseleave', start);
        // Pausa quando fora da viewport
        if ('IntersectionObserver' in window) {
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (ent) { ent.isIntersecting ? start() : stop(); });
            }, { threshold: 0.2 });
            io.observe(slider);
        } else {
            start();
        }
    }

    // ---------- Reveal on scroll ----------
    if ('IntersectionObserver' in window) {
        var rIO = new IntersectionObserver(function (entries) {
            entries.forEach(function (ent) {
                if (ent.isIntersecting) {
                    ent.target.classList.add('is-visible');
                    rIO.unobserve(ent.target);
                }
            });
        }, { threshold: 0.12 });
        document.querySelectorAll('[data-reveal]').forEach(function (el) { rIO.observe(el); });
    } else {
        document.querySelectorAll('[data-reveal]').forEach(function (el) { el.classList.add('is-visible'); });
    }

    // ---------- Analytics hook (best-effort) ----------
    // Endpoint /api/track é implementado na Fase 5. Aqui apenas enviamos;
    // se a rota ainda não existir, o beacon falha silenciosamente.
    function track(ev, data) {
        try {
            var payload = JSON.stringify({
                event_type: ev,
                page_path: location.pathname,
                source: (data && data.source) || null,
                ref_type: (data && data.ref_type) || null,
                ref_id:   (data && data.ref_id)   || null,
                meta:     (data && data.meta)     || null
            });
            if (navigator.sendBeacon) {
                var blob = new Blob([payload], { type: 'application/json' });
                navigator.sendBeacon('/api/track', blob);
            } else {
                fetch('/api/track', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: payload,
                    keepalive: true
                }).catch(function () {});
            }
        } catch (e) {}
    }

    document.addEventListener('click', function (e) {
        var el = e.target.closest('[data-track]');
        if (!el) return;
        track(el.getAttribute('data-track'), {
            source:   el.getAttribute('data-track-source'),
            ref_type: el.getAttribute('data-track-ref-type'),
            ref_id:   el.getAttribute('data-track-ref-id')
        });
    });

    window.MultiCell = { track: track, setTheme: applyTheme };
})();
