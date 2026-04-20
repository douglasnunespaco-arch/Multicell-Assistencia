<?php
/** @var array $service */
$href = '/assistencia-tecnica/' . $service['slug'];
$img  = $service['image_path'] ?? '';
$imgSrc = $img ? '/' . ltrim($img, '/') : service_image($service['slug'] ?? null, $service['icon'] ?? null);
?>
<article class="card card--service" data-reveal>
    <div class="card__media card__media--service">
        <img src="<?= e($imgSrc) ?>" alt="<?= e($service['name']) ?>" loading="lazy">
        <span class="card__media-badge"><?= service_icon($service['icon'] ?? null, 18) ?></span>
    </div>
    <div class="card__body">
        <h3 class="card__title"><?= e($service['name']) ?></h3>
        <p class="card__desc"><?= e($service['short_description'] ?? '') ?></p>
        <div class="card__footer">
            <span class="card__price">
                <?php if (!empty($service['price_from'])): ?>
                    <small>A partir de</small><?= e(money($service['price_from'])) ?>
                <?php else: ?>
                    <small>Consulte</small>no WhatsApp
                <?php endif; ?>
            </span>
            <a href="<?= e($href) ?>" class="btn btn--ghost btn--sm"
               data-track="service_click" data-track-ref-type="service" data-track-ref-id="<?= (int) $service['id'] ?>"
               data-track-source="card">
                Ver detalhes <?= icon('arrow-right', 14) ?>
            </a>
        </div>
    </div>
</article>
