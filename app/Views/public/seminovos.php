<?php /** @var array $products */ ?>
<section class="page-banner">
    <div class="container">
        <nav class="breadcrumb"><a href="/">Início</a> / <span>Seminovos</span></nav>
        <span class="eyebrow">Aparelhos seminovos</span>
        <h1>Seminovos revisados <em style="color:var(--brand);font-style:normal;">com procedência</em>.</h1>
        <p>Cada aparelho passa por checagem técnica, limpeza e validação de IMEI. Consulte o estoque atualizado no WhatsApp.</p>
    </div>
</section>

<section class="section catalog-perks">
    <div class="container">
        <ul class="catalog-perks__grid" aria-label="Diferenciais dos seminovos">
            <li class="catalog-perks__item">
                <span class="catalog-perks__icon"><?= icon('shield', 22) ?></span>
                <div>
                    <strong>Garantia inclusa</strong>
                    <small>90 dias em todos os aparelhos seminovos.</small>
                </div>
            </li>
            <li class="catalog-perks__item">
                <span class="catalog-perks__icon"><?= icon('check', 22) ?></span>
                <div>
                    <strong>Revisão técnica</strong>
                    <small>Bateria, tela, câmeras e conectores testados.</small>
                </div>
            </li>
            <li class="catalog-perks__item">
                <span class="catalog-perks__icon"><?= icon('phone', 22) ?></span>
                <div>
                    <strong>IMEI verificado</strong>
                    <small>Procedência e bloqueio checados antes da venda.</small>
                </div>
            </li>
        </ul>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (!empty($products)): ?>
            <div class="grid grid--products">
                <?php foreach ($products as $product): ?>
                    <?= \App\Core\View::capture('partials/public/card-product', ['product' => $product]) ?>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty">
                <h3>Estoque de seminovos em atualização</h3>
                <p>Fale com a gente no WhatsApp — temos chegadas frequentes e aparelhos sob encomenda.</p>
                <p style="margin-top:18px;">
                    <a href="/go/whatsapp?src=seminovos_empty&amp;msg=<?= urlencode('Olá! Quero saber quais seminovos vocês têm disponíveis agora.') ?>"
                       class="btn btn--primary" data-track="whatsapp_click" data-track-source="seminovos_empty">
                        <?= icon('whatsapp', 16) ?> Consultar disponibilidade
                    </a>
                </p>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="section catalog-cta">
    <div class="container catalog-cta__box">
        <div>
            <h2>Não achou o modelo que queria?</h2>
            <p>A gente busca pra você. Chama no WhatsApp com o modelo, capacidade e cor desejada.</p>
        </div>
        <a href="/go/whatsapp?src=seminovos_cta&amp;msg=<?= urlencode('Olá! Estou procurando um seminovo específico. Podem me ajudar a encontrar?') ?>"
           class="btn btn--primary btn--lg" data-track="whatsapp_click" data-track-source="seminovos_cta">
            <?= icon('whatsapp', 18) ?> Encontrar meu seminovo
        </a>
    </div>
</section>
