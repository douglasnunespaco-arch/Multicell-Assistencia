<?php
/** @var ?array $row */
use App\Core\FormField as F;
$isEdit = !empty($row);
$action = $isEdit ? '/admin/slides/' . (int) $row['id'] . '/update' : '/admin/slides/store';
$row = $row ?? ['title'=>'','subtitle'=>'','cta_label'=>'','cta_url'=>'','image_path'=>null,'sort_order'=>0,'is_active'=>1];
?>
<div class="admin-card">
    <header class="admin-card__head">
        <h2><?= $isEdit ? 'Editar slide #' . (int) $row['id'] : 'Novo slide' ?></h2>
        <a class="admin-btn admin-btn--sm" href="/admin/slides">← Voltar</a>
    </header>
    <form method="post" action="<?= e($action) ?>" enctype="multipart/form-data" class="admin-form">
        <?= \App\Core\Csrf::field() ?>
        <div class="admin-form__hint">
            <strong>Texto é opcional.</strong> Se a imagem já contém todo o texto, deixe título, subtítulo e CTA em branco — o slide aparece apenas com a imagem.
        </div>
        <?= F::text('title', 'Título (opcional)', $row['title'], ['placeholder' => 'Ex.: Seu celular em boas mãos']) ?>
        <?= F::textarea('subtitle', 'Subtítulo (opcional)', $row['subtitle'], ['rows' => 2]) ?>
        <div class="admin-form__row">
            <?= F::text('cta_label', 'CTA — texto do botão (opcional)', $row['cta_label'], ['placeholder' => 'Ex.: Falar no WhatsApp']) ?>
            <?= F::url('cta_url', 'CTA — URL (opcional)', $row['cta_url'], ['placeholder' => 'https://wa.me/...']) ?>
        </div>
        <?= F::file('image', 'Imagem do slide', $row['image_path'], ['accept' => 'image/*']) ?>
        <div class="admin-form__row">
            <?= F::number('sort_order', 'Ordem', $row['sort_order'], ['min' => 0, 'hint' => 'menor aparece primeiro']) ?>
            <?= F::checkbox('is_active', 'Ativo no site', $row['is_active']) ?>
        </div>
        <div class="admin-form__actions">
            <a class="admin-btn" href="/admin/slides">Cancelar</a>
            <button type="submit" class="admin-btn admin-btn--primary">Salvar</button>
        </div>
    </form>
</div>
