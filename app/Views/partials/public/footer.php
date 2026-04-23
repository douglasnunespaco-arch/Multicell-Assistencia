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
            <p>Reparo rápido, peças selecionadas e garantia real. Atendimento em Várzea Grande/MT.</p>
            <div class="footer-social">
                <?php if ($ig): ?>
                    <a href="<?= e($ig) ?>" target="_blank" rel="noopener" aria-label="Instagram"
                       data-track="cta_click" data-track-source="footer_instagram">
                        <?= icon('instagram', 18) ?>
                    </a>
                <?php endif; ?>
                <?php if ($fb): ?>
                    <a href="<?= e($fb) ?>" target="_blank" rel="noopener" aria-label="Facebook"
                       data-track="cta_click" data-track-source="footer_facebook">
                        <?= icon('facebook', 18) ?>
                    </a>
                <?php endif; ?>
                <?php if ($tt): ?>
                    <a href="<?= e($tt) ?>" target="_blank" rel="noopener" aria-label="TikTok"
                       data-track="cta_click" data-track-source="footer_tiktok">
                        <?= icon('tiktok', 18) ?>
                    </a>
                <?php endif; ?>
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
            <a href="<?= whatsapp_link('footer') ?>" data-track="whatsapp_click" data-track-source="footer">
                <?= icon('whatsapp', 14, 'inline-ic') ?> WhatsApp
            </a>
            <a href="/go/phone" data-track="phone_click" data-track-source="footer">
                <?= icon('phone', 14, 'inline-ic') ?> <?= e(\App\Models\Setting::get('phone', '(00) 0000-0000')) ?>
            </a>
            <a href="mailto:<?= e(\App\Models\Setting::get('email', 'contato@multicell.local')) ?>">
                <?= icon('mail', 14, 'inline-ic') ?> <?= e(\App\Models\Setting::get('email', 'contato@multicell.local')) ?>
            </a>
            <?php if ($branch): ?>
                <a href="/go/map" class="footer-address" data-track="map_click" data-track-source="footer">
                    <?= icon('pin', 16, 'inline-ic') ?>
                    <div>
                        <?= e($branch['address']) ?><br>
                        <?= e($branch['city']) ?>/<?= e($branch['state']) ?>
                    </div>
                </a>
            <?php endif; ?>
        </div>

        <div class="footer-col">
            <h4>Atendimento</h4>
            <p style="color:var(--fg-1);margin:0 0 8px;font-size:14px;display:flex;gap:8px;align-items:flex-start;">
                <?= icon('clock', 14, 'inline-ic') ?>
                <span><?= e(\App\Models\Setting::get('hours_default', 'Seg a Sex 8h–18h · Sáb 8h–12h')) ?></span>
            </p>
            <a href="/links">
                <?= icon('globe', 14, 'inline-ic') ?> Links rápidos
            </a>
        </div>
    </div>

    <div class="container footer-bottom">
        <span>© <?= date('Y') ?> <?= e(\App\Models\Setting::get('site_name', 'Multi Cell Assistência Técnica')) ?>. Todos os direitos reservados.</span>
        <span class="footer-bottom__right">
            Feito com cuidado em Várzea Grande/MT
            <span class="footer-bottom__sep" aria-hidden="true">·</span>
            <a href="/admin/login" class="footer-admin-link" rel="nofollow" data-testid="footer-admin-link">Painel</a>
        </span>
    </div>
</footer>
