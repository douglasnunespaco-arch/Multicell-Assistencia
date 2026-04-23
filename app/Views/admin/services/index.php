<?php /** @var array $rows */ ?>
<div class="admin-card">
    <header class="admin-card__head">
        <h2>Serviços <small class="admin-card__hint">· <?= count($rows) ?> total</small></h2>
        <a class="admin-btn admin-btn--primary" href="/admin/services/new">+ Novo serviço</a>
    </header>
    <?php if (empty($rows)): ?>
        <div class="admin-empty"><p>Nenhum serviço cadastrado</p></div>
    <?php else: ?>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>#</th><th>Título</th><th>Ícone</th><th>Ordem</th><th>Destaque</th><th>Status</th><th class="admin-col-actions">Ações</th></tr></thead>
            <tbody>
            <?php foreach ($rows as $r): ?>
                <tr>
                    <td>#<?= (int) $r['id'] ?></td>
                    <td><strong><?= e($r['title']) ?></strong><?php if (!empty($r['summary'])): ?><br><small><?= e($r['summary']) ?></small><?php endif; ?></td>
                    <td><code><?= e($r['icon'] ?? '—') ?></code></td>
                    <td><?= (int) $r['sort_order'] ?></td>
                    <td><?= !empty($r['is_featured']) ? '★' : '—' ?></td>
                    <td><span class="admin-tag admin-tag--<?= $r['is_active'] ? 'novo' : 'cancelado' ?>"><?= $r['is_active'] ? 'Ativo' : 'Inativo' ?></span></td>
                    <td class="admin-col-actions">
                        <a class="admin-btn admin-btn--sm" href="/admin/services/<?= (int) $r['id'] ?>/edit">Editar</a>
                        <form method="post" action="/admin/services/<?= (int) $r['id'] ?>/delete" style="display:inline" onsubmit="return confirm('Remover este serviço?');">
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
