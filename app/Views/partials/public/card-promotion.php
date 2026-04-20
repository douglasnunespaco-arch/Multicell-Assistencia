<?php
/** @var array $promo */
$href = '/promocoes/' . $promo['slug'];
$msg = 'Olá! Quero aproveitar a promoção: ' . $promo['title'];
$waHref = '/go/whatsapp?src=promo_card&msg=' . urlencode($msg);
$deadline = '';
if (!empty($promo['ends_at'])) {
    try {
        $end = new DateTimeImmutable($promo['ends_at']);
        $now = new DateTimeImmutable('today');
        $diff = (int) $now->diff($end)->format('%r%a');
        if ($diff >= 0 && $diff <= 30) {
            $deadline = $diff === 0 ? 'Encerra hoje' : ('Encerra em ' . $diff . ' ' . ($diff === 1 ? 'dia' : 'dias'));
        }
    } catch (\Throwable $e) {}
}
$img = $promo['image_path'] ?? '';
$imgSrc = $img ? '/' . ltrim($img, '/') : promo_image($promo['slug'] ?? null);
?>
<article class="promo-card" data-reveal>
    <div class="promo-card__media">
        <img src="<?= e($imgSrc) ?>" alt="<?= e($promo['title']) ?>" loading="lazy">
        <span class="promo-card__tag"><?= icon('tag', 14) ?> Promo</span>
    </div>
    <div class="promo-card__body">
        <?php if ($deadline): ?><span class="promo-card__deadline"><?= icon('clock', 12, 'inline-ic') ?> <?= e($deadline) ?></span><?php endif; ?>
        <h3 class="promo-card__title"><?= e($promo['title']) ?></h3>
        <?php if (!empty($promo['description'])): ?>
            <p style="margin:0;color:var(--fg-1);font-size:14px;"><?= e($promo['description']) ?></p>
        <?php endif; ?>
        <?php if (!empty($promo['new_price'])): ?>
            <div class="promo-card__prices">
                <?php if (!empty($promo['old_price'])): ?>
                    <span class="promo-card__old"><?= e(money($promo['old_price'])) ?></span>
                <?php endif; ?>
                <span class="promo-card__price"><?= e(money($promo['new_price'])) ?></span>
            </div>
        <?php endif; ?>
        <div class="promo-card__cta" style="display:flex;gap:10px;flex-wrap:wrap;">
            <a href="<?= e($waHref) ?>" class="btn btn--primary"
               data-track="promotion_click" data-track-ref-type="promotion" data-track-ref-id="<?= (int) $promo['id'] ?>"
               data-track-source="card_cta">
                <?= icon('whatsapp', 16) ?> <?= e($promo['cta_label'] ?: 'Aproveitar no WhatsApp') ?>
            </a>
            <a href="<?= e($href) ?>" class="btn btn--ghost">Ver detalhes</a>
        </div>
    </div>
</article>
