<?php
/** @var ?array $row */
use App\Core\FormField as F;
$isEdit = !empty($row);
$action = $isEdit ? '/admin/products/' . (int) $row['id'] . '/update' : '/admin/products/store';
$row = $row ?? ['title'=>'','slug'=>'','category'=>'','summary'=>'','description'=>'','price'=>'','old_price'=>'','cta_label'=>'','cta_url'=>'','image_path'=>null,'sort_order'=>0,'is_active'=>1,'is_featured'=>0];
?>
<div class="admin-card">
    <header class="admin-card__head">
        <h2><?= $isEdit ? 'Editar produto #' . (int) $row['id'] : 'Novo produto' ?></h2>
        <a class="admin-btn admin-btn--sm" href="/admin/products">← Voltar</a>
    </header>
    <form method="post" action="<?= e($action) ?>" enctype="multipart/form-data" class="admin-form">
        <?= \App\Core\Csrf::field() ?>
        <?= F::text('title', 'Nome do produto', $row['title'], ['required' => true]) ?>
        <div class="admin-form__row">
            <?= F::text('slug', 'Slug (opcional)', $row['slug'], ['hint' => 'gera a partir do nome se vazio']) ?>
            <?= F::text('category', 'Categoria', $row['category'] ?? '') ?>
        </div>
        <?= F::textarea('summary', 'Resumo curto', $row['summary'] ?? '', ['rows' => 2]) ?>
        <?= F::textarea('description', 'Descrição completa', $row['description'] ?? '', ['rows' => 4]) ?>
        <div class="admin-form__row">
            <?= F::number('price', 'Preço atual (R$)', $row['price'] ?? '', ['min' => 0, 'step' => '0.01']) ?>
            <?= F::number('old_price', 'Preço antigo (R$, opcional)', $row['old_price'] ?? '', ['min' => 0, 'step' => '0.01']) ?>
        </div>
        <?= F::file('image', 'Imagem do produto', $row['image_path'] ?? null) ?>
        <div class="admin-form__row">
            <?= F::text('cta_label', 'Texto do botão CTA (opcional)', $row['cta_label'] ?? '') ?>
            <?= F::url('cta_url', 'URL do CTA (opcional)', $row['cta_url'] ?? '') ?>
        </div>
        <div class="admin-form__row">
            <?= F::number('sort_order', 'Ordem', $row['sort_order']) ?>
            <?= F::checkbox('is_featured', 'Destaque na home', $row['is_featured']) ?>
            <?= F::checkbox('is_active', 'Ativo no site', $row['is_active']) ?>
        </div>
        <div class="admin-form__actions">
            <a class="admin-btn" href="/admin/products">Cancelar</a>
            <button type="submit" class="admin-btn admin-btn--primary">Salvar</button>
        </div>
    </form>
</div>
