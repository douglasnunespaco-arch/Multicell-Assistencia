<?php
/** @var array $service; array $related */
$msg = 'Olá! Tenho interesse no serviço: ' . $service['name'] . '. Pode me passar mais informações?';
$waHref = '/go/whatsapp?src=service_detail&msg=' . urlencode($msg);
?>
<section class="page-banner">
    <div class="container">
        <nav class="breadcrumb">
            <a href="/">Início</a> /
            <a href="/assistencia-tecnica">Assistência</a> /
            <span><?= e($service['name']) ?></span>
        </nav>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="detail-grid">
            <div class="detail-media">
                <?php if (!empty($service['image_path'])): ?>
                    <img src="<?= e('/' . ltrim($service['image_path'], '/')) ?>" alt="<?= e($service['name']) ?>">
                <?php else: ?>
                    <?= service_icon($service['icon'] ?? null, 64) ?>
                <?php endif; ?>
            </div>
            <div class="detail-body">
                <div class="tags">
                    <span class="tag">Garantia 90 dias</span>
                    <span class="tag">Orçamento grátis</span>
                </div>
                <h1><?= e($service['name']) ?></h1>
                <?php if (!empty($service['short_description'])): ?>
                    <p class="lede" style="color:var(--fg-1);font-size:17px;margin:8px 0 0;"><?= e($service['short_description']) ?></p>
                <?php endif; ?>
                <?php if (!empty($service['price_from'])): ?>
                    <div class="pricing">
                        <span class="from">A partir de</span>
                        <span class="price"><?= e(money($service['price_from'])) ?></span>
                    </div>
                <?php endif; ?>
                <div class="ctas">
                    <a href="<?= e($waHref) ?>" class="btn btn--primary btn--lg" data-track="whatsapp_click" data-track-source="service_detail" data-track-ref-type="service" data-track-ref-id="<?= (int) $service['id'] ?>"><?= icon('phone', 18) ?> Consultar no WhatsApp</a>
                    <a href="/reservar" class="btn btn--ghost btn--lg" data-track="cta_click" data-track-source="service_detail_reserve">Reservar atendimento</a>
                </div>
                <?php if (!empty($service['description'])): ?>
                    <div class="description"><p><?= nl2br(e($service['description'])) ?></p></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($related)): ?>
<section class="section section--tight" style="background:var(--bg-1);border-top:1px solid var(--border);">
    <div class="container">
        <div class="section__head"><span class="eyebrow">Também pode interessar</span><h2 style="font-size:28px;">Outros serviços</h2></div>
        <div class="grid grid--services">
            <?php foreach ($related as $s): ?>
                <?= \App\Core\View::capture('partials/public/card-service', ['service' => $s]) ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
