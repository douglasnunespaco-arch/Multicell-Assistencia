<?php /** @var array $rows */ ?>
<div class="admin-card">
    <header class="admin-card__head">
        <h2>Unidades <small class="admin-card__hint">· <?= count($rows) ?> cadastradas</small></h2>
        <a class="admin-btn admin-btn--primary" href="/admin/units/new">+ Nova unidade</a>
    </header>
    <?php if (empty($rows)): ?>
        <div class="admin-empty"><p>Nenhuma unidade cadastrada</p><small>Cadastre lojas/filiais com endereço, WhatsApp e horário próprios.</small></div>
    <?php else: ?>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>#</th><th>Nome</th><th>Cidade/UF</th><th>WhatsApp</th><th>Ordem</th><th>Status</th><th class="admin-col-actions">Ações</th></tr></thead>
            <tbody>
            <?php foreach ($rows as $r): ?>
                <tr>
                    <td>#<?= (int) $r['id'] ?></td>
                    <td><strong><?= e($r['name']) ?></strong><br><small><?= e($r['address']) ?></small></td>
                    <td><?= e($r['city']) ?>/<?= e($r['state']) ?></td>
                    <td><?= e($r['whatsapp'] ?? '—') ?></td>
                    <td><?= (int) $r['sort_order'] ?></td>
                    <td><span class="admin-tag admin-tag--<?= $r['is_active'] ? 'novo' : 'cancelado' ?>"><?= $r['is_active'] ? 'Ativa' : 'Inativa' ?></span></td>
                    <td class="admin-col-actions">
                        <a class="admin-btn admin-btn--sm" href="/admin/units/<?= (int) $r['id'] ?>/edit">Editar</a>
                        <form method="post" action="/admin/units/<?= (int) $r['id'] ?>/delete" style="display:inline" onsubmit="return confirm('Remover esta unidade?');">
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
