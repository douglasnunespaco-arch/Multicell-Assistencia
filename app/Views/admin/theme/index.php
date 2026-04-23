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
            <div class="admin-form__field">
                <label for="brand_color">Cor primária (hex)</label>
                <input type="text" id="brand_color" name="brand_color" value="<?= e($v('brand_color', '#14F195')) ?>" placeholder="#14F195" pattern="^#[0-9a-fA-F]{6}$">
                <small>Ex.: <code>#14F195</code>. Aplicada como <code>--brand</code> no CSS.</small>
            </div>
            <div class="admin-form__field">
                <label for="brand_color_ink">Cor do texto sobre o brand</label>
                <input type="text" id="brand_color_ink" name="brand_color_ink" value="<?= e($v('brand_color_ink', '#0A0A0B')) ?>" placeholder="#0A0A0B" pattern="^#[0-9a-fA-F]{6}$">
                <small>Usado em botões primários (texto). Normalmente preto ou branco.</small>
            </div>
        </div>

        <?= F::select('default_theme', 'Tema padrão do site', $v('default_theme', 'dark'), [
            'dark'  => 'Dark (escuro)',
            'light' => 'Light (claro)',
        ]) ?>

        <?= F::text('display_font', 'Fonte display (informativo)', $v('display_font', 'Sora'), [
            'placeholder' => 'Sora · Manrope · Space Grotesk',
            'hint' => 'Indicativo. A troca real de fonte exige subir os arquivos .woff2 correspondentes em /assets/fonts/.'
        ]) ?>

        <div class="admin-form__actions">
            <button type="submit" class="admin-btn admin-btn--primary">Salvar tema</button>
        </div>
    </form>
</div>
