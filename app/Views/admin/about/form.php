<?php
/** @var ?array $row */
use App\Core\FormField as F;
$isEdit = !empty($row);
$action = $isEdit ? '/admin/about/' . (int) $row['id'] . '/update' : '/admin/about/store';
$row = $row ?? ['title'=>'','content'=>'','image_path'=>null,'layout'=>'image-left','sort_order'=>0,'is_active'=>1];
?>
<div class="admin-card">
    <header class="admin-card__head">
        <h2><?= $isEdit ? 'Editar bloco #' . (int) $row['id'] : 'Novo bloco Sobre' ?></h2>
        <a class="admin-btn admin-btn--sm" href="/admin/about">← Voltar</a>
    </header>
    <form method="post" action="<?= e($action) ?>" enctype="multipart/form-data" class="admin-form">
        <?= \App\Core\Csrf::field() ?>
        <?= F::text('title', 'Título', $row['title'], ['required' => true, 'placeholder' => 'Ex.: Nossa missão']) ?>
        <?= F::textarea('content', 'Conteúdo', $row['content'] ?? '', ['rows' => 6, 'hint' => 'Texto institucional. HTML básico permitido.']) ?>
        <?= F::select('layout', 'Layout', $row['layout'] ?? 'image-left', [
            'image-left'  => 'Imagem à esquerda',
            'image-right' => 'Imagem à direita',
            'full'        => 'Apenas texto (full width)',
        ]) ?>
        <?php if (!empty($row['image_path'])): ?>
            <div class="admin-form__field">
                <label>Imagem atual</label>
                <div style="display:flex;align-items:center;gap:12px">
                    <img src="<?= e($row['image_path']) ?>" alt="" style="width:120px;height:80px;object-fit:cover;border-radius:6px">
                    <label style="font-size:13px"><input type="checkbox" name="image_remove" value="1"> Remover imagem</label>
                </div>
            </div>
        <?php endif; ?>
        <div class="admin-form__field">
            <label for="image">Substituir/Enviar imagem</label>
            <input type="file" id="image" name="image" accept="image/*">
            <small>Opcional. JPG/PNG/WebP até 5 MB.</small>
        </div>
        <div class="admin-form__row">
            <?= F::number('sort_order', 'Ordem', $row['sort_order']) ?>
            <?= F::checkbox('is_active', 'Ativo no site', $row['is_active']) ?>
        </div>
        <div class="admin-form__actions">
            <a class="admin-btn" href="/admin/about">Cancelar</a>
            <button type="submit" class="admin-btn admin-btn--primary">Salvar</button>
        </div>
    </form>
</div>
