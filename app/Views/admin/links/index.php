<?php /** @var array $rows */ ?>
<div class="admin-card">
    <header class="admin-card__head">
        <h2>Links / Bio <small class="admin-card__hint">· <?= count($rows) ?> itens · página pública em <code>/links</code></small></h2>
        <a class="admin-btn admin-btn--primary" href="/admin/links/new">+ Novo item</a>
    </header>
    <?php if (empty($rows)): ?>
        <div class="admin-empty"><p>Nenhum item cadastrado</p><small>Gerencie os botões e banners da página <code>/links</code>.</small></div>
    <?php else: ?>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr>
                <th>#</th><th>Tipo</th><th>Título / Banner</th><th>URL</th>
                <th>Estilo</th><th>Altura</th><th>Ordem</th><th>Nova aba</th><th>Status</th>
                <th class="admin-col-actions">Ações</th>
            </tr></thead>
            <tbody>
            <?php foreach ($rows as $r): ?>
                <?php $type = $r['type'] ?? 'link'; $img = $r['image_path'] ?? null; ?>
                <tr>
                    <td>#<?= (int) $r['id'] ?></td>
                    <td><span class="admin-tag admin-tag--<?= $type === 'banner' ? 'em_atendimento' : 'novo' ?>"><?= $type === 'banner' ? 'Banner' : 'Botão' ?></span></td>
                    <td>
                        <?php if ($type === 'banner' && $img): ?>
                            <img src="/<?= e(ltrim((string) $img, '/')) ?>" alt="" style="height:34px;width:auto;border-radius:6px;vertical-align:middle;margin-right:8px">
                        <?php endif; ?>
                        <strong><?= e($r['title']) ?></strong>
                        <?php if (!empty($r['subtitle'])): ?><br><small style="color:var(--fg-2)"><?= e($r['subtitle']) ?></small><?php endif; ?>
                    </td>
                    <td><small><code style="word-break:break-all"><?= e(mb_strimwidth((string)$r['url'], 0, 50, '…')) ?></code></small></td>
                    <td><small><?= e($r['style'] ?? 'default') ?></small></td>
                    <td><?= (int) ($r['height_px'] ?? 0) > 0 ? (int) $r['height_px'] . 'px' : '<small style="color:var(--fg-2)">auto</small>' ?></td>
                    <td><?= (int) $r['sort_order'] ?></td>
                    <td><?= !empty($r['open_new_tab']) ? '↗' : '→' ?></td>
                    <td><span class="admin-tag admin-tag--<?= $r['is_active'] ? 'novo' : 'cancelado' ?>"><?= $r['is_active'] ? 'Ativo' : 'Inativo' ?></span></td>
                    <td class="admin-col-actions">
                        <a class="admin-btn admin-btn--sm" href="/admin/links/<?= (int) $r['id'] ?>/edit">Editar</a>
                        <form method="post" action="/admin/links/<?= (int) $r['id'] ?>/delete" style="display:inline" onsubmit="return confirm('Remover este item?');">
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
