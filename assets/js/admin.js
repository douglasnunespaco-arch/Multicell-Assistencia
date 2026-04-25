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
})();
