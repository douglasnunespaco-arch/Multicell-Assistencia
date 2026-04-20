<?php /** @var array $promotions */ ?>
<section class="page-banner">
    <div class="container">
        <nav class="breadcrumb"><a href="/">Início</a> / <span>Promoções</span></nav>
        <span class="eyebrow">Promoções ativas</span>
        <h1>Condições especiais <em style="color:var(--brand);font-style:normal;">por tempo limitado</em>.</h1>
        <p>Aproveite enquanto estão valendo e garanta o desconto com a nossa equipe.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (!empty($promotions)): ?>
            <div class="grid grid--promotions">
                <?php foreach ($promotions as $promo): ?>
                    <?= \App\Core\View::capture('partials/public/card-promotion', ['promo' => $promo]) ?>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty"><h3>Sem promoções ativas no momento</h3><p>Fique de olho — novidades chegam toda semana.</p></div>
        <?php endif; ?>
    </div>
</section>
