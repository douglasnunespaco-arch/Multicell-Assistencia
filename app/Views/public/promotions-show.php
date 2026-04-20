<?php
/** @var array $promo; array $others */
$msg = 'Olá! Quero aproveitar a promoção: ' . $promo['title'];
$waHref = '/go/whatsapp?src=promo_detail&msg=' . urlencode($msg);
?>
<section class="page-banner">
    <div class="container">
        <nav class="breadcrumb">
            <a href="/">Início</a> / <a href="/promocoes">Promoções</a> / <span><?= e($promo['title']) ?></span>
        </nav>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="detail-grid">
            <div class="detail-media">
                <?php if (!empty($promo['image_path'])): ?>
                    <img src="<?= e('/' . ltrim($promo['image_path'], '/')) ?>" alt="<?= e($promo['title']) ?>">
                <?php else: ?>
                    <?= icon('tag', 64) ?>
                <?php endif; ?>
            </div>
            <div class="detail-body">
                <div class="tags">
                    <span class="tag">Promoção</span>
                    <?php if (!empty($promo['ends_at'])): ?>
                        <span class="tag">Até <?= e(date('d/m/Y', strtotime($promo['ends_at']))) ?></span>
                    <?php endif; ?>
                </div>
                <h1><?= e($promo['title']) ?></h1>
                <?php if (!empty($promo['new_price'])): ?>
                    <div class="pricing">
                        <?php if (!empty($promo['old_price'])): ?>
                            <span class="price-old"><?= e(money($promo['old_price'])) ?></span>
                        <?php endif; ?>
                        <span class="price"><?= e(money($promo['new_price'])) ?></span>
                    </div>
                <?php endif; ?>
                <div class="ctas">
                    <a href="<?= e($waHref) ?>" class="btn btn--primary btn--lg"
                       data-track="promotion_click" data-track-ref-type="promotion" data-track-ref-id="<?= (int) $promo['id'] ?>" data-track-source="promo_detail">
                        <?= icon('phone', 18) ?> <?= e($promo['cta_label'] ?: 'Aproveitar no WhatsApp') ?>
                    </a>
                    <a href="/promocoes" class="btn btn--ghost btn--lg">Ver outras promoções</a>
                </div>
                <?php if (!empty($promo['description'])): ?>
                    <div class="description"><p><?= nl2br(e($promo['description'])) ?></p></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($others)): ?>
<section class="section section--tight" style="background:var(--bg-1);border-top:1px solid var(--border);">
    <div class="container">
        <div class="section__head"><span class="eyebrow">Outras promoções</span><h2 style="font-size:28px;">Também vale a pena</h2></div>
        <div class="grid grid--promotions">
            <?php foreach ($others as $p): ?>
                <?= \App\Core\View::capture('partials/public/card-promotion', ['promo' => $p]) ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
