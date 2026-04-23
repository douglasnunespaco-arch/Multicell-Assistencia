<?php /** @var array $rows */ ?>
<div class="admin-card">
    <header class="admin-card__head">
        <h2>Promoções <small class="admin-card__hint">· <?= count($rows) ?> total</small></h2>
        <a class="admin-btn admin-btn--primary" href="/admin/promotions/new">+ Nova promoção</a>
    </header>
    <?php if (empty($rows)): ?>
        <div class="admin-empty"><p>Nenhuma promoção cadastrada</p></div>
    <?php else: ?>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>#</th><th>Título</th><th>Preços</th><th>Termina em</th><th>Ordem</th><th>Status</th><th class="admin-col-actions">Ações</th></tr></thead>
            <tbody>
            <?php foreach ($rows as $r): ?>
                <tr>
                    <td>#<?= (int) $r['id'] ?></td>
                    <td><strong><?= e($r['title']) ?></strong></td>
                    <td><?php if ($r['new_price']): ?><strong>R$ <?= number_format((float) $r['new_price'], 2, ',', '.') ?></strong><?php endif; ?><?php if ($r['old_price']): ?> <small style="text-decoration:line-through">R$ <?= number_format((float) $r['old_price'], 2, ',', '.') ?></small><?php endif; ?></td>
                    <td><?= !empty($r['ends_at']) ? e(date('d/m/Y', strtotime($r['ends_at']))) : '—' ?></td>
                    <td><?= (int) $r['sort_order'] ?></td>
                    <td><span class="admin-tag admin-tag--<?= $r['is_active'] ? 'novo' : 'cancelado' ?>"><?= $r['is_active'] ? 'Ativa' : 'Inativa' ?></span></td>
                    <td class="admin-col-actions">
                        <a class="admin-btn admin-btn--sm" href="/admin/promotions/<?= (int) $r['id'] ?>/edit">Editar</a>
                        <form method="post" action="/admin/promotions/<?= (int) $r['id'] ?>/delete" style="display:inline" onsubmit="return confirm('Remover esta promoção?');">
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
