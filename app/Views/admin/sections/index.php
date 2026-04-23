<?php
/** @var array $values · @var array $sections */
?>
<div class="admin-card">
    <header class="admin-card__head">
        <h2>Seções da home</h2>
        <small class="admin-card__hint">ligue/desligue blocos sem mexer no código</small>
    </header>
    <form method="post" action="/admin/sections/update" class="admin-form">
        <?= \App\Core\Csrf::field() ?>

        <p style="color: var(--fg-1); font-size: 13px; margin: 0 0 16px;">
            Marque quais seções devem aparecer na home. Desmarcadas são ocultadas na renderização pública.
            Útil para pausar temporariamente um bloco sem remover conteúdo.
        </p>

        <div style="display: grid; gap: 10px;">
            <?php foreach ($sections as $key => $label):
                $checked = (($values[$key] ?? '1') === '1'); ?>
                <label style="display: flex; align-items: center; gap: 12px; padding: 12px 14px; border: 1px solid var(--admin-border, #e5e7eb); border-radius: 10px; cursor: pointer;">
                    <input type="checkbox" name="<?= e($key) ?>" value="1" <?= $checked ? 'checked' : '' ?> data-testid="section-toggle-<?= e($key) ?>">
                    <div style="flex: 1;">
                        <strong style="font-size: 14px;"><?= e($label) ?></strong>
                        <small style="display: block; color: var(--fg-2); font-size: 11px; margin-top: 2px;">
                            chave: <code><?= e($key) ?></code>
                        </small>
                    </div>
                    <span class="admin-tag admin-tag--<?= $checked ? 'novo' : 'cancelado' ?>" style="font-size: 10px;">
                        <?= $checked ? 'Visível' : 'Oculto' ?>
                    </span>
                </label>
            <?php endforeach; ?>
        </div>

        <div class="admin-form__actions" style="margin-top: 20px;">
            <button type="submit" class="admin-btn admin-btn--primary">Salvar seções</button>
        </div>

        <p style="color: var(--fg-2); font-size: 11px; margin-top: 16px;">
            ⚠️ Para que o toggle tenha efeito real na home pública, cada seção precisa consumir a flag correspondente via
            <code>Setting::get('section_xxx_visible', '1') === '1'</code> antes de renderizar.
            Isso é aditivo e pode ser feito pontualmente sem quebrar o hero ou outras áreas.
        </p>
    </form>
</div>
