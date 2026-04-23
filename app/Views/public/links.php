<?php
/** @var ?array $branch */
$siteName = \App\Models\Setting::get('site_name', 'Multi Cell');
$tagline  = \App\Models\Setting::get('tagline', 'Assistência técnica + acessórios premium');
$logoPath = \App\Models\Setting::get('logo_path', '');

// Links dinâmicos do painel (Fase C1). Se a tabela ainda não existir, falha silenciosamente.
$bioLinks = [];
try { $bioLinks = \App\Models\BioLink::active(); } catch (\Throwable $e) { $bioLinks = []; }

// Extrai iniciais para avatar fallback (MC para Multi Cell)
$initials = '';
foreach (preg_split('/\s+/', trim((string) $siteName)) as $w) {
    if ($w !== '' && mb_strlen($initials) < 2) $initials .= mb_strtoupper(mb_substr($w, 0, 1));
}
if ($initials === '') $initials = 'MC';

// Divide o site_name em "marca forte" + "descritor" para a hierarquia do header.
// Ex.: "Multi Cell Assistência Técnica" → ["Multi Cell", "Assistência Técnica"].
$parts = preg_split('/\s+/', trim((string) $siteName)) ?: [];
$brandMain = '';
$brandSub  = '';
if (count($parts) >= 3) {
    $brandMain = implode(' ', array_slice($parts, 0, 2));
    $brandSub  = implode(' ', array_slice($parts, 2));
} else {
    $brandMain = (string) $siteName;
    $brandSub  = '';
}
?>
<main class="bio-shell">
    <div class="bio-bg" aria-hidden="true">
        <span class="bio-bg__glow bio-bg__glow--1"></span>
        <span class="bio-bg__glow bio-bg__glow--2"></span>
        <span class="bio-bg__grid"></span>
    </div>

    <section class="bio-inner" data-reveal>
        <header class="bio-head">
            <div class="bio-avatar">
                <?php if ($logoPath): ?>
                    <img src="/<?= e(ltrim((string) $logoPath, '/')) ?>" alt="<?= e($siteName) ?>">
                <?php else: ?>
                    <span class="bio-avatar__text"><?= e($initials) ?></span>
                <?php endif; ?>
                <span class="bio-avatar__badge" aria-hidden="true"><?= icon('check', 12) ?></span>
            </div>
            <h1 class="bio-title"><?= e($brandMain) ?></h1>
            <?php if ($brandSub !== ''): ?>
                <p class="bio-title-sub"><?= e($brandSub) ?></p>
            <?php endif; ?>
            <p class="bio-sub"><?= e($tagline) ?></p>
        </header>

        <nav class="bio-stack" aria-label="Links principais">
            <?php if (!empty($bioLinks)): foreach ($bioLinks as $bl):
                $iconSlug = trim((string) ($bl['icon'] ?? ''));
                $openNew  = !empty($bl['open_new_tab']);
                $isExt    = preg_match('~^https?://~i', (string) $bl['url']) === 1;
                $target   = $openNew || $isExt ? ' target="_blank" rel="noopener"' : '';
                $type     = $bl['type'] ?? 'link';
                $style    = $bl['style'] ?? 'default';
                $h        = (int) ($bl['height_px'] ?? 0);
                $img      = trim((string) ($bl['image_path'] ?? ''));
                $trackSrc = 'links_' . preg_replace('~[^a-z0-9]+~', '_', strtolower($bl['title']));
            ?>
                <?php if ($type === 'banner' && $img): ?>
                    <a href="<?= e($bl['url']) ?>"
                       class="bio-banner"
                       data-track="cta_click" data-track-source="<?= e($trackSrc) ?>"<?= $target ?>
                       <?= $h > 0 ? 'style="--bio-h: ' . (int) $h . 'px"' : '' ?>>
                        <img src="/<?= e(ltrim($img, '/')) ?>" alt="<?= e($bl['title']) ?>" loading="lazy">
                    </a>
                <?php else: ?>
                    <a href="<?= e($bl['url']) ?>"
                       class="bio-link bio-link--<?= e($style === 'highlight' ? 'highlight' : 'default') ?>"
                       data-track="cta_click" data-track-source="<?= e($trackSrc) ?>"<?= $target ?>
                       <?= $h > 0 ? 'style="--bio-h: ' . (int) $h . 'px"' : '' ?>>
                        <span class="bio-link__icon" aria-hidden="true">
                            <?php if ($iconSlug): ?><?= icon($iconSlug, 22) ?><?php endif; ?>
                        </span>
                        <span class="bio-link__text">
                            <strong class="bio-link__title"><?= e($bl['title']) ?></strong>
                            <?php if (!empty($bl['subtitle'])): ?>
                                <small class="bio-link__sub"><?= e($bl['subtitle']) ?></small>
                            <?php endif; ?>
                        </span>
                        <span class="bio-link__arrow" aria-hidden="true"><?= icon('arrow-right', 16) ?></span>
                    </a>
                <?php endif; ?>
            <?php endforeach; else: ?>
                <a href="<?= whatsapp_link('links_whatsapp') ?>" class="bio-link bio-link--highlight" data-track="whatsapp_click" data-track-source="links_main">
                    <span class="bio-link__icon" aria-hidden="true"><?= icon('whatsapp', 22) ?></span>
                    <span class="bio-link__text">
                        <strong class="bio-link__title">WhatsApp direto</strong>
                        <small class="bio-link__sub">Fale agora com a Multi Cell</small>
                    </span>
                    <span class="bio-link__arrow" aria-hidden="true"><?= icon('arrow-right', 16) ?></span>
                </a>
            <?php endif; ?>
        </nav>
    </section>

    <footer class="bio-foot">
        <a href="/" class="bio-back" data-track="cta_click" data-track-source="links_back_to_site">Ir para o site</a>
    </footer>
</main>
