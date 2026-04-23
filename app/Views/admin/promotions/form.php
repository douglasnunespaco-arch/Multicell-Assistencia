<?php
/** @var ?array $row */
use App\Core\FormField as F;
$isEdit = !empty($row);
$action = $isEdit ? '/admin/promotions/' . (int) $row['id'] . '/update' : '/admin/promotions/store';
$row = $row ?? ['title'=>'','slug'=>'','description'=>'','old_price'=>'','new_price'=>'','cta_label'=>'','cta_url'=>'','ends_at'=>'','sort_order'=>0,'is_active'=>1];
$ends = !empty($row['ends_at']) ? date('Y-m-d', strtotime($row['ends_at'])) : '';
?>
<div class="admin-card">
    <header class="admin-card__head">
        <h2><?= $isEdit ? 'Editar promoção #' . (int) $row['id'] : 'Nova promoção' ?></h2>
        <a class="admin-btn admin-btn--sm" href="/admin/promotions">← Voltar</a>
    </header>
    <form method="post" action="<?= e($action) ?>" enctype="multipart/form-data" class="admin-form">
        <?= \App\Core\Csrf::field() ?>
        <?= F::text('title', 'Título da promoção', $row['title'], ['required' => true]) ?>
        <?= F::text('slug', 'Slug (opcional)', $row['slug']) ?>
        <?= F::textarea('description', 'Descrição', $row['description'] ?? '', ['rows' => 3]) ?>
        <div class="admin-form__row">
            <?= F::number('old_price', 'Preço antigo (R$)', $row['old_price'] ?? '', ['min' => 0, 'step' => '0.01']) ?>
            <?= F::number('new_price', 'Preço promocional (R$)', $row['new_price'] ?? '', ['min' => 0, 'step' => '0.01']) ?>
            <?= F::date('ends_at', 'Termina em', $ends) ?>
        </div>
        <div class="admin-form__row">
            <?= F::text('cta_label', 'Texto do botão (opcional)', $row['cta_label'] ?? '') ?>
            <?= F::url('cta_url', 'URL do CTA (opcional)', $row['cta_url'] ?? '') ?>
        </div>
        <?= F::file('image', 'Imagem da promoção (opcional)', $row['image_path'] ?? null) ?>
        <div class="admin-form__row">
            <?= F::number('sort_order', 'Ordem', $row['sort_order']) ?>
            <?= F::checkbox('is_active', 'Ativa no site', $row['is_active']) ?>
        </div>
        <div class="admin-form__actions">
            <a class="admin-btn" href="/admin/promotions">Cancelar</a>
            <button type="submit" class="admin-btn admin-btn--primary">Salvar</button>
        </div>
    </form>
</div>
