<?php
/** @var array $slides, $services, $products, $promotions, $testimonials, ?array $branch */
$avgRating    = \App\Models\Setting::get('avg_rating', '4.9');
$totalReviews = \App\Models\Setting::get('total_reviews', '120');
?>

<!-- ================= HERO ================= -->
<section class="hero" aria-label="Apresentação">
    <div class="container">
        <div class="hero__slides" data-hero-slider data-autoplay="6500">
            <?php if (!empty($slides)): ?>
                <?php foreach ($slides as $i => $s): ?>
                    <div class="hero__slide <?= $i === 0 ? 'is-active' : '' ?>">
                        <div class="hero__grid">
                            <div class="hero__content">
                                <span class="eyebrow" data-reveal>Assistência técnica especializada</span>
                                <h1 data-reveal>
                                    <?= e($s['title']) ?>
                                    <?php if (!empty($s['subtitle'])): ?>
                                        <br><em style="font-size:.65em;font-weight:400;color:var(--fg-1);letter-spacing:-0.01em;"><?= e($s['subtitle']) ?></em>
                                    <?php endif; ?>
                                </h1>
                                <p class="lede" data-reveal>Troca de tela, bateria, placa e acessórios em Várzea Grande/MT. Orçamento grátis, peças selecionadas e garantia real.</p>
                                <div class="hero__ctas" data-reveal>
                                    <a href="<?= whatsapp_link('home_hero_slide_' . ($i + 1)) ?>" class="btn btn--primary btn--lg"
                                       data-track="whatsapp_click" data-track-source="home_hero_slide_<?= (int) ($i + 1) ?>"
                                       data-track-ref-type="slide" data-track-ref-id="<?= (int) $s['id'] ?>">
                                        <?= icon('phone', 18) ?> Chamar no WhatsApp
                                    </a>
                                    <a href="/reservar" class="btn btn--ghost btn--lg"
                                       data-track="cta_click" data-track-source="home_hero_reserve">
                                        Reservar atendimento
                                    </a>
                                </div>
                                <div class="hero__trust" data-reveal>
                                    <div class="hero__trust-item"><?= icon('shield', 18) ?> Garantia de 90 dias</div>
                                    <div class="hero__trust-item"><?= icon('check', 18) ?> Orçamento grátis</div>
                                    <div class="hero__trust-item"><?= icon('award', 18) ?> Técnicos certificados</div>
                                    <div class="hero__trust-item"><?= icon('bolt', 18) ?> Atendimento rápido</div>
                                </div>
                            </div>
                            <div class="hero__visual" data-reveal>
                                <?php if (!empty($s['image_path'])): ?>
                                    <img src="<?= e('/' . ltrim($s['image_path'], '/')) ?>" alt="<?= e($s['title']) ?>" class="hero__visual-img">
                                <?php endif; ?>
                                <div class="hero__visual-badge"><?= icon('bolt', 16) ?> Entrega em até 24h</div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (count($slides) > 1): ?>
                    <div class="hero__dots" role="tablist" aria-label="Slides">
                        <?php foreach ($slides as $i => $_): ?>
                            <button type="button" class="<?= $i === 0 ? 'is-active' : '' ?>" aria-label="Slide <?= $i + 1 ?>"></button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <!-- Fallback sem slides configurados -->
                <div class="hero__grid">
                    <div>
                        <span class="eyebrow">Assistência técnica especializada</span>
                        <h1>Seu celular em boas <em>mãos</em>.</h1>
                        <p class="lede">Reparo rápido, peças originais e garantia real. Atendimento em Várzea Grande/MT.</p>
                        <div class="hero__ctas">
                            <a href="<?= whatsapp_link('home_hero_fallback') ?>" class="btn btn--primary btn--lg" data-track="whatsapp_click" data-track-source="home_hero_fallback"><?= icon('phone', 18) ?> Chamar no WhatsApp</a>
                            <a href="/reservar" class="btn btn--ghost btn--lg">Reservar atendimento</a>
                        </div>
                    </div>
                    <div class="hero__visual"><div class="hero__visual-badge"><?= icon('bolt', 16) ?> Entrega em até 24h</div></div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ================= SERVIÇOS ================= -->
