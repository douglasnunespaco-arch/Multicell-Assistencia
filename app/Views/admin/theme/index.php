<?php
/** @var array $values · @var array $keys */
use App\Core\FormField as F;
$v = fn(string $k, string $default = '') => (string) ($values[$k] ?? $default);
$theme = $v('default_theme', 'dark');
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

        <div class="admin-field">
            <label>Tema padrão do site</label>
            <div class="theme-cards" role="radiogroup" aria-label="Tema padrão">
                <?php
                $opts = [
                    ['value' => 'dark',  'title' => 'Escuro',     'sub' => 'foco · contraste alto', 'class' => 'theme-card--dark'],
                    ['value' => 'light', 'title' => 'Claro',      'sub' => 'leveza · luz alta',     'class' => 'theme-card--light'],
                    ['value' => 'auto',  'title' => 'Automático', 'sub' => 'segue o sistema',       'class' => 'theme-card--auto'],
                ];
                foreach ($opts as $o):
                    $checked = ($theme === $o['value']) ? 'checked' : '';
                ?>
                <label class="theme-card <?= $o['class'] ?>" data-testid="theme-card-<?= e($o['value']) ?>">
                    <input type="radio" name="default_theme" value="<?= e($o['value']) ?>" <?= $checked ?>>
                    <input type="hidden" name="theme_preference_sync" value="0">
                    <div class="theme-card__preview" aria-hidden="true">
                        <div class="theme-card__sidebar">
                            <span class="theme-card__bar"></span>
                            <span class="theme-card__bar"></span>
                            <span class="theme-card__bar"></span>
                            <span class="theme-card__bar"></span>
                        </div>
                        <div class="theme-card__main">
                            <div class="theme-card__hero"></div>
                            <div class="theme-card__row">
                                <span class="theme-card__chip"></span>
                                <span class="theme-card__chip"></span>
                            </div>
                            <div class="theme-card__row">
                                <span class="theme-card__chip"></span>
                                <span class="theme-card__chip"></span>
                                <span class="theme-card__chip"></span>
                            </div>
                        </div>
                    </div>
                    <div class="theme-card__caption">
                        <strong>
                            <span class="theme-card__check"><?= icon('check', 12) ?></span>
                            <?= e($o['title']) ?>
                        </strong>
                        <small><?= e($o['sub']) ?></small>
                    </div>
                </label>
                <?php endforeach; ?>
            </div>
            <small class="admin-field__hint">Define o tema padrão para visitantes anônimos. O admin pode alternar a qualquer hora pelo botão no topo.</small>
        </div>

        <?= F::text('display_font', 'Fonte display (informativo)', $v('display_font', 'Sora'), [
            'placeholder' => 'Sora · Manrope · Space Grotesk',
            'hint' => 'Indicativo. A troca real de fonte exige subir os arquivos .woff2 correspondentes em /assets/fonts/.'
        ]) ?>

        <div class="admin-form__actions">
            <button type="submit" class="admin-btn admin-btn--primary">Salvar tema</button>
        </div>
    </form>
</div>

<script>
// Aplicar preview ao trocar de card (sem precisar salvar)
document.querySelectorAll('input[name="default_theme"]').forEach(function (r) {
    r.addEventListener('change', function () {
        var v = r.value;
        if (v === 'auto') {
            var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.setAttribute('data-theme', prefersDark ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme-pref', 'auto');
        } else {
            document.documentElement.setAttribute('data-theme', v);
            document.documentElement.setAttribute('data-theme-pref', v);
        }
    });
});
</script>
