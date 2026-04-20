<?php
$branch = \App\Models\Branch::primary();
$ig = \App\Models\Setting::get('instagram_url');
$fb = \App\Models\Setting::get('facebook_url');
$tt = \App\Models\Setting::get('tiktok_url');
?>
<footer class="site-footer">
    <div class="container footer-grid">
        <div class="footer-col footer-brand">
            <a href="/" class="brand">
                <span class="brand__mark">MC</span>
                <span class="brand__name">Multi Cell <small>Assistência Técnica</small></span>
            </a>
            <p>Reparo rápido, peças originais e garantia real. Atendimento em Várzea Grande/MT.</p>
            <div class="footer-social">
                <?php if ($ig): ?>
                    <a href="<?= e($ig) ?>" target="_blank" rel="noopener" aria-label="Instagram"
                       data-track="cta_click" data-track-source="footer_instagram">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                    </a>
                <?php endif; ?>
                <?php if ($fb): ?>
                    <a href="<?= e($fb) ?>" target="_blank" rel="noopener" aria-label="Facebook"
                       data-track="cta_click" data-track-source="footer_facebook">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 1 0-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.5-3.89 3.77-3.89 1.1 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.44 2.89h-2.34v6.99A10 10 0 0 0 22 12z"/></svg>
                    </a>
                <?php endif; ?>
                <?php if ($tt): ?>
                    <a href="<?= e($tt) ?>" target="_blank" rel="noopener" aria-label="TikTok"
                       data-track="cta_click" data-track-source="footer_tiktok">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19.6 6.32a5.3 5.3 0 0 1-3.15-1.02 5.28 5.28 0 0 1-2.1-3.7h-3.2v12.6a2.74 2.74 0 1 1-1.95-2.63V8.43a5.92 5.92 0 1 0 5.15 5.86V9.13a8.5 8.5 0 0 0 5.25 1.77V7.68a5.16 5.16 0 0 1-0-1.36z"/></svg>
                    </a>
                <?php endif; ?>
                <a href="<?= e(\App\Models\Setting::get('google_maps_url', '#')) ?>" target="_blank" rel="noopener" aria-label="Google Maps"
                   data-track="cta_click" data-track-source="footer_maps">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                </a>
            </div>
        </div>

        <div class="footer-col">
            <h4>Navegação</h4>
            <a href="/">Início</a>
            <a href="/assistencia-tecnica">Assistência</a>
            <a href="/produtos">Produtos</a>
            <a href="/promocoes">Promoções</a>
            <a href="/reservar">Reservar</a>
            <a href="/sobre">Sobre</a>
            <a href="/contato">Contato</a>
        </div>

        <div class="footer-col">
            <h4>Contato</h4>
            <a href="<?= whatsapp_link('footer') ?>" data-track="whatsapp_click" data-track-source="footer">WhatsApp</a>
            <a href="/go/phone" data-track="phone_click" data-track-source="footer">
                <?= e(\App\Models\Setting::get('phone', '(00) 0000-0000')) ?>
            </a>
            <a href="mailto:<?= e(\App\Models\Setting::get('email', 'contato@multicell.local')) ?>">
                <?= e(\App\Models\Setting::get('email', 'contato@multicell.local')) ?>
            </a>
            <?php if ($branch): ?>
                <a href="/go/map" data-track="map_click" data-track-source="footer">
                    <?= e($branch['address']) ?><br><?= e($branch['city']) ?>/<?= e($branch['state']) ?>
                </a>
            <?php endif; ?>
        </div>

        <div class="footer-col">
            <h4>Atendimento</h4>
            <p style="color:var(--fg-1);margin:0 0 8px;font-size:14px;">
                <?= e(\App\Models\Setting::get('hours_default', 'Seg a Sex 8h–18h · Sáb 8h–12h')) ?>
            </p>
            <a href="/links">Links rápidos</a>
        </div>
    </div>

    <div class="container footer-bottom">
        <span>© <?= date('Y') ?> <?= e(\App\Models\Setting::get('site_name', 'Multi Cell Assistência Técnica')) ?>. Todos os direitos reservados.</span>
        <span>Feito com cuidado em Várzea Grande/MT</span>
    </div>
</footer>
