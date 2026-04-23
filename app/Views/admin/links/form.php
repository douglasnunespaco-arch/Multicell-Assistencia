<?php
/** @var ?array $row */
use App\Core\FormField as F;
$isEdit = !empty($row);
$action = $isEdit ? '/admin/links/' . (int) $row['id'] . '/update' : '/admin/links/store';
$row = $row ?? [
    'title' => '', 'subtitle' => '', 'type' => 'link', 'url' => '',
    'icon' => '', 'image_path' => null, 'style' => 'default',
    'height_px' => 0, 'sort_order' => 0, 'is_active' => 1, 'open_new_tab' => 1,
];
$currentType = $row['type'] ?? 'link';
?>
<div class="admin-card">
    <header class="admin-card__head">
        <h2><?= $isEdit ? 'Editar item #' . (int) $row['id'] : 'Novo item' ?></h2>
        <a class="admin-btn admin-btn--sm" href="/admin/links">← Voltar</a>
    </header>
    <form method="post" action="<?= e($action) ?>" enctype="multipart/form-data" class="admin-form" data-links-form>
        <?= \App\Core\Csrf::field() ?>

        <?= F::select('type', 'Tipo do item', [
            'link'   => 'Botão (ícone + título + subtítulo)',
            'banner' => 'Banner / imagem horizontal clicável',
        ], $currentType, ['hint' => 'Banner exibe a imagem ocupando toda a largura.']) ?>

        <?= F::text('title', 'Título', $row['title'], ['required' => true, 'placeholder' => 'Ex.: Falar no WhatsApp']) ?>
        <?= F::text('subtitle', 'Subtítulo (opcional)', $row['subtitle'] ?? '', ['placeholder' => 'Ex.: Fale agora com a loja']) ?>
        <?= F::text('url', 'URL / Destino', $row['url'], ['required' => true, 'placeholder' => 'https://... ou /rota-interna']) ?>

        <div class="admin-form__row" data-show-on-type="link">
            <?= F::text('icon', 'Ícone (slug Lucide)', $row['icon'] ?? '', ['placeholder' => 'whatsapp · instagram · map · package · tag · star', 'hint' => 'Usado apenas no tipo Botão.']) ?>
            <?= F::select('style', 'Estilo visual', [
                'default'   => 'Escuro translúcido',
                'highlight' => 'Destaque (fundo claro)',
            ], $row['style'] ?? 'default') ?>
        </div>

        <div data-show-on-type="banner">
            <?= F::file('image', 'Imagem horizontal (banner)', $row['image_path'] ?? null, ['accept' => 'image/*', 'hint' => 'Recomendado 1200×300px. A imagem inteira vira um link clicável.']) ?>
        </div>

        <div class="admin-form__row">
            <?= F::number('height_px', 'Altura (px)', (int) ($row['height_px'] ?? 0), ['min' => 0, 'max' => 400, 'hint' => '0 = altura padrão. Use valores entre 56 e 120 para botões, 100 a 260 para banners.']) ?>
            <?= F::number('sort_order', 'Ordem', $row['sort_order']) ?>
        </div>

        <div class="admin-form__row">
            <?= F::checkbox('open_new_tab', 'Abrir em nova aba', $row['open_new_tab']) ?>
            <?= F::checkbox('is_active', 'Ativo', $row['is_active']) ?>
        </div>

        <div class="admin-form__actions">
            <a class="admin-btn" href="/admin/links">Cancelar</a>
            <button type="submit" class="admin-btn admin-btn--primary">Salvar</button>
        </div>
    </form>
</div>

<script>
(function(){
    var form = document.querySelector('[data-links-form]');
    if (!form) return;
    var typeSel = form.querySelector('[name="type"]');
    function sync() {
        var val = typeSel.value;
        form.querySelectorAll('[data-show-on-type]').forEach(function(el){
            el.style.display = (el.getAttribute('data-show-on-type') === val) ? '' : 'none';
        });
    }
    typeSel.addEventListener('change', sync);
    sync();
})();
</script>
