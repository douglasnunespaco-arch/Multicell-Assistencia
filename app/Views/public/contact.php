<?php /** @var ?array $branch */ ?>
<section class="page-banner">
    <div class="container">
        <nav class="breadcrumb"><a href="/">Início</a> / <span>Contato</span></nav>
        <span class="eyebrow">Contato e localização</span>
        <h1>Fale com a Multi Cell.</h1>
        <p>Estamos em Várzea Grande/MT, prontos para atender pessoalmente ou pelo WhatsApp.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="locate-grid">
            <div class="locate-card" data-reveal>
                <?php if ($branch): ?>
                    <div class="locate-card__row">
                        <?= icon('pin', 20) ?>
                        <div><strong>Endereço</strong><span><?= e($branch['address']) ?><br><?= e($branch['city']) ?>/<?= e($branch['state']) ?> <?= e($branch['zip_code'] ?? '') ?></span></div>
                    </div>
                    <div class="locate-card__row">
                        <?= icon('clock', 20) ?>
                        <div><strong>Horário</strong><span><?= e($branch['hours_text'] ?? \App\Models\Setting::get('hours_default')) ?></span></div>
                    </div>
                <?php endif; ?>
                <div class="locate-card__row">
                    <?= icon('phone', 20) ?>
                    <div><strong>Telefone</strong><span><?= e(\App\Models\Setting::get('phone', 'Consulte no WhatsApp')) ?></span></div>
                </div>
                <div class="locate-card__row">
                    <?= icon('mail', 20) ?>
                    <div><strong>E-mail</strong><span><?= e(\App\Models\Setting::get('email', 'contato@multicell.local')) ?></span></div>
                </div>
                <div class="locate-card__ctas">
                    <a href="<?= whatsapp_link('contact_cta') ?>" class="btn btn--primary" data-track="whatsapp_click" data-track-source="contact_cta"><?= icon('whatsapp', 16) ?> WhatsApp</a>
                    <a href="/go/map" class="btn btn--ghost" data-track="map_click" data-track-source="contact_map"><?= icon('map', 16) ?> Ver no mapa</a>
                    <a href="/reservar" class="btn btn--ghost"><?= icon('calendar', 16) ?> Reservar atendimento</a>
                </div>
            </div>
            <div class="map-wrap" data-reveal>
                <?php if (!empty($branch['map_embed_url'])): ?>
                    <iframe src="<?= e($branch['map_embed_url']) ?>" loading="lazy" title="Mapa Multi Cell" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                <?php else: ?>
                    <div style="display:grid;place-items:center;height:100%;color:var(--fg-2);">Mapa em breve</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
