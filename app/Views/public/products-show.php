<?php
/** @var array $product; array $related */
$msg = 'Olá! Tenho interesse no produto: ' . $product['name'] . '. Poderia me passar mais informações?';
$waHref = '/go/whatsapp?src=product_detail&msg=' . urlencode($msg);
$price = $product['price'] ?? null;
$promo = $product['promo_price'] ?? null;
$hasPromo = $promo !== null && $price !== null && (float) $promo < (float) $price;
?>
<section class="page-banner">
    <div class="container">
        <nav class="breadcrumb">
            <a href="/">Início</a> / <a href="/produtos">Produtos</a>
            <?php if (!empty($product['category'])): ?>
                / <a href="/produtos?categoria=<?= urlencode($product['category']) ?>"><?= e($product['category']) ?></a>
            <?php endif; ?>
            / <span><?= e($product['name']) ?></span>
        </nav>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="detail-grid">
            <div class="detail-media">
                <?php if (!empty($product['image_path'])): ?>
                    <img src="<?= e('/' . ltrim($product['image_path'], '/')) ?>" alt="<?= e($product['name']) ?>">
                <?php else: ?>
                    <?= icon('image', 64) ?>
                <?php endif; ?>
            </div>
            <div class="detail-body">
                <div class="tags">
                    <?php if ($hasPromo): ?><span class="tag">Promoção</span><?php endif; ?>
                    <?php if (!empty($product['category'])): ?><span class="tag"><?= e($product['category']) ?></span><?php endif; ?>
                    <?php if ((int)($product['in_stock'] ?? 1) === 1): ?><span class="tag">Disponível</span><?php endif; ?>
                </div>
                <h1><?= e($product['name']) ?></h1>
                <?php if (!empty($product['short_description'])): ?>
                    <p style="color:var(--fg-1);font-size:17px;margin:8px 0 0;"><?= e($product['short_description']) ?></p>
                <?php endif; ?>
                <div class="pricing">
                    <?php if ($hasPromo): ?>
                        <span class="price-old"><?= e(money($price)) ?></span>
                        <span class="price"><?= e(money($promo)) ?></span>
                    <?php elseif ($price !== null): ?>
                        <span class="price"><?= e(money($price)) ?></span>
                    <?php else: ?>
                        <span class="from">Consulte no WhatsApp</span>
                    <?php endif; ?>
                </div>
                <div class="ctas">
                    <a href="<?= e($waHref) ?>" class="btn btn--primary btn--lg" data-track="product_click" data-track-source="product_detail" data-track-ref-type="product" data-track-ref-id="<?= (int) $product['id'] ?>"><?= icon('phone', 18) ?> Consultar no WhatsApp</a>
                    <a href="/produtos" class="btn btn--ghost btn--lg">Voltar ao catálogo</a>
                </div>
                <?php if (!empty($product['description'])): ?>
                    <div class="description"><p><?= nl2br(e($product['description'])) ?></p></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($related)): ?>
<section class="section section--tight" style="background:var(--bg-1);border-top:1px solid var(--border);">
    <div class="container">
        <div class="section__head"><span class="eyebrow">Relacionados</span><h2 style="font-size:28px;">Outros produtos</h2></div>
        <div class="grid grid--products">
            <?php foreach ($related as $p): ?>
                <?= \App\Core\View::capture('partials/public/card-product', ['product' => $p]) ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
