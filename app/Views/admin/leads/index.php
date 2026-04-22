<?php
/**
 * @var array $rows
 * @var int   $total
 * @var int   $page
 * @var int   $last_page
 * @var int   $per_page
 * @var ?string $status
 * @var array $statuses
 */
$statusLabels = [
    'novo'           => 'Novos',
    'em_atendimento' => 'Em atendimento',
    'concluido'      => 'Concluídos',
    'cancelado'      => 'Cancelados',
];
$statusLabelsSingular = [
    'novo'           => 'Novo',
    'em_atendimento' => 'Em atendimento',
    'concluido'      => 'Concluído',
    'cancelado'      => 'Cancelado',
];
$buildQs = function (array $overrides = []) use ($status, $page) {
    $qs = array_filter(array_merge(
        ['status' => $status, 'page' => $page],
        $overrides
    ), fn($v) => $v !== null && $v !== '');
    return $qs ? '?' . http_build_query($qs) : '';
};
?>
<div class="admin-card" data-testid="leads-header-card">
    <header class="admin-card__head">
        <h2>Reservas <small class="admin-card__hint">· <?= (int) $total ?> <?= $status ? 'com status "' . e($statusLabelsSingular[$status] ?? $status) . '"' : 'no total' ?></small></h2>
    </header>

    <div class="admin-filters" data-testid="leads-filters">
        <a class="admin-filter<?= $status === null ? ' is-active' : '' ?>"
           href="/admin/leads" data-testid="filter-status-all">Todas</a>
        <?php foreach ($statuses as $st): ?>
            <a class="admin-filter admin-filter--<?= e($st) ?><?= $status === $st ? ' is-active' : '' ?>"
               href="/admin/leads?status=<?= e($st) ?>"
               data-testid="filter-status-<?= e($st) ?>">
                <?= e($statusLabels[$st] ?? ucfirst($st)) ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<section class="admin-card" data-testid="leads-list-card">
    <?php if (empty($rows)): ?>
        <div class="admin-empty" data-testid="leads-empty">
            <?= icon('calendar', 28) ?>
            <p>Nenhuma reserva encontrada<?= $status ? ' com esse status' : '' ?>.</p>
            <small>Assim que novas reservas chegarem pelo formulário público, elas aparecerão aqui.</small>
        </div>
    <?php else: ?>
        <div class="admin-table-wrap">
            <table class="admin-table" data-testid="leads-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Telefone</th>
                        <th>Aparelho</th>
                        <th>Tipo</th>
                        <th>Dia desejado</th>
                        <th>Status</th>
                        <th>Criado em</th>
                        <th class="admin-col-actions">Ações</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($rows as $r):
                    $phoneDigits = preg_replace('/\D+/', '', (string) $r['phone']);
                    $waMsg = 'Olá ' . $r['customer_name'] . ', aqui é da Multi Cell Assistência Técnica.';
                    $waHref = 'https://wa.me/55' . $phoneDigits . '?text=' . rawurlencode($waMsg);
                    $device = trim(($r['device_brand'] ?? '') . ' ' . ($r['device_model'] ?? ''));
                    $preferredDate = !empty($r['preferred_date']) ? date('d/m/Y', strtotime($r['preferred_date'])) : '—';
                    $preferredDate .= !empty($r['preferred_period']) ? ' · ' . e($r['preferred_period']) : '';
                ?>
                    <tr data-testid="lead-row-<?= (int) $r['id'] ?>">
                        <td>#<?= (int) $r['id'] ?></td>
                        <td><strong><?= e($r['customer_name']) ?></strong></td>
                        <td><?= e($r['phone']) ?></td>
                        <td><?= e($device !== '' ? $device : '—') ?></td>
                        <td><?= e($r['service_type'] ?? '—') ?></td>
                        <td><small><?= $preferredDate ?></small></td>
                        <td><span class="admin-tag admin-tag--<?= e($r['status']) ?>" data-testid="lead-status-<?= (int) $r['id'] ?>"><?= e($statusLabelsSingular[$r['status']] ?? $r['status']) ?></span></td>
                        <td><small><?= e(date('d/m/Y H:i', strtotime($r['created_at']))) ?></small></td>
                        <td class="admin-col-actions">
                            <a class="admin-btn admin-btn--sm" href="/admin/leads/<?= (int) $r['id'] ?>"
                               data-testid="lead-view-<?= (int) $r['id'] ?>">Ver</a>
                            <?php if ($phoneDigits !== ''): ?>
                                <a class="admin-btn admin-btn--sm admin-btn--wa" href="<?= e($waHref) ?>"
                                   target="_blank" rel="noopener"
                                   data-testid="lead-wa-<?= (int) $r['id'] ?>">
                                    <?= icon('whatsapp', 14) ?> WhatsApp
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($last_page > 1): ?>
            <nav class="admin-pagination" data-testid="leads-pagination" aria-label="Paginação">
                <?php if ($page > 1): ?>
                    <a class="admin-btn admin-btn--sm" href="/admin/leads<?= $buildQs(['page' => $page - 1]) ?>" data-testid="page-prev">← Anterior</a>
                <?php else: ?>
                    <span class="admin-btn admin-btn--sm is-disabled">← Anterior</span>
                <?php endif; ?>
                <span class="admin-pagination__info">Página <?= (int) $page ?> de <?= (int) $last_page ?></span>
                <?php if ($page < $last_page): ?>
                    <a class="admin-btn admin-btn--sm" href="/admin/leads<?= $buildQs(['page' => $page + 1]) ?>" data-testid="page-next">Próxima →</a>
                <?php else: ?>
                    <span class="admin-btn admin-btn--sm is-disabled">Próxima →</span>
                <?php endif; ?>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</section>
