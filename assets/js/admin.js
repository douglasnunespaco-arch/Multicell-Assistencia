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
})();
