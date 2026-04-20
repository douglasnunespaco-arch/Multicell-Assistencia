<?php /** @var array $services */ ?>
<section class="page-banner">
    <div class="container">
        <nav class="breadcrumb"><a href="/">Início</a> / <span>Assistência Técnica</span></nav>
        <span class="eyebrow">Serviços técnicos</span>
        <h1>Reparos que devolvem seu celular <em style="color:var(--brand);font-style:normal;">ao ponto</em>.</h1>
        <p>Troca de tela, bateria, placa, software e acessórios — com garantia de 90 dias e orçamento grátis.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (!empty($services)): ?>
            <div class="grid grid--services">
                <?php foreach ($services as $service): ?>
                    <?= \App\Core\View::capture('partials/public/card-service', ['service' => $service]) ?>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty"><h3>Em breve</h3><p>Nossa lista completa de serviços aparecerá aqui.</p></div>
        <?php endif; ?>
    </div>
</section>

<section class="final-cta">
    <div class="container">
        <h2 data-reveal>Não encontrou o que precisa?</h2>
        <p data-reveal>Fale com nossa equipe — temos técnicos prontos para avaliar seu caso.</p>
        <div class="final-cta__ctas" data-reveal>
            <a href="<?= whatsapp_link('services_cta') ?>" class="btn btn--primary btn--lg" data-track="whatsapp_click" data-track-source="services_cta"><?= icon('phone', 18) ?> Chamar no WhatsApp</a>
            <a href="/reservar" class="btn btn--ghost btn--lg" data-track="cta_click" data-track-source="services_reserve">Reservar atendimento</a>
        </div>
    </div>
</section>
