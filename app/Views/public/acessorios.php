<?php /** @var array $products, $categories; ?string $current_cat */ ?>
<section class="page-banner">
    <div class="container">
        <nav class="breadcrumb"><a href="/">Início</a> / <span>Acessórios</span></nav>
        <span class="eyebrow">Acessórios Multi Cell</span>
        <h1>Acessórios selecionados <em style="color:var(--brand);font-style:normal;">que valem o uso</em>.</h1>
        <p>Capas, películas, carregadores, cabos, fones e wearables — qualidade testada no balcão e na rua.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (!empty($categories)): ?>
            <div class="chips">
                <a href="/acessorios" class="chip <?= !$current_cat ? 'is-active' : '' ?>">Todos</a>
                <?php foreach ($categories as $cat): ?>
                    <a href="/acessorios?categoria=<?= urlencode($cat) ?>"
                       class="chip <?= $current_cat === $cat ? 'is-active' : '' ?>"><?= e($cat) ?></a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($products)): ?>
            <div class="grid grid--products">
                <?php foreach ($products as $product): ?>
                    <?= \App\Core\View::capture('partials/public/card-product', ['product' => $product]) ?>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty">
                <h3>Sem itens nesta categoria agora</h3>
                <p>Chama no WhatsApp — temos estoque rotativo e encomendas sob pedido.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="section catalog-cta">
    <div class="container catalog-cta__box">
        <div>
            <h2>Compre sem sair de casa</h2>
            <p>Entrega em Várzea Grande e Cuiabá. Combine tudo pelo WhatsApp e receba em casa.</p>
        </div>
        <a href="/delivery" class="btn btn--ghost btn--lg" data-testid="acessorios-delivery-link">
            Ver como funciona o delivery
        </a>
        <a href="/go/whatsapp?src=acessorios_cta&amp;msg=<?= urlencode('Olá! Quero fazer um pedido de acessórios com entrega.') ?>"
           class="btn btn--primary btn--lg" data-track="whatsapp_click" data-track-source="acessorios_cta">
            <?= icon('whatsapp', 18) ?> Pedir no WhatsApp
        </a>
    </div>
</section>
