<?php
/** @var array $values · @var array $keys */
use App\Core\FormField as F;
$v = fn(string $k, string $default = '') => (string) ($values[$k] ?? $default);
?>
<div class="admin-card">
    <header class="admin-card__head">
        <h2>SEO e Social</h2>
        <small class="admin-card__hint">metatags públicas · Twitter/OG · tracking GA4 e Meta Pixel</small>
    </header>
    <form method="post" action="/admin/seo/update" class="admin-form">
        <?= \App\Core\Csrf::field() ?>

        <?= F::text('seo_title', 'Título SEO (<title>)', $v('seo_title'), [
            'placeholder' => 'Multi Cell Assistência Técnica • Várzea Grande/MT',
            'hint' => 'Aparece na aba do navegador e nos buscadores.'
        ]) ?>

        <?= F::textarea('seo_description', 'Descrição SEO (meta description)', $v('seo_description'), [
            'rows' => 3,
            'hint' => 'Ideal entre 140–160 caracteres.'
        ]) ?>

        <?= F::text('seo_canonical_url', 'URL canônica base', $v('seo_canonical_url'), [
            'placeholder' => 'https://multicell.com.br',
            'hint' => 'Sem barra final. Usado no <link rel="canonical"> das páginas.'
        ]) ?>

        <?= F::text('seo_keywords', 'Keywords (opcional)', $v('seo_keywords'), [
            'placeholder' => 'assistência celular, troca de tela, Várzea Grande',
            'hint' => 'Separadas por vírgula.'
        ]) ?>

        <div class="admin-form__row">
            <?= F::select('seo_twitter_card', 'Tipo de card Twitter/X', $v('seo_twitter_card', 'summary_large_image'), [
                'summary_large_image' => 'Imagem grande (recomendado)',
                'summary'             => 'Resumo simples',
            ]) ?>
            <?= F::text('seo_twitter_handle', 'Handle Twitter/X (@...)', $v('seo_twitter_handle'), [
                'placeholder' => '@multicell'
            ]) ?>
        </div>

        <div class="admin-form__row">
            <?= F::text('tracking_ga4_id', 'Google Analytics 4 ID', $v('tracking_ga4_id'), [
                'placeholder' => 'G-XXXXXXXXXX',
                'hint' => 'Formato G- seguido de 10 caracteres.'
            ]) ?>
            <?= F::text('tracking_meta_pixel_id', 'Meta Pixel ID (Facebook/Instagram Ads)', $v('tracking_meta_pixel_id'), [
                'placeholder' => '1234567890123456',
                'hint' => 'Sequência numérica de 15–16 dígitos.'
            ]) ?>
        </div>

        <div class="admin-form__actions">
            <button type="submit" class="admin-btn admin-btn--primary">Salvar SEO</button>
        </div>
    </form>
</div>
