<?php /** @var array $blocks; ?array $branch */ ?>
<section class="page-banner">
    <div class="container">
        <nav class="breadcrumb"><a href="/">Início</a> / <span>Sobre</span></nav>
        <span class="eyebrow">Nossa história</span>
        <h1>Assistência técnica séria, <em style="color:var(--brand);font-style:normal;">feita com orgulho</em>.</h1>
        <p>Unimos peças selecionadas, técnicos experientes e atendimento humano — sempre com orçamento claro.</p>
    </div>
</section>

<section class="section">
    <div class="container" style="display:grid;gap:48px;">
        <?php foreach (($blocks ?? []) as $b):
            $reverse = ($b['layout'] ?? '') === 'image-right';
        ?>
            <article class="detail-grid" data-reveal style="gap:40px;align-items:center;<?= $reverse ? '' : '' ?>">
                <div class="detail-media" style="<?= $reverse ? 'order:2;' : '' ?>">
                    <?php if (!empty($b['image_path'])): ?>
                        <img src="<?= e('/' . ltrim($b['image_path'], '/')) ?>" alt="<?= e($b['title']) ?>">
                    <?php else: ?>
                        <?= icon('sparkle', 64) ?>
                    <?php endif; ?>
                </div>
                <div class="detail-body">
                    <h2 style="font-size:clamp(24px,3vw,36px);margin-bottom:10px;"><?= e($b['title']) ?></h2>
                    <?php if (!empty($b['content'])): ?>
                        <div class="description"><p><?= nl2br(e($b['content'])) ?></p></div>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="final-cta">
    <div class="container">
        <h2 data-reveal>Venha conhecer a Multi Cell.</h2>
        <p data-reveal>Estamos prontos para cuidar do seu celular com técnica e atenção.</p>
        <div class="final-cta__ctas" data-reveal>
            <a href="<?= whatsapp_link('about_cta') ?>" class="btn btn--primary btn--lg" data-track="whatsapp_click" data-track-source="about_cta"><?= icon('phone', 18) ?> Chamar no WhatsApp</a>
            <a href="/contato" class="btn btn--ghost btn--lg">Ver localização</a>
        </div>
    </div>
</section>
