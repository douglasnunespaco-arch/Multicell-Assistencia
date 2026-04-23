<?php /** @var array $rows */ ?>
<div class="admin-card">
    <header class="admin-card__head">
        <h2>Sobre / Institucional <small class="admin-card__hint">· <?= count($rows) ?> blocos</small></h2>
        <a class="admin-btn admin-btn--primary" href="/admin/about/new">+ Novo bloco</a>
    </header>
    <?php if (empty($rows)): ?>
        <div class="admin-empty"><p>Nenhum bloco cadastrado</p><small>Crie blocos institucionais (missão, valores, história, diferenciais) exibidos na página Sobre.</small></div>
    <?php else: ?>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>#</th><th>Título</th><th>Layout</th><th>Imagem</th><th>Ordem</th><th>Status</th><th class="admin-col-actions">Ações</th></tr></thead>
            <tbody>
            <?php foreach ($rows as $r): ?>
                <tr>
                    <td>#<?= (int) $r['id'] ?></td>
                    <td><strong><?= e($r['title']) ?></strong><?php if (!empty($r['content'])): ?><br><small><?= e(mb_strimwidth(strip_tags((string)$r['content']), 0, 90, '…')) ?></small><?php endif; ?></td>
                    <td><code><?= e($r['layout']) ?></code></td>
                    <td><?= !empty($r['image_path']) ? '<img src="' . e($r['image_path']) . '" alt="" style="width:48px;height:32px;object-fit:cover;border-radius:4px">' : '—' ?></td>
                    <td><?= (int) $r['sort_order'] ?></td>
                    <td><span class="admin-tag admin-tag--<?= $r['is_active'] ? 'novo' : 'cancelado' ?>"><?= $r['is_active'] ? 'Ativo' : 'Inativo' ?></span></td>
                    <td class="admin-col-actions">
                        <a class="admin-btn admin-btn--sm" href="/admin/about/<?= (int) $r['id'] ?>/edit">Editar</a>
                        <form method="post" action="/admin/about/<?= (int) $r['id'] ?>/delete" style="display:inline" onsubmit="return confirm('Remover este bloco?');">
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
