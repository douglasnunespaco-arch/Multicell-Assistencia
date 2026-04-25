<?php
/** @var array $values · @var array $keys */
use App\Core\FormField as F;
$v = fn(string $k, string $default = '') => (string) ($values[$k] ?? $default);
?>
<div class="admin-card">
    <header class="admin-card__head">
        <h2>Tema e Identidade</h2>
        <small class="admin-card__hint">cor primária · tema padrão · preferências visuais</small>
    </header>
    <form method="post" action="/admin/theme/update" class="admin-form">
        <?= \App\Core\Csrf::field() ?>

        <div class="admin-form__row">
            <div class="admin-field">
                <label for="brand_color">Cor primária (hex)</label>
                <div class="admin-color-field">
                    <input type="color" class="admin-color-swatch" id="brand_color_picker" value="<?= e($v('brand_color', '#14F195')) ?>" data-color-target="brand_color" aria-label="Selecionar cor primária">
                    <input type="text" class="admin-input" id="brand_color" name="brand_color" value="<?= e($v('brand_color', '#14F195')) ?>" placeholder="#14F195" pattern="^#[0-9a-fA-F]{6}$" data-color-input>
                </div>
                <small class="admin-field__hint">Ex.: <code>#14F195</code>. Aplicada como <code>--brand</code> no CSS.</small>
            </div>
            <div class="admin-field">
                <label for="brand_color_ink">Cor do texto sobre o brand</label>
                <div class="admin-color-field">
                    <input type="color" class="admin-color-swatch" id="brand_color_ink_picker" value="<?= e($v('brand_color_ink', '#0A0A0B')) ?>" data-color-target="brand_color_ink" aria-label="Selecionar cor de texto">
                    <input type="text" class="admin-input" id="brand_color_ink" name="brand_color_ink" value="<?= e($v('brand_color_ink', '#0A0A0B')) ?>" placeholder="#0A0A0B" pattern="^#[0-9a-fA-F]{6}$" data-color-input>
                </div>
                <small class="admin-field__hint">Usado em botões primários (texto). Normalmente preto ou branco.</small>
            </div>
        </div>

        <?= F::select('default_theme', 'Tema padrão do site', [
            'dark'  => 'Dark (escuro)',
            'light' => 'Light (claro)',
        ], $v('default_theme', 'dark')) ?>

        <?= F::text('display_font', 'Fonte display (informativo)', $v('display_font', 'Sora'), [
            'placeholder' => 'Sora · Manrope · Space Grotesk',
            'hint' => 'Indicativo. A troca real de fonte exige subir os arquivos .woff2 correspondentes em /assets/fonts/.'
        ]) ?>

        <div class="admin-form__actions">
            <button type="submit" class="admin-btn admin-btn--primary">Salvar tema</button>
        </div>
    </form>
</div>
