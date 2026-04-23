<?php
/** @var string $hero_title, $hero_subtitle, $delivery_area, $delivery_hours, $wa_message */
$waHref = '/go/whatsapp?src=delivery_page&msg=' . urlencode($wa_message);
?>
<section class="page-banner">
    <div class="container">
        <nav class="breadcrumb"><a href="/">Início</a> / <span>Delivery</span></nav>
        <span class="eyebrow">Compre pelo WhatsApp</span>
        <h1><?= e($hero_title) ?></h1>
        <p><?= e($hero_subtitle) ?></p>
        <div class="page-banner__cta">
            <a href="<?= e($waHref) ?>" class="btn btn--primary btn--lg"
               data-track="whatsapp_click" data-track-source="delivery_hero" data-testid="delivery-hero-cta">
                <?= icon('whatsapp', 18) ?> Fazer pedido agora
            </a>
        </div>
    </div>
</section>

<section class="section delivery-info">
    <div class="container delivery-info__grid">
        <div class="delivery-info__card">
            <span class="delivery-info__icon"><?= icon('pin', 20) ?></span>
            <div>
                <strong>Área atendida</strong>
                <p><?= e($delivery_area) ?></p>
            </div>
        </div>
        <div class="delivery-info__card">
            <span class="delivery-info__icon"><?= icon('clock', 20) ?></span>
            <div>
                <strong>Horário</strong>
                <p><?= e($delivery_hours) ?></p>
            </div>
        </div>
        <div class="delivery-info__card">
            <span class="delivery-info__icon"><?= icon('whatsapp', 20) ?></span>
            <div>
                <strong>Forma de pedido</strong>
                <p>Totalmente pelo WhatsApp, com atendente humano.</p>
            </div>
        </div>
    </div>
</section>

<section class="section delivery-steps">
    <div class="container">
        <header class="section-head">
            <span class="eyebrow">Como funciona</span>
            <h2>Quatro passos simples <em style="color:var(--brand);font-style:normal;">até receber em casa</em>.</h2>
        </header>
        <ol class="delivery-steps__list">
            <li class="delivery-steps__item">
                <span class="delivery-steps__num">1</span>
                <div>
                    <strong>Escolha o que quer</strong>
                    <p>Navegue em <a href="/acessorios">Acessórios</a> ou <a href="/seminovos">Seminovos</a> e separe os itens.</p>
                </div>
            </li>
            <li class="delivery-steps__item">
                <span class="delivery-steps__num">2</span>
                <div>
                    <strong>Confirme pelo WhatsApp</strong>
                    <p>Fale com nosso atendente — ajustamos detalhes, confirmamos estoque e fechamos valor.</p>
                </div>
            </li>
            <li class="delivery-steps__item">
                <span class="delivery-steps__num">3</span>
                <div>
                    <strong>Combine pagamento e entrega</strong>
                    <p>Pix, cartão ou dinheiro. Entrega no endereço combinado dentro da área atendida.</p>
                </div>
            </li>
            <li class="delivery-steps__item">
                <span class="delivery-steps__num">4</span>
                <div>
                    <strong>Receba em casa</strong>
                    <p>Embalagem cuidada, conferência no ato e suporte pós-entrega pelo mesmo WhatsApp.</p>
                </div>
            </li>
        </ol>
    </div>
</section>

<section class="section catalog-cta">
    <div class="container catalog-cta__box">
        <div>
            <h2>Pronto pra fazer seu pedido?</h2>
            <p>Chama no WhatsApp com o item desejado e o endereço — a gente cuida do resto.</p>
        </div>
        <a href="<?= e($waHref) ?>" class="btn btn--primary btn--lg"
           data-track="whatsapp_click" data-track-source="delivery_bottom" data-testid="delivery-bottom-cta">
            <?= icon('whatsapp', 18) ?> Pedir pelo WhatsApp
        </a>
    </div>
</section>
