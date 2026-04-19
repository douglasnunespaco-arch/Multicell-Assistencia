<?php
$slides = fetch_all_active('hero_slides');
$services = fetch_all_active('services');
$products = fetch_all_active('products');
$promotions = fetch_all_active('promotions');
$testimonials = fetch_all_active('testimonials');
$branches = fetch_all_active('branches');
?>
<section class="hero">
    <div class="container hero-grid">
        <div>
            <span class="eyebrow">Site premium + atendimento rápido</span>
            <h1><?= htmlspecialchars(setting('hero_title', 'Assistência técnica especializada, promoções e atendimento rápido.')) ?></h1>
            <p class="hero-copy"><?= htmlspecialchars(setting('hero_subtitle', 'Conserto, acessórios, aparelhos novos e seminovos com atendimento local em Várzea Grande.')) ?></p>
            <div class="cta-row">
                <a class="btn btn-primary trackable" href="<?= htmlspecialchars(setting('primary_cta_url', 'https://wa.me/5500000000000')) ?>" target="_blank" rel="noopener" data-event="whatsapp_click" data-cta="<?= htmlspecialchars(setting('primary_cta_label', 'Chamar no WhatsApp')) ?>">Chamar no WhatsApp</a>
                <a class="btn btn-secondary trackable" href="<?= base_url('/reservar') ?>" data-event="cta_click" data-cta="Reservar atendimento">Reservar atendimento</a>
            </div>
            <div class="trust-badges">
                <span>Assistência</span>
                <span>Promoções</span>
                <span>Produtos</span>
                <span>Várzea Grande</span>
            </div>
        </div>
        <div class="hero-panel">
            <?php foreach ($slides as $index => $slide): ?>
                <article class="hero-card <?= $index === 0 ? 'is-highlight' : '' ?>">
                    <div>
                        <h3><?= htmlspecialchars($slide['title'] ?? '') ?></h3>
                        <p><?= htmlspecialchars($slide['subtitle'] ?? '') ?></p>
                    </div>
                    <a class="text-link trackable" href="<?= htmlspecialchars($slide['cta_url'] ?? '#') ?>" <?= str_starts_with($slide['cta_url'] ?? '', 'http') ? 'target="_blank" rel="noopener"' : '' ?> data-event="slide_click" data-cta="<?= htmlspecialchars($slide['cta_label'] ?? 'CTA Slide') ?>" data-entity-type="slide" data-entity-label="<?= htmlspecialchars($slide['title'] ?? '') ?>">
                        <?= htmlspecialchars($slide['cta_label'] ?? 'Saiba mais') ?> →
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Serviços principais</span>
            <h2>Reparos e soluções que geram atendimento.</h2>
        </div>
        <div class="grid cards-4">
            <?php foreach ($services as $item): ?>
                <article class="card">
                    <div class="icon-chip"><?= htmlspecialchars($item['icon'] ?? '⚙️') ?></div>
                    <h3><?= htmlspecialchars($item['name'] ?? '') ?></h3>
                    <p><?= htmlspecialchars($item['summary'] ?? '') ?></p>
                    <a class="text-link trackable" href="<?= htmlspecialchars(setting('primary_cta_url', 'https://wa.me/5500000000000')) ?>" target="_blank" rel="noopener" data-event="service_click" data-cta="Solicitar serviço" data-entity-type="service" data-entity-label="<?= htmlspecialchars($item['name'] ?? '') ?>">Solicitar no WhatsApp →</a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section dark-surface">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Produtos e acessórios</span>
            <h2>Vitrine pensada para conversão rápida.</h2>
        </div>
        <div class="grid cards-3">
            <?php foreach ($products as $item): ?>
                <article class="card product-card">
                    <?php if (!empty($item['image_path'])): ?><img src="<?= asset_url($item['image_path']) ?>" alt="<?= htmlspecialchars($item['name']) ?>"><?php endif; ?>
                    <span class="pill"><?= htmlspecialchars($item['condition_label'] ?? $item['category'] ?? 'Produto') ?></span>
                    <h3><?= htmlspecialchars($item['name'] ?? '') ?></h3>
                    <p><?= htmlspecialchars($item['summary'] ?? '') ?></p>
                    <strong class="price-label"><?= htmlspecialchars($item['price_label'] ?? 'Consulte') ?></strong>
                    <a class="btn btn-secondary trackable" href="<?= htmlspecialchars($item['cta_url'] ?? setting('primary_cta_url', 'https://wa.me/5500000000000')) ?>" target="_blank" rel="noopener" data-event="product_click" data-cta="<?= htmlspecialchars($item['cta_label'] ?? 'Consultar produto') ?>" data-entity-type="product" data-entity-label="<?= htmlspecialchars($item['name'] ?? '') ?>">
                        <?= htmlspecialchars($item['cta_label'] ?? 'Consultar disponibilidade') ?>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Promoções</span>
            <h2>Ofertas atualizáveis pelo painel admin.</h2>
        </div>
        <div class="grid cards-3">
            <?php foreach ($promotions as $item): ?>
                <article class="card promo-card">
                    <div class="pill"><?= htmlspecialchars($item['badge'] ?? 'Oferta') ?></div>
                    <h3><?= htmlspecialchars($item['title'] ?? '') ?></h3>
                    <p><?= htmlspecialchars($item['summary'] ?? '') ?></p>
                    <div class="price-stack">
                        <?php if (!empty($item['old_price_label'])): ?><small><?= htmlspecialchars($item['old_price_label']) ?></small><?php endif; ?>
                        <strong><?= htmlspecialchars($item['price_label'] ?? 'Consulte') ?></strong>
                    </div>
                    <a class="btn btn-primary trackable" href="<?= htmlspecialchars($item['cta_url'] ?? setting('primary_cta_url', 'https://wa.me/5500000000000')) ?>" target="_blank" rel="noopener" data-event="promotion_click" data-cta="<?= htmlspecialchars($item['cta_label'] ?? 'Ver promoção') ?>" data-entity-type="promotion" data-entity-label="<?= htmlspecialchars($item['title'] ?? '') ?>">
                        <?= htmlspecialchars($item['cta_label'] ?? 'Ver promoção') ?>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="container split-panel">
        <div class="glass-panel">
            <span class="eyebrow">Por que a Multi Cell</span>
            <h2>Estrutura para vender, atender e registrar conversões.</h2>
            <ul class="feature-list">
                <li>Atendimento rápido com CTA direto para WhatsApp.</li>
                <li>Página de reserva com pré-atendimento.</li>
                <li>Produtos, promoções e serviços geridos pelo admin.</li>
                <li>Analytics interno para entender o que mais gera clique.</li>
            </ul>
        </div>
        <div class="glass-panel">
            <span class="eyebrow">Depoimentos</span>
            <h2>Prova social no contexto comercial.</h2>
            <?php foreach ($testimonials as $item): ?>
                <blockquote class="testimonial">
                    <p>“<?= htmlspecialchars($item['content'] ?? '') ?>”</p>
                    <footer><?= htmlspecialchars($item['author_name'] ?? 'Cliente') ?> • <?= str_repeat('★', (int) ($item['rating'] ?? 5)) ?></footer>
                </blockquote>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section dark-surface">
    <div class="container location-grid">
        <?php $branch = $branches[0] ?? null; ?>
        <div>
            <span class="eyebrow">Visite a loja</span>
            <h2>Localização e atendimento</h2>
            <p><?= htmlspecialchars($branch['address'] ?? setting('address', 'Endereço não configurado')) ?></p>
            <p><?= htmlspecialchars($branch['business_hours'] ?? setting('business_hours', 'Horário não configurado')) ?></p>
            <div class="cta-row">
                <a class="btn btn-primary trackable" href="<?= htmlspecialchars(setting('primary_cta_url', 'https://wa.me/5500000000000')) ?>" target="_blank" rel="noopener" data-event="whatsapp_click" data-cta="WhatsApp rodapé">Falar no WhatsApp</a>
                <a class="btn btn-secondary trackable" href="<?= base_url('/contato') ?>" data-event="cta_click" data-cta="Ver contato">Ver contato</a>
            </div>
        </div>
        <div class="map-shell">
            <?php if (!empty($branch['map_embed'])): ?>
                <?= $branch['map_embed'] ?>
            <?php else: ?>
                <div class="map-placeholder">Mapa / embed configurável no admin</div>
            <?php endif; ?>
        </div>
    </div>
</section>
