<?php
/** @var array $product */
$href   = '/produtos/' . $product['slug'];
$price  = $product['price'] ?? null;
$promo  = $product['promo_price'] ?? null;
$hasPromo = $promo !== null && $price !== null && (float) $promo < (float) $price;
$img = $product['image_path'] ?? '';
$msg = 'Olá! Tenho interesse no produto: ' . $product['name'] . '. Poderia me passar mais informações?';
$waHref = '/go/whatsapp?src=product_card&msg=' . urlencode($msg);
?>
<article class="card" data-reveal>
    <?php if ($hasPromo): ?><span class="card__badge">Promo</span><?php endif; ?>
    <div class="card__media <?= $img ? '' : 'card__media--empty' ?>">
        <?php if ($img): ?>
            <img src="<?= e('/' . ltrim($img, '/')) ?>" alt="<?= e($product['name']) ?>" loading="lazy">
        <?php else: ?>
            <?= icon('image', 44) ?>
        <?php endif; ?>
    </div>
    <div class="card__body">
        <h3 class="card__title"><?= e($product['name']) ?></h3>
        <p class="card__desc"><?= e($product['short_description'] ?? '') ?></p>
        <div class="card__footer">
            <span class="card__price">
                <?php if ($hasPromo): ?>
                    <small>De <?= e(money($price)) ?> por</small>
                    <?= e(money($promo)) ?>
                <?php elseif ($price !== null): ?>
                    <small>Preço</small><?= e(money($price)) ?>
                <?php else: ?>
                    <small>Consulte</small>WhatsApp
                <?php endif; ?>
            </span>
            <a href="<?= e($waHref) ?>" class="btn btn--primary btn--sm"
               data-track="product_click" data-track-ref-type="product" data-track-ref-id="<?= (int) $product['id'] ?>"
               data-track-source="card">
                Consultar
            </a>
        </div>
    </div>
</article>
