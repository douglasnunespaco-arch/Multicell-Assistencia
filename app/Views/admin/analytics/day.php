<?php
/** @var string $date · @var array $events · @var array $totals */
$labels = [
    'page_view'       => 'Visualização',
    'whatsapp_click'  => 'Clique WhatsApp',
    'cta_click'       => 'Clique CTA',
    'phone_click'     => 'Clique telefone',
    'map_click'       => 'Clique mapa',
    'product_click'   => 'Clique produto',
    'service_click'   => 'Clique serviço',
    'promotion_click' => 'Clique promoção',
];
?>
<div class="analytics">
    <header class="analytics__head">
        <div>
            <h2>Eventos do dia · <?= e(date('d/m/Y · D', strtotime($date))) ?></h2>
            <small><?= number_format((int)($totals['clicks']??0), 0, ',', '.') ?> cliques · <?= number_format((int)($totals['pageviews']??0), 0, ',', '.') ?> page views · <?= number_format((int)($totals['sessions']??0), 0, ',', '.') ?> sessões · até 500 eventos abaixo</small>
        </div>
        <a class="admin-btn admin-btn--sm" href="/admin/analytics" data-testid="day-back">← Voltar</a>
    </header>

    <section class="admin-card">
        <?php if (empty($events)): ?>
            <p class="admin-empty">Nenhum evento registrado nesse dia.</p>
        <?php else: ?>
            <table class="admin-table" data-testid="day-events-table">
                <thead>
                    <tr>
                        <th style="width:90px">Hora</th>
                        <th>Evento</th>
                        <th>Página / Item</th>
                        <th>Fonte</th>
                        <th>Sessão</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $ev):
                        $time = date('H:i:s', strtotime((string) $ev['created_at']));
                        $type = (string) $ev['event_type'];
                        $label = $labels[$type] ?? $type;
                        $where = !empty($ev['item_title']) ? (string) $ev['item_title'] : (string) ($ev['page_path'] ?? '');
                        if (!empty($ev['ref_type']) && !empty($ev['item_title'])) $where .= ' (' . (string) $ev['ref_type'] . ')';
                    ?>
                        <tr>
                            <td><code><?= e($time) ?></code></td>
                            <td><span class="admin-tag"><?= e($label) ?></span></td>
                            <td><?= e($where ?: '—') ?></td>
                            <td><?= e((string) ($ev['source'] ?? '') ?: '(direto)') ?></td>
                            <td><code style="font-size:11px;opacity:.7"><?= e(substr((string) ($ev['session_id'] ?? ''), 0, 10)) ?></code></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>
</div>
