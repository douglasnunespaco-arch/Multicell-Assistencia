<?php /** @var array $products, $categories; ?string $current_cat */ ?>
<section class="page-banner">
    <div class="container">
        <nav class="breadcrumb"><a href="/">Início</a> / <span>Produtos</span></nav>
        <span class="eyebrow">Loja Multi Cell</span>
        <h1>Acessórios premium <em style="color:var(--brand);font-style:normal;">para o dia a dia</em>.</h1>
        <p>Capas, películas, carregadores e fones selecionados. Consulte preços e disponibilidade no WhatsApp.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (!empty($categories)): ?>
            <div class="chips">
                <a href="/produtos" class="chip <?= !$current_cat ? 'is-active' : '' ?>">Todos</a>
                <?php foreach ($categories as $cat): ?>
                    <a href="/produtos?categoria=<?= urlencode($cat) ?>" class="chip <?= $current_cat === $cat ? 'is-active' : '' ?>"><?= e($cat) ?></a>
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
            <div class="empty"><h3>Nada por aqui ainda</h3><p>Em breve novos produtos nesta categoria.</p></div>
        <?php endif; ?>
    </div>
</section>