<section class="section">
    <div class="container">
        <div class="section__head" data-reveal>
            <span class="eyebrow">Serviços</span>
            <h2>Reparos que resolvem de verdade</h2>
            <p>Do diagnóstico à entrega: cuidamos do seu aparelho com técnica e transparência.</p>
        </div>
        <?php if (!empty($services)): ?>
            <div class="grid grid--services">
                <?php foreach ($services as $service): ?>
                    <?= \App\Core\View::capture('partials/public/card-service', ['service' => $service]) ?>
                <?php endforeach; ?>
            </div>
            <div style="margin-top:32px;">
                <a href="/assistencia-tecnica" class="btn btn--ghost" data-track="cta_click" data-track-source="home_services_more">
                    Ver todos os serviços <?= icon('arrow-right', 16) ?>
                </a>
            </div>
        <?php else: ?>
            <div class="empty"><h3>Serviços em atualização</h3><p>Em breve nossa vitrine completa.</p></div>
        <?php endif; ?>
    </div>
</section>

<!-- ================= PRODUTOS ================= -->
<section class="section section--tight" style="background:var(--bg-1);border-top:1px solid var(--border);border-bottom:1px solid var(--border);">
    <div class="container">
        <div class="section__head" data-reveal>
            <span class="eyebrow">Loja</span>
            <h2>Produtos selecionados</h2>
            <p>Acessórios premium com a curadoria da nossa equipe técnica.</p>
        </div>
        <?php if (!empty($products)): ?>
            <div class="grid grid--products">
                <?php foreach ($products as $product): ?>
                    <?= \App\Core\View::capture('partials/public/card-product', ['product' => $product]) ?>
                <?php endforeach; ?>
            </div>
            <div style="margin-top:32px;">
                <a href="/produtos" class="btn btn--ghost" data-track="cta_click" data-track-source="home_products_more">
                    Ver catálogo completo <?= icon('arrow-right', 16) ?>
                </a>
            </div>
        <?php else: ?>
            <div class="empty"><h3>Catálogo em atualização</h3><p>Novidades chegando em breve.</p></div>
        <?php endif; ?>
    </div>
</section>

<!-- ================= PROMOÇÕES ================= -->
<?php if (!empty($promotions)): ?>
<section class="section">
    <div class="container">
        <div class="section__head" data-reveal>
            <span class="eyebrow">Promoções ativas</span>
            <h2>Aproveite enquanto estão valendo</h2>
            <p>Condições especiais por tempo limitado em serviços e acessórios.</p>
        </div>
        <div class="grid grid--promotions">
            <?php foreach ($promotions as $promo): ?>
                <?= \App\Core\View::capture('partials/public/card-promotion', ['promo' => $promo]) ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ================= PROVA SOCIAL ================= -->
<?php if (!empty($testimonials)): ?>
<section class="section" style="background:var(--bg-1);border-top:1px solid var(--border);border-bottom:1px solid var(--border);">
    <div class="container">
        <div class="testimonials-head" data-reveal>
            <div>
                <span class="eyebrow">Clientes reais</span>
                <h2 style="font-size:clamp(26px,3.5vw,38px);margin:0;">O que dizem sobre a Multi Cell</h2>
            </div>
            <div class="rating-chip">
                <span class="rating-chip__stars">
                    <?= str_repeat(icon('star', 16), 5) ?>
                </span>
                <span class="rating-chip__text"><?= e($avgRating) ?>/5 <small>em <?= e($totalReviews) ?>+ avaliações</small></span>
            </div>
        </div>
        <div class="testimonials-grid">
            <?php foreach ($testimonials as $t): ?>
                <?= \App\Core\View::capture('partials/public/testimonial-item', ['t' => $t]) ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ================= DIFERENCIAIS ================= -->
