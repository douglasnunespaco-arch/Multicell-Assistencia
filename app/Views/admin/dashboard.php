<?php
/**
 * @var array $stats
 * @var array $recent
 */
?>
<div class="admin-grid-stats" data-testid="stats-grid">
    <a class="admin-stat admin-stat--link" href="/admin/leads?status=novo" data-testid="stat-leads-new">
        <span class="admin-stat__label">Reservas novas</span>
        <strong class="admin-stat__value"><?= (int) $stats['leads_new'] ?></strong>
        <small class="admin-stat__hint">aguardando atendimento</small>
    </a>
    <a class="admin-stat admin-stat--link" href="/admin/leads" data-testid="stat-leads-today">
        <span class="admin-stat__label">Reservas hoje</span>
        <strong class="admin-stat__value"><?= (int) $stats['leads_today'] ?></strong>
        <small class="admin-stat__hint">recebidas hoje</small>
    </a>
    <a class="admin-stat admin-stat--link" href="/admin/leads" data-testid="stat-leads-total">
        <span class="admin-stat__label">Reservas totais</span>
        <strong class="admin-stat__value"><?= (int) $stats['leads_total'] ?></strong>
        <small class="admin-stat__hint">desde o início</small>
    </a>
    <div class="admin-stat" data-testid="stat-leads-week">
        <span class="admin-stat__label">Reservas (7 dias)</span>
        <strong class="admin-stat__value"><?= (int) $stats['leads_week'] ?></strong>
        <small class="admin-stat__hint">últimos 7 dias</small>
    </div>
    <div class="admin-stat" data-testid="stat-pageviews-today">
        <span class="admin-stat__label">Visualizações hoje</span>
        <strong class="admin-stat__value"><?= (int) $stats['pageviews_today'] ?></strong>
        <small class="admin-stat__hint">page views</small>
    </div>
    <div class="admin-stat" data-testid="stat-wa-today">
        <span class="admin-stat__label">Cliques WhatsApp hoje</span>
        <strong class="admin-stat__value"><?= (int) $stats['wa_clicks_today'] ?></strong>
        <small class="admin-stat__hint">conversões diretas</small>
    </div>
</div>

<section class="admin-card" data-testid="recent-leads-card">
    <header class="admin-card__head">
        <h2>Últimas reservas</h2>
        <a class="admin-btn admin-btn--sm" href="/admin/leads" data-testid="recent-leads-see-all">Ver todas →</a>
    </header>

    <?php if (empty($recent)): ?>
        <div class="admin-empty" data-testid="recent-leads-empty">
            <?= icon('calendar', 28) ?>
            <p>Nenhuma reserva registrada ainda.</p>
            <small>Quando um cliente enviar o formulário em <code>/reservar</code>, aparecerá aqui.</small>
        </div>
    <?php else: ?>
        <div class="admin-table-wrap">
            <table class="admin-table" data-testid="recent-leads-table">
                <thead>
                    <tr>
                        <th>#</th><th>Cliente</th><th>Telefone</th><th>Tipo</th><th>Status</th><th>Quando</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($recent as $r): ?>
                    <tr>
                        <td><a href="/admin/leads/<?= (int) $r['id'] ?>">#<?= (int) $r['id'] ?></a></td>
                        <td><?= e($r['customer_name']) ?></td>
                        <td><?= e($r['phone']) ?></td>
                        <td><?= e($r['service_type'] ?? '—') ?></td>
                        <td><span class="admin-tag admin-tag--<?= e($r['status']) ?>"><?= e($r['status']) ?></span></td>
                        <td><small><?= e(date('d/m/Y H:i', strtotime($r['created_at']))) ?></small></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<section class="admin-card admin-card--info" data-testid="roadmap-card">
    <header class="admin-card__head">
        <h2>Próximos passos do painel</h2>
    </header>
    <ul class="admin-roadmap">
        <li><strong>Sub-rodada 3C · concluída</strong> — Gestão de Reservas disponível no menu <em>Reservas</em>: listar, filtrar por status, ver detalhe, atualizar status e abrir WhatsApp.</li>
        <li><strong>Sub-rodada 3B</strong> — CRUDs de conteúdo: Slides, Serviços, Produtos, Promoções, Configurações.</li>
        <li><strong>Fase 5</strong> — Analytics avançado: rankings, comparação por período, cliques por slide/produto.</li>
    </ul>
</section>
