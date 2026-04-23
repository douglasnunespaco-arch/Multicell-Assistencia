<?php
/** @var ?array $row */
/** @var string[] $sources */
use App\Core\FormField as F;
$isEdit = !empty($row);
$action = $isEdit ? '/admin/testimonials/' . (int) $row['id'] . '/update' : '/admin/testimonials/store';
$row = $row ?? ['author_name'=>'','rating'=>5,'content'=>'','source'=>'google','sort_order'=>0,'is_active'=>1];
$sourceOpts = array_combine($sources, array_map(fn($s) => ucfirst($s), $sources));
?>
<div class="admin-card">
    <header class="admin-card__head">
        <h2><?= $isEdit ? 'Editar depoimento #' . (int) $row['id'] : 'Novo depoimento' ?></h2>
        <a class="admin-btn admin-btn--sm" href="/admin/testimonials">← Voltar</a>
    </header>
    <form method="post" action="<?= e($action) ?>" class="admin-form">
        <?= \App\Core\Csrf::field() ?>
        <?= F::text('author_name', 'Autor / cliente', $row['author_name'], ['required' => true]) ?>
        <div class="admin-form__row">
            <?= F::select('rating', 'Nota (estrelas)', [1=>'1 ★',2=>'2 ★★',3=>'3 ★★★',4=>'4 ★★★★',5=>'5 ★★★★★'], $row['rating']) ?>
            <?= F::select('source', 'Origem do depoimento', $sourceOpts, $row['source']) ?>
        </div>
        <?= F::textarea('content', 'Comentário do cliente', $row['content'], ['rows' => 4, 'required' => true]) ?>
        <div class="admin-form__row">
            <?= F::number('sort_order', 'Ordem', $row['sort_order']) ?>
            <?= F::checkbox('is_active', 'Ativo no site', $row['is_active']) ?>
        </div>
        <div class="admin-form__hint">
            <strong>Regra de exibição pública:</strong> o site mostra apenas depoimentos com <strong>nota 5★</strong> e origem social (<em>google · instagram · facebook · tiktok</em>).
        </div>
        <div class="admin-form__actions">
            <a class="admin-btn" href="/admin/testimonials">Cancelar</a>
            <button type="submit" class="admin-btn admin-btn--primary">Salvar</button>
        </div>
    </form>
</div>
