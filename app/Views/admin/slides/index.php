<?php /** @var array $rows */ ?>
<div class="admin-card">
    <header class="admin-card__head">
        <h2>Slides <small class="admin-card__hint">· <?= count($rows) ?> total</small></h2>
        <div class="admin-card__head-actions">
            <a class="admin-btn admin-btn--primary" href="/admin/slides/new" data-testid="slides-new">+ Novo slide</a>
        </div>
    </header>
    <?php if (empty($rows)): ?>
        <div class="admin-empty"><p>Nenhum slide cadastrado</p><small>Clique em "Novo slide" para criar o primeiro.</small></div>
    <?php else: ?>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>#</th><th>Imagem</th><th>Título</th><th>Ordem</th><th>Status</th><th class="admin-col-actions">Ações</th></tr></thead>
            <tbody>
            <?php foreach ($rows as $r): ?>
                <tr>
                    <td>#<?= (int) $r['id'] ?></td>
                    <td><?php if (!empty($r['image_path'])): ?><img src="/<?= e($r['image_path']) ?>" alt="" class="admin-thumb"><?php else: ?><span class="admin-thumb admin-thumb--empty">—</span><?php endif; ?></td>
                    <td><?= e($r['title'] ?? '—') ?><?php if (empty($r['title'])): ?> <small>(só imagem)</small><?php endif; ?></td>
                    <td><?= (int) $r['sort_order'] ?></td>
                    <td><span class="admin-tag admin-tag--<?= $r['is_active'] ? 'novo' : 'cancelado' ?>"><?= $r['is_active'] ? 'Ativo' : 'Inativo' ?></span></td>
                    <td class="admin-col-actions">
                        <a class="admin-btn admin-btn--sm" href="/admin/slides/<?= (int) $r['id'] ?>/edit">Editar</a>
                        <form method="post" action="/admin/slides/<?= (int) $r['id'] ?>/delete" style="display:inline" onsubmit="return confirm('Remover este slide?');">
                            <?= \App\Core\Csrf::field() ?>
                            <button type="submit" class="admin-btn admin-btn--sm admin-btn--danger">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
