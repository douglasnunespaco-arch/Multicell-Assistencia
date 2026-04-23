<?php
/** @var ?array $branch */
$ig       = \App\Models\Setting::get('instagram_url');
$fb       = \App\Models\Setting::get('facebook_url');
$tt       = \App\Models\Setting::get('tiktok_url');
$reviews  = \App\Models\Setting::get('google_reviews_url');

// Links dinâmicos do painel (Fase C1). Se a tabela ainda não existir, falha silenciosamente.
$bioLinks = [];
try { $bioLinks = \App\Models\BioLink::active(); } catch (\Throwable $e) { $bioLinks = []; }
?>
<main class="links-shell">
    <div class="links-card" data-reveal>
        <div class="links-avatar">MC</div>
        <h1>Multi Cell</h1>
        <p>Assistência Técnica de Celulares · Várzea Grande/MT</p>

        <div class="links-list">
            <?php if (!empty($bioLinks)): ?>
                <?php foreach ($bioLinks as $bl):
                    $iconSlug = trim((string) ($bl['icon'] ?? ''));
                    $openNew  = !empty($bl['open_new_tab']);
                    $isExt    = preg_match('~^https?://~i', (string) $bl['url']) === 1;
                    $target   = $openNew || $isExt ? ' target="_blank" rel="noopener"' : '';
                ?>
                    <a href="<?= e($bl['url']) ?>" class="link-btn" data-track="cta_click" data-track-source="links_<?= e($iconSlug ?: 'item') ?>"<?= $target ?>>
                        <?php if ($iconSlug): ?><?= icon($iconSlug, 18) ?><?php endif; ?>
                        <?= e($bl['title']) ?>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback: conteúdo estático (usado antes da Fase C1 ou caso nenhum link esteja ativo) -->
                <a href="<?= whatsapp_link('links_whatsapp') ?>" class="link-btn link-btn--primary" data-track="whatsapp_click" data-track-source="links_main">
                    <?= icon('whatsapp', 18) ?> Falar no WhatsApp
                </a>
                <a href="/reservar" class="link-btn" data-track="cta_click" data-track-source="links_reserve">
                    <?= icon('calendar', 18) ?> Reservar atendimento
                </a>
                <a href="/produtos" class="link-btn" data-track="cta_click" data-track-source="links_products">
                    <?= icon('package', 18) ?> Ver produtos e acessórios
                </a>
                <a href="/promocoes" class="link-btn" data-track="cta_click" data-track-source="links_promos">
                    <?= icon('tag', 18) ?> Promoções ativas
                </a>
                <a href="/assistencia-tecnica" class="link-btn" data-track="cta_click" data-track-source="links_services">
                    <?= icon('wrench', 18) ?> Nossos serviços
                </a>
                <?php if ($ig): ?>
                    <a href="<?= e($ig) ?>" target="_blank" rel="noopener" class="link-btn" data-track="cta_click" data-track-source="links_instagram"><?= icon('instagram', 18) ?> Instagram</a>
                <?php endif; ?>
                <?php if ($fb): ?>
                    <a href="<?= e($fb) ?>" target="_blank" rel="noopener" class="link-btn" data-track="cta_click" data-track-source="links_facebook"><?= icon('facebook', 18) ?> Facebook</a>
                <?php endif; ?>
                <?php if ($tt): ?>
                    <a href="<?= e($tt) ?>" target="_blank" rel="noopener" class="link-btn" data-track="cta_click" data-track-source="links_tiktok"><?= icon('tiktok', 18) ?> TikTok</a>
                <?php endif; ?>
                <a href="/go/map" class="link-btn" data-track="map_click" data-track-source="links_map">
                    <?= icon('map', 18) ?> Como chegar
                </a>
                <?php if ($reviews): ?>
                    <a href="<?= e($reviews) ?>" target="_blank" rel="noopener" class="link-btn" data-track="cta_click" data-track-source="links_reviews">
                        <?= icon('star', 18) ?> Avaliações no Google
                    </a>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <div class="links-footer">
            © <?= date('Y') ?> Multi Cell · <?= e($branch['city'] ?? 'Várzea Grande') ?>/<?= e($branch['state'] ?? 'MT') ?>
        </div>
    </div>
</main>
