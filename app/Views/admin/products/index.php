<?php /** @var array $rows */ ?>
<div class="admin-card">
    <header class="admin-card__head">
        <h2>Produtos <small class="admin-card__hint">· <?= count($rows) ?> total</small></h2>
        <a class="admin-btn admin-btn--primary" href="/admin/products/new">+ Novo produto</a>
    </header>
    <?php if (empty($rows)): ?>
        <div class="admin-empty"><p>Nenhum produto cadastrado</p></div>
    <?php else: ?>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>#</th><th>Imagem</th><th>Produto</th><th>Preço</th><th>Ordem</th><th>Destaque</th><th>Status</th><th class="admin-col-actions">Ações</th></tr></thead>
            <tbody>
            <?php foreach ($rows as $r): ?>
                <tr>
                    <td>#<?= (int) $r['id'] ?></td>
                    <td><?php if (!empty($r['image_path'])): ?><img src="/<?= e($r['image_path']) ?>" class="admin-thumb"><?php else: ?><span class="admin-thumb admin-thumb--empty">—</span><?php endif; ?></td>
                    <td><strong><?= e($r['title']) ?></strong><?php if (!empty($r['category'])): ?><br><small><?= e($r['category']) ?></small><?php endif; ?></td>
                    <td><?php if ($r['price']): ?><strong>R$ <?= number_format((float) $r['price'], 2, ',', '.') ?></strong><?php endif; ?></td>
                    <td><?= (int) $r['sort_order'] ?></td>
                    <td><?= !empty($r['is_featured']) ? '★' : '—' ?></td>
                    <td><span class="admin-tag admin-tag--<?= $r['is_active'] ? 'novo' : 'cancelado' ?>"><?= $r['is_active'] ? 'Ativo' : 'Inativo' ?></span></td>
                    <td class="admin-col-actions">
                        <a class="admin-btn admin-btn--sm" href="/admin/products/<?= (int) $r['id'] ?>/edit">Editar</a>
                        <form method="post" action="/admin/products/<?= (int) $r['id'] ?>/delete" style="display:inline" onsubmit="return confirm('Remover este produto?');">
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
