<?php
/**
 * @var array $lead
 * @var array $statuses
 */
$statusLabelsSingular = [
    'novo'           => 'Novo',
    'em_atendimento' => 'Em atendimento',
    'concluido'      => 'Concluído',
    'cancelado'      => 'Cancelado',
];
$phoneDigits = preg_replace('/\D+/', '', (string) $lead['phone']);
$device = trim(($lead['device_brand'] ?? '') . ' ' . ($lead['device_model'] ?? ''));
$waMsgParts = ['Olá ' . $lead['customer_name'] . ', aqui é da Multi Cell Assistência Técnica.'];
if ($device !== '') $waMsgParts[] = 'Sobre seu aparelho: ' . $device . '.';
if (!empty($lead['issue_description'])) $waMsgParts[] = 'Problema relatado: ' . $lead['issue_description'];
$waMsg = implode(' ', $waMsgParts);
$waHref = $phoneDigits !== '' ? 'https://wa.me/55' . $phoneDigits . '?text=' . rawurlencode($waMsg) : '';

$preferredDate = !empty($lead['preferred_date']) ? date('d/m/Y', strtotime($lead['preferred_date'])) : '—';
?>
<div class="admin-card" data-testid="lead-header-card">
    <header class="admin-card__head">
        <h2>
            Reserva #<?= (int) $lead['id'] ?>
            <small class="admin-card__hint">· criada em <?= e(date('d/m/Y H:i', strtotime($lead['created_at']))) ?></small>
        </h2>
        <div class="admin-card__head-actions">
            <a class="admin-btn admin-btn--sm" href="/admin/leads" data-testid="back-to-list">← Voltar</a>
            <?php if ($waHref): ?>
                <a class="admin-btn admin-btn--sm admin-btn--wa" href="<?= e($waHref) ?>"
                   target="_blank" rel="noopener" data-testid="lead-wa-btn">
                    <?= icon('whatsapp', 14) ?> Abrir WhatsApp
                </a>
            <?php endif; ?>
        </div>
    </header>

    <div class="admin-lead-grid">
        <div class="admin-lead-field"><span>Nome</span><strong data-testid="lead-name"><?= e($lead['customer_name']) ?></strong></div>
        <div class="admin-lead-field"><span>Telefone</span><strong data-testid="lead-phone"><?= e($lead['phone']) ?></strong></div>
        <div class="admin-lead-field"><span>Marca</span><strong><?= e($lead['device_brand'] ?? '—') ?></strong></div>
        <div class="admin-lead-field"><span>Modelo</span><strong><?= e($lead['device_model'] ?? '—') ?></strong></div>
        <div class="admin-lead-field"><span>Tipo de atendimento</span><strong><?= e($lead['service_type'] ?? '—') ?></strong></div>
        <div class="admin-lead-field"><span>Dia desejado</span><strong><?= e($preferredDate) ?></strong></div>
        <div class="admin-lead-field"><span>Período</span><strong><?= e($lead['preferred_period'] ?? '—') ?></strong></div>
        <div class="admin-lead-field"><span>Origem</span><strong><?= e($lead['source'] ?? '—') ?></strong></div>
        <div class="admin-lead-field">
            <span>Status atual</span>
            <strong><span class="admin-tag admin-tag--<?= e($lead['status']) ?>" data-testid="lead-current-status"><?= e($statusLabelsSingular[$lead['status']] ?? $lead['status']) ?></span></strong>
        </div>
        <div class="admin-lead-field admin-lead-field--full">
            <span>Defeito / descrição do problema</span>
            <strong data-testid="lead-issue"><?= e($lead['issue_description'] ?? '—') ?: '—' ?></strong>
        </div>
        <div class="admin-lead-field admin-lead-field--full">
            <span>Observação do cliente</span>
            <strong data-testid="lead-notes"><?= e($lead['notes'] ?? '—') ?: '—' ?></strong>
        </div>
    </div>
</div>

<section class="admin-card" data-testid="lead-status-card">
    <header class="admin-card__head">
        <h2>Atualizar status</h2>
        <small class="admin-card__hint">mudança é registrada no banco imediatamente</small>
    </header>

    <form method="post" action="/admin/leads/<?= (int) $lead['id'] ?>/status" class="admin-form-inline" data-testid="lead-status-form">
        <?= \App\Core\Csrf::field() ?>
        <label for="lead-status-select" class="sr-only">Novo status</label>
        <select id="lead-status-select" name="status" class="admin-input" data-testid="lead-status-select">
            <?php foreach ($statuses as $st): ?>
                <option value="<?= e($st) ?>" <?= $st === $lead['status'] ? 'selected' : '' ?>>
                    <?= e($statusLabelsSingular[$st] ?? $st) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="admin-btn admin-btn--primary" data-testid="lead-status-save">
            Salvar status
        </button>
    </form>
</section>
