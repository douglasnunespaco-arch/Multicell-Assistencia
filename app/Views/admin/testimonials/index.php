<?php /** @var array $rows */ ?>
<div class="admin-card">
    <header class="admin-card__head">
        <h2>Depoimentos <small class="admin-card__hint">· <?= count($rows) ?> total</small></h2>
        <a class="admin-btn admin-btn--primary" href="/admin/testimonials/new">+ Novo depoimento</a>
    </header>
    <?php if (empty($rows)): ?>
        <div class="admin-empty"><p>Nenhum depoimento cadastrado</p></div>
    <?php else: ?>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>#</th><th>Autor</th><th>Nota</th><th>Origem</th><th>Conteúdo</th><th>Ordem</th><th>Status</th><th class="admin-col-actions">Ações</th></tr></thead>
            <tbody>
            <?php foreach ($rows as $r): ?>
                <tr>
                    <td>#<?= (int) $r['id'] ?></td>
                    <td><strong><?= e($r['author_name']) ?></strong></td>
                    <td><?= (int) $r['rating'] ?>★</td>
                    <td><small><?= e(strtoupper($r['source'] ?? '—')) ?></small></td>
                    <td><small style="opacity:.8"><?= e(mb_strimwidth($r['content'] ?? '', 0, 60, '…')) ?></small></td>
                    <td><?= (int) $r['sort_order'] ?></td>
                    <td><span class="admin-tag admin-tag--<?= $r['is_active'] ? 'novo' : 'cancelado' ?>"><?= $r['is_active'] ? 'Ativo' : 'Inativo' ?></span></td>
                    <td class="admin-col-actions">
                        <a class="admin-btn admin-btn--sm" href="/admin/testimonials/<?= (int) $r['id'] ?>/edit">Editar</a>
                        <form method="post" action="/admin/testimonials/<?= (int) $r['id'] ?>/delete" style="display:inline" onsubmit="return confirm('Remover este depoimento?');">
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
