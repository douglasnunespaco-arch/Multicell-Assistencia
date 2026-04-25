<?php
/**
 * @var array $stats
 * @var array $recent
 * @var array $achievements
 * @var array $streak
 * @var array $monthly_lead
 */
?>
<?php if (!empty($streak) && (int)$streak['days'] >= 3): ?>
<aside class="streak-banner" data-testid="streak-banner" aria-label="Streak de metas">
    <span class="streak-banner__flame" aria-hidden="true"><?= icon('bolt', 18) ?></span>
    <div class="streak-banner__copy">
        <strong><?= (int) $streak['days'] ?> dias seguidos</strong> batendo a meta diária de <?= (int) $streak['goal'] ?> cliques.
    </div>
    <a class="streak-banner__cta" href="/admin/leads"><?= icon('arrow-right', 14) ?> ver leads</a>
</aside>
<?php endif; ?>
<?php if (!empty($achievements)): ?>
<section class="admin-trophies" data-testid="admin-trophies" aria-label="Conquistas recentes">
    <?php foreach ($achievements as $a):
        $delta = max(0, (int) $a['value'] - (int) $a['prev']);
    ?>
    <article class="trophy-card" data-testid="trophy-<?= e($a['key']) ?>">
        <div class="trophy-card__icon" aria-hidden="true"><?= icon('trophy-solid', 32) ?></div>
        <div class="trophy-card__body">
            <header class="trophy-card__head">
                <span class="trophy-card__eyebrow"><?= icon('trophy', 12) ?> <?= e(strtoupper($a['eyebrow'])) ?></span>
                <h3 class="trophy-card__title"><?= e($a['title']) ?></h3>
            </header>
            <p class="trophy-card__text">
                <?= e($a['body']) ?>
                <span class="trophy-card__stat">
                    <?= icon('bolt', 14) ?>
                    <strong><?= number_format((int) $a['value'], 0, ',', '.') ?> <?= e($a['unit']) ?></strong>
                    <?php if ((int) $a['prev'] > 0): ?>
                        <small>· antes: <?= number_format((int) $a['prev'], 0, ',', '.') ?></small>
                    <?php endif; ?>
                    <?php if ($delta > 0): ?>
                        <small class="trophy-card__delta">+<?= number_format($delta, 0, ',', '.') ?></small>
                    <?php endif; ?>
                </span>
            </p>
            <div class="trophy-card__actions">
                <a class="admin-btn admin-btn--primary admin-btn--sm" href="/admin/leads" data-testid="trophy-analytics">
                    <?= icon('trophy', 14) ?> Ver Analytics
                </a>
                <a class="admin-btn admin-btn--sm" href="/admin/leads?status=novo" data-testid="trophy-leads">
                    <?= icon('calendar', 14) ?> Orçamentos
                </a>
            </div>
        </div>
        <button type="button" class="trophy-card__close" aria-label="Minimizar conquista" data-testid="trophy-minimize" title="Minimizar">&times;</button>
    </article>
    <?php endforeach; ?>
</section>
<script>
(function () {
    document.querySelectorAll('.trophy-card__close').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var card = btn.closest('.trophy-card');
            if (!card) return;
            // Toggle minimize — NÃO persiste. Na próxima entrada no painel volta expandido.
            card.classList.toggle('is-minimized');
            btn.setAttribute('aria-label',
                card.classList.contains('is-minimized') ? 'Expandir conquista' : 'Minimizar conquista');
            btn.setAttribute('title',
                card.classList.contains('is-minimized') ? 'Expandir' : 'Minimizar');
        });
    });
})();
</script>
<?php endif; ?>

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
    <a class="admin-stat admin-stat--link" href="/admin/leads" data-testid="stat-leads-week">
        <span class="admin-stat__label">Reservas (7 dias)</span>
        <strong class="admin-stat__value"><?= (int) $stats['leads_week'] ?></strong>
        <small class="admin-stat__hint">últimos 7 dias</small>
    </a>
    <a class="admin-stat admin-stat--link" href="/admin/seo" data-testid="stat-pageviews-today">
        <span class="admin-stat__label">Visualizações hoje</span>
        <strong class="admin-stat__value"><?= (int) $stats['pageviews_today'] ?></strong>
        <small class="admin-stat__hint">page views</small>
    </a>
    <a class="admin-stat admin-stat--link" href="/admin/leads?status=novo" data-testid="stat-wa-today">
        <span class="admin-stat__label">Cliques WhatsApp hoje</span>
        <strong class="admin-stat__value"><?= (int) $stats['wa_clicks_today'] ?></strong>
        <small class="admin-stat__hint">conversões diretas</small>
    </a>
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

