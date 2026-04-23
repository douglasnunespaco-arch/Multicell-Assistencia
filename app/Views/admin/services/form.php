<?php
/** @var ?array $row */
use App\Core\FormField as F;
$isEdit = !empty($row);
$action = $isEdit ? '/admin/services/' . (int) $row['id'] . '/update' : '/admin/services/store';
$row = $row ?? ['title'=>'','slug'=>'','summary'=>'','description'=>'','icon'=>'','sort_order'=>0,'is_active'=>1,'is_featured'=>0];
?>
<div class="admin-card">
    <header class="admin-card__head">
        <h2><?= $isEdit ? 'Editar serviço #' . (int) $row['id'] : 'Novo serviço' ?></h2>
        <a class="admin-btn admin-btn--sm" href="/admin/services">← Voltar</a>
    </header>
    <form method="post" action="<?= e($action) ?>" enctype="multipart/form-data" class="admin-form">
        <?= \App\Core\Csrf::field() ?>
        <?= F::text('title', 'Título', $row['title'], ['required' => true, 'placeholder' => 'Ex.: Troca de Tela']) ?>
        <?= F::text('slug', 'Slug (opcional)', $row['slug'], ['hint' => 'deixe em branco para gerar a partir do título']) ?>
        <?= F::textarea('summary', 'Resumo curto', $row['summary'] ?? '', ['rows' => 2, 'hint' => 'aparece nos cards da home']) ?>
        <?= F::textarea('description', 'Descrição completa', $row['description'] ?? '', ['rows' => 4]) ?>
        <?= F::text('icon', 'Ícone (slug Lucide)', $row['icon'] ?? '', ['placeholder' => 'ex.: smartphone · battery · wrench', 'hint' => 'nome do ícone Lucide já disponível']) ?>
        <div class="admin-form__row">
            <?= F::number('sort_order', 'Ordem', $row['sort_order']) ?>
            <?= F::checkbox('is_featured', 'Destaque na home', $row['is_featured']) ?>
            <?= F::checkbox('is_active', 'Ativo no site', $row['is_active']) ?>
        </div>
        <div class="admin-form__actions">
            <a class="admin-btn" href="/admin/services">Cancelar</a>
            <button type="submit" class="admin-btn admin-btn--primary">Salvar</button>
        </div>
    </form>
</div>
