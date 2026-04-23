<?php /** @var array $rows */ ?>
<div class="admin-card">
    <header class="admin-card__head">
        <h2>Links / Bio <small class="admin-card__hint">· <?= count($rows) ?> links · página pública em <code>/links</code></small></h2>
        <a class="admin-btn admin-btn--primary" href="/admin/links/new">+ Novo link</a>
    </header>
    <?php if (empty($rows)): ?>
        <div class="admin-empty"><p>Nenhum link cadastrado</p><small>Gerencie os botões da página /links (link-in-bio estilo Linktree).</small></div>
    <?php else: ?>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>#</th><th>Título</th><th>URL</th><th>Ícone</th><th>Ordem</th><th>Nova aba</th><th>Status</th><th class="admin-col-actions">Ações</th></tr></thead>
            <tbody>
            <?php foreach ($rows as $r): ?>
                <tr>
                    <td>#<?= (int) $r['id'] ?></td>
                    <td><strong><?= e($r['title']) ?></strong></td>
                    <td><small><code style="word-break:break-all"><?= e(mb_strimwidth((string)$r['url'], 0, 60, '…')) ?></code></small></td>
                    <td><code><?= e($r['icon'] ?? '—') ?></code></td>
                    <td><?= (int) $r['sort_order'] ?></td>
                    <td><?= !empty($r['open_new_tab']) ? '↗' : '→' ?></td>
                    <td><span class="admin-tag admin-tag--<?= $r['is_active'] ? 'novo' : 'cancelado' ?>"><?= $r['is_active'] ? 'Ativo' : 'Inativo' ?></span></td>
                    <td class="admin-col-actions">
                        <a class="admin-btn admin-btn--sm" href="/admin/links/<?= (int) $r['id'] ?>/edit">Editar</a>
                        <form method="post" action="/admin/links/<?= (int) $r['id'] ?>/delete" style="display:inline" onsubmit="return confirm('Remover este link?');">
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
