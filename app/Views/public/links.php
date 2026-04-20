<?php /** @var ?array $branch */
$ig = \App\Models\Setting::get('instagram_url');
$fb = \App\Models\Setting::get('facebook_url');
$tt = \App\Models\Setting::get('tiktok_url');
$reviews = \App\Models\Setting::get('google_reviews_url');
?>
<main class="links-shell">
    <div class="links-card" data-reveal>
        <div class="links-avatar">MC</div>
        <h1>Multi Cell</h1>
        <p>Assistência Técnica de Celulares · Várzea Grande/MT</p>

        <div class="links-list">
            <a href="<?= whatsapp_link('links_whatsapp') ?>" class="link-btn link-btn--primary" data-track="whatsapp_click" data-track-source="links_main">
                <?= icon('phone', 18) ?> Falar no WhatsApp
            </a>
            <a href="/reservar" class="link-btn" data-track="cta_click" data-track-source="links_reserve">
                <?= icon('clock', 18) ?> Reservar atendimento
            </a>
            <a href="/produtos" class="link-btn" data-track="cta_click" data-track-source="links_products">
                <?= icon('tag', 18) ?> Ver produtos e acessórios
            </a>
            <a href="/promocoes" class="link-btn" data-track="cta_click" data-track-source="links_promos">
                <?= icon('bolt', 18) ?> Promoções ativas
            </a>
            <a href="/assistencia-tecnica" class="link-btn" data-track="cta_click" data-track-source="links_services">
                <?= icon('wrench', 18) ?> Nossos serviços
            </a>
            <?php if ($ig): ?>
                <a href="<?= e($ig) ?>" target="_blank" rel="noopener" class="link-btn" data-track="cta_click" data-track-source="links_instagram"><?= icon('instagram', 18) ?> Instagram</a>
            <?php endif; ?>
            <?php if ($fb): ?>
                <a href="<?= e($fb) ?>" target="_blank" rel="noopener" class="link-btn" data-track="cta_click" data-track-source="links_facebook">Facebook</a>
            <?php endif; ?>
            <?php if ($tt): ?>
                <a href="<?= e($tt) ?>" target="_blank" rel="noopener" class="link-btn" data-track="cta_click" data-track-source="links_tiktok">TikTok</a>
            <?php endif; ?>
            <a href="/go/map" class="link-btn" data-track="map_click" data-track-source="links_map">
                <?= icon('pin', 18) ?> Como chegar
            </a>
            <?php if ($reviews): ?>
                <a href="<?= e($reviews) ?>" target="_blank" rel="noopener" class="link-btn" data-track="cta_click" data-track-source="links_reviews">
                    <?= icon('star', 18) ?> Avaliações no Google
                </a>
            <?php endif; ?>
        </div>

        <div class="links-footer">
            © <?= date('Y') ?> Multi Cell · <?= e($branch['city'] ?? 'Várzea Grande') ?>/<?= e($branch['state'] ?? 'MT') ?>
        </div>
    </div>
</main>
