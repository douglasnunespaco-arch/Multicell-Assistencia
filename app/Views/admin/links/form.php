<?php
/** @var ?array $row */
use App\Core\FormField as F;
$isEdit = !empty($row);
$action = $isEdit ? '/admin/links/' . (int) $row['id'] . '/update' : '/admin/links/store';
$row = $row ?? ['title'=>'','url'=>'','icon'=>'','sort_order'=>0,'is_active'=>1,'open_new_tab'=>1];
?>
<div class="admin-card">
    <header class="admin-card__head">
        <h2><?= $isEdit ? 'Editar link #' . (int) $row['id'] : 'Novo link' ?></h2>
        <a class="admin-btn admin-btn--sm" href="/admin/links">← Voltar</a>
    </header>
    <form method="post" action="<?= e($action) ?>" class="admin-form">
        <?= \App\Core\Csrf::field() ?>
        <?= F::text('title', 'Título do botão', $row['title'], ['required' => true, 'placeholder' => 'Ex.: Falar no WhatsApp']) ?>
        <?= F::text('url', 'URL / Destino', $row['url'], ['required' => true, 'placeholder' => 'https://... ou /rota-interna']) ?>
        <?= F::text('icon', 'Ícone (slug Lucide)', $row['icon'] ?? '', ['placeholder' => 'whatsapp · instagram · map · package · tag · star', 'hint' => 'Nome do ícone já disponível no sistema']) ?>
        <div class="admin-form__row">
            <?= F::number('sort_order', 'Ordem', $row['sort_order']) ?>
            <?= F::checkbox('open_new_tab', 'Abrir em nova aba', $row['open_new_tab']) ?>
            <?= F::checkbox('is_active', 'Ativo', $row['is_active']) ?>
        </div>
        <div class="admin-form__actions">
            <a class="admin-btn" href="/admin/links">Cancelar</a>
            <button type="submit" class="admin-btn admin-btn--primary">Salvar</button>
        </div>
    </form>
</div>