<section class="section diffs">
    <div class="container">
        <div class="section__head" data-reveal>
            <span class="eyebrow">Por que a Multi Cell</span>
            <h2>Padrão técnico, atendimento humano</h2>
        </div>
        <div class="diffs-grid">
            <div class="diff" data-reveal>
                <div class="diff__icon"><?= icon('shield', 22) ?></div>
                <h3>Garantia real</h3>
                <p>Toda peça e serviço com garantia registrada.</p>
            </div>
            <div class="diff" data-reveal>
                <div class="diff__icon"><?= icon('award', 22) ?></div>
                <h3>Peças selecionadas</h3>
                <p>Originais ou OEM premium, testadas antes da entrega.</p>
            </div>
            <div class="diff" data-reveal>
                <div class="diff__icon"><?= icon('chip', 22) ?></div>
                <h3>Técnicos experientes</h3>
                <p>Equipe treinada em micro-solda e software.</p>
            </div>
            <div class="diff" data-reveal>
                <div class="diff__icon"><?= icon('heart', 22) ?></div>
                <h3>Atendimento humano</h3>
                <p>Orçamento claro e comunicação sem enrolação.</p>
            </div>
        </div>
    </div>
</section>

<!-- ================= LOCALIZAÇÃO ================= -->
<?php if (!empty($branch)): ?>
<section class="section">
    <div class="container">
        <div class="section__head" data-reveal>
            <span class="eyebrow">Onde estamos</span>
            <h2>Nossa loja em Várzea Grande/MT</h2>
        </div>
        <div class="locate-grid">
            <div class="locate-card" data-reveal>
                <div class="locate-card__row">
                    <?= icon('pin', 20) ?>
                    <div><strong>Endereço</strong><span><?= e($branch['address']) ?><br><?= e($branch['city']) ?>/<?= e($branch['state']) ?> <?= e($branch['zip_code'] ?? '') ?></span></div>
                </div>
                <div class="locate-card__row">
                    <?= icon('clock', 20) ?>
                    <div><strong>Horário</strong><span><?= e($branch['hours_text'] ?? \App\Models\Setting::get('hours_default')) ?></span></div>
                </div>
                <div class="locate-card__row">
                    <?= icon('phone', 20) ?>
                    <div><strong>Atendimento</strong><span><?= e(\App\Models\Setting::get('phone', 'Consulte no WhatsApp')) ?></span></div>
                </div>
                <div class="locate-card__ctas">
                    <a href="/go/map" class="btn btn--ghost btn--sm" data-track="map_click" data-track-source="home_location"><?= icon('pin', 16) ?> Ver no mapa</a>
                    <a href="<?= whatsapp_link('home_location') ?>" class="btn btn--primary btn--sm" data-track="whatsapp_click" data-track-source="home_location"><?= icon('phone', 16) ?> Falar no WhatsApp</a>
                </div>
            </div>
            <div class="map-wrap" data-reveal>
                <?php if (!empty($branch['map_embed_url'])): ?>
                    <iframe src="<?= e($branch['map_embed_url']) ?>" loading="lazy" title="Mapa da Multi Cell" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                <?php else: ?>
                    <div style="display:grid;place-items:center;height:100%;color:var(--fg-2);font-size:14px;">Mapa em breve</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ================= CTA FINAL ================= -->
<section class="final-cta">
    <div class="container">
        <span class="eyebrow" data-reveal>Atendimento rápido</span>
        <h2 data-reveal>Seu celular com problema? <em>A gente resolve hoje.</em></h2>
        <p data-reveal>Fale agora com nossa equipe e receba orçamento sem compromisso.</p>
        <div class="final-cta__ctas" data-reveal>
            <a href="<?= whatsapp_link('home_final_cta') ?>" class="btn btn--primary btn--lg"
               data-track="whatsapp_click" data-track-source="home_final_cta">
                <?= icon('phone', 18) ?> Falar no WhatsApp agora
            </a>
            <a href="/reservar" class="btn btn--ghost btn--lg"
               data-track="cta_click" data-track-source="home_final_reserve">
                Reservar horário
            </a>
        </div>
    </div>
</section>
