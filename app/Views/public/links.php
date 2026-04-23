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
                        <span class="link-btn__icon" aria-hidden="true">
                            <?php if ($iconSlug): ?><?= icon($iconSlug, 18) ?><?php endif; ?>
                        </span>
                        <span class="link-btn__label"><?= e($bl['title']) ?></span>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback estático -->
                <a href="<?= whatsapp_link('links_whatsapp') ?>" class="link-btn" data-track="whatsapp_click" data-track-source="links_main">
                    <span class="link-btn__icon" aria-hidden="true"><?= icon('whatsapp', 18) ?></span>
                    <span class="link-btn__label">Falar no WhatsApp</span>
                </a>
                <a href="/produtos" class="link-btn" data-track="cta_click" data-track-source="links_products">
                    <span class="link-btn__icon" aria-hidden="true"><?= icon('package', 18) ?></span>
                    <span class="link-btn__label">Ver produtos e acessórios</span>
                </a>
                <a href="/promocoes" class="link-btn" data-track="cta_click" data-track-source="links_promos">
                    <span class="link-btn__icon" aria-hidden="true"><?= icon('tag', 18) ?></span>
                    <span class="link-btn__label">Promoções ativas</span>
                </a>
                <a href="/go/map" class="link-btn" data-track="map_click" data-track-source="links_map">
                    <span class="link-btn__icon" aria-hidden="true"><?= icon('map', 18) ?></span>
                    <span class="link-btn__label">Como chegar</span>
                </a>
                <?php if ($ig): ?>
                    <a href="<?= e($ig) ?>" target="_blank" rel="noopener" class="link-btn" data-track="cta_click" data-track-source="links_instagram">
                        <span class="link-btn__icon" aria-hidden="true"><?= icon('instagram', 18) ?></span>
                        <span class="link-btn__label">Instagram</span>
                    </a>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <a href="/" class="link-btn link-btn--back" data-track="cta_click" data-track-source="links_back_to_site">
            <span class="link-btn__icon" aria-hidden="true"><?= icon('arrow-right', 16) ?></span>
            <span class="link-btn__label">Voltar ao site</span>
        </a>

        <div class="links-footer">
            © <?= date('Y') ?> Multi Cell · <?= e($branch['city'] ?? 'Várzea Grande') ?>/<?= e($branch['state'] ?? 'MT') ?>
        </div>
    </div>
</main>