<?php
// ------- Bloco "Rankings e metas" · cliques por período -------
$periodKeys = ['day','week','month','year'];
$periodIcons = ['day'=>'bolt','week'=>'calendar','month'=>'award','year'=>'shield-check'];
?>
<section class="admin-card admin-rankings-card" data-testid="rankings-card">
    <header class="admin-card__head">
        <h2>Rankings e metas</h2>
        <small class="admin-card__hint">cliques reais por período · troféu acende quando a meta é batida</small>
    </header>

    <div class="rankings-grid" data-testid="rankings-grid">
        <?php foreach ($periodKeys as $pk):
            $r = $rankings[$pk] ?? ['label'=>ucfirst($pk),'total'=>0,'goal'=>0,'top'=>[]];
            $total = (int) $r['total'];
            $goal  = max(1, (int) $r['goal']);
            $pct   = min(100, (int) round(($total * 100) / $goal));
            $hit   = $total >= $goal;
            $super = $total >= ($goal * 2);
            $stateClass = $hit ? ($super ? ' is-super' : ' is-hit') : '';
        ?>
            <article class="period-card<?= $stateClass ?>"
                     data-period="<?= e($pk) ?>"
                     data-goal-hit="<?= $hit ? '1' : '0' ?>"
                     data-testid="period-<?= e($pk) ?>">
                <header class="period-card__head">
                    <span class="period-card__eyebrow"><?= icon($periodIcons[$pk] ?? 'bolt', 14) ?> <?= e($r['label']) ?></span>
                    <span class="trophy" aria-hidden="true" title="Meta <?= $hit ? 'batida' : 'em andamento' ?>">
                        <?= icon('award', 18) ?>
                    </span>
                </header>
                <div class="period-card__totals">
                    <strong class="period-card__value" data-testid="period-<?= e($pk) ?>-total"><?= number_format($total, 0, ',', '.') ?></strong>
                    <small class="period-card__goal">/ meta <?= number_format($goal, 0, ',', '.') ?></small>
                </div>
                <div class="period-card__progress" role="progressbar" aria-valuenow="<?= $pct ?>" aria-valuemin="0" aria-valuemax="100">
                    <span class="period-card__bar" style="width: <?= $pct ?>%"></span>
                </div>
                <p class="period-card__state">
                    <?php if ($super): ?>
                        <strong style="color:var(--brand);">Meta superada · dobro batido</strong>
                    <?php elseif ($hit): ?>
                        <strong style="color:var(--brand);">Meta batida · 🎉</strong>
                    <?php else: ?>
                        <span><?= $pct ?>% da meta · faltam <?= number_format(max(0, $goal - $total), 0, ',', '.') ?></span>
                    <?php endif; ?>
                </p>

                <?php if (empty($r['top'])): ?>
                    <div class="period-card__empty">sem cliques registrados ainda</div>
                <?php else: ?>
                    <ol class="period-card__ranking" data-testid="period-<?= e($pk) ?>-ranking">
                        <?php foreach ($r['top'] as $i => $row):
                            $label = (string) ($row['bucket'] ?? '—');
                            if ($label === '') $label = '—';
                            $count = (int) ($row['c'] ?? 0);
                        ?>
                            <li>
                                <span class="period-card__rank-n">#<?= $i + 1 ?></span>
                                <span class="period-card__rank-label" title="<?= e($label) ?>"><?= e($label) ?></span>
                                <span class="period-card__rank-count"><?= number_format($count, 0, ',', '.') ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
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
