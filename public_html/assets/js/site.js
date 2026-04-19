(function () {
    const navToggle = document.querySelector('.menu-toggle');
    const nav = document.querySelector('.site-nav');
    if (navToggle && nav) navToggle.addEventListener('click', () => nav.classList.toggle('open'));

    const track = (payload) => {
        const url = (window.APP_BASE_URL || '/') + 'track';
        const body = JSON.stringify(payload || {});
        if (navigator.sendBeacon) {
            navigator.sendBeacon(url, new Blob([body], { type: 'application/json' }));
            return;
        }
        fetch(url, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body }).catch(() => {});
    };

    track({ event_name: 'page_view', page_path: window.location.pathname });

    document.querySelectorAll('.trackable').forEach((element) => {
        element.addEventListener('click', function () {
            track({
                event_name: this.dataset.event || 'cta_click',
                cta_label: this.dataset.cta || this.textContent.trim(),
                entity_type: this.dataset.entityType || '',
                entity_label: this.dataset.entityLabel || '',
                page_path: window.location.pathname,
                source_label: document.referrer || 'direct'
            });
        });
    });
})();
