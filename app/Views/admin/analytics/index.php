<?php
/**
 * @var int   $range
 * @var array $kpis  · clicks, pageviews, sessions, leads, conv_rate
 * @var array $timeline · [{d, c}]
 * @var array $by_type · [{event_type, c}]
 * @var array $sources · [{source, c}]
 * @var array $pages   · [{page_path, c}]
 * @var array $items   · [{type, id, clicks, title}]
 */
$total = max(1, array_sum(array_map(fn($r) => (int)$r['c'], $by_type)));
// helper donut · 1 anel por tipo
$donutColors = ['#14F195','#0FD585','#FFD24A','#FFB547','#7fecc0','#2D6CD1','#A77BFF','#FF6B85','#FF9E66','#9aa0a6'];
?>
<div class="analytics">
    <header class="analytics__head">
        <div>
            <h2>Analytics</h2>
            <small>Tudo que rola no seu site · sem cookies de terceiros</small>
        </div>
        <form method="get" class="analytics__range">
            <label for="range">Período</label>
            <select id="range" name="range" onchange="this.form.submit()">
                <option value="7"  <?= $range === 7  ? 'selected' : '' ?>>Últimos 7 dias</option>
                <option value="30" <?= $range === 30 ? 'selected' : '' ?>>Últimos 30 dias</option>
                <option value="90" <?= $range === 90 ? 'selected' : '' ?>>Últimos 90 dias</option>
            </select>
        </form>
    </header>

    <!-- KPI cards -->
    <div class="analytics__kpis">
        <article class="analytics-kpi">
            <span class="analytics-kpi__label">Cliques</span>
            <strong class="analytics-kpi__value"><?= number_format($kpis['clicks'], 0, ',', '.') ?></strong>
            <span class="analytics-kpi__hint">eventos de interação</span>
        </article>
        <article class="analytics-kpi">
            <span class="analytics-kpi__label">Visualizações</span>
            <strong class="analytics-kpi__value"><?= number_format($kpis['pageviews'], 0, ',', '.') ?></strong>
            <span class="analytics-kpi__hint">page views</span>
        </article>
        <article class="analytics-kpi">
            <span class="analytics-kpi__label">Sessões</span>
            <strong class="analytics-kpi__value"><?= number_format($kpis['sessions'], 0, ',', '.') ?></strong>
            <span class="analytics-kpi__hint">visitantes únicos</span>
        </article>
        <article class="analytics-kpi">
            <span class="analytics-kpi__label">Leads</span>
            <strong class="analytics-kpi__value"><?= number_format($kpis['leads'], 0, ',', '.') ?></strong>
            <span class="analytics-kpi__hint">reservas recebidas</span>
        </article>
        <article class="analytics-kpi analytics-kpi--accent">
            <span class="analytics-kpi__label">Taxa de conversão</span>
            <strong class="analytics-kpi__value"><?= number_format($kpis['conv_rate'], 1, ',', '.') ?>%</strong>
            <span class="analytics-kpi__hint">leads / cliques</span>
        </article>
    </div>

    <!-- Bar chart timeline -->
    <section class="admin-card analytics-timeline">
        <header class="admin-card__head">
            <h2>Cliques por dia</h2>
            <small class="admin-card__hint"><?= count($timeline) ?> dias · passe o mouse pra ver detalhe</small>
        </header>
        <?php
        $vals = array_map(fn($r) => (int) $r['c'], $timeline);
        $max  = max($vals); $max = $max < 1 ? 1 : $max;
        $W = 1100; $H = 200; $padL = 36; $padB = 28; $padT = 12;
        $innerW = $W - $padL - 8;
        $innerH = $H - $padT - $padB;
        $n = count($timeline);
        $bw = $n > 0 ? ($innerW / $n) - 4 : 0;
        $gridLines = [0.25, 0.5, 0.75, 1.0];
        ?>
        <div class="analytics-chart">
            <svg viewBox="0 0 <?= $W ?> <?= $H ?>" preserveAspectRatio="none" width="100%" height="<?= $H ?>" role="img" aria-label="Cliques por dia">
                <defs><linearGradient id="barFill" x1="0" x2="0" y1="0" y2="1">
                    <stop offset="0%"   stop-color="#14F195" stop-opacity=".95"/>
                    <stop offset="100%" stop-color="#14F195" stop-opacity=".35"/>
                </linearGradient></defs>
                <!-- grid -->
                <?php foreach ($gridLines as $g): $y = round($padT + $innerH * (1 - $g), 1); ?>
                    <line x1="<?= $padL ?>" y1="<?= $y ?>" x2="<?= $W - 4 ?>" y2="<?= $y ?>" stroke="currentColor" stroke-opacity=".08" stroke-dasharray="3 4"/>
                    <text x="<?= $padL - 6 ?>" y="<?= $y + 3 ?>" text-anchor="end" font-size="10" fill="currentColor" opacity=".55"><?= (int) round($max * $g) ?></text>
                <?php endforeach; ?>
                <!-- bars -->
                <?php foreach ($timeline as $i => $r):
                    $h = round(($r['c'] / $max) * $innerH, 1);
                    $x = round($padL + $i * (($innerW) / $n) + 2, 1);
                    $y = round($padT + $innerH - $h, 1);
                    $label = date('d/m', strtotime($r['d']));
                ?>
                    <g class="analytics-chart__bar">
                        <rect x="<?= $x ?>" y="<?= $y ?>" width="<?= round($bw, 1) ?>" height="<?= max($h, 0.5) ?>" rx="2" fill="url(#barFill)">
                            <title><?= e($label) ?> · <?= (int) $r['c'] ?> cliques</title>
                        </rect>
                    </g>
                <?php endforeach; ?>
                <!-- x labels (sparse) -->
                <?php
                $tickEvery = $n > 30 ? (int) ceil($n / 10) : ($n > 7 ? (int) ceil($n / 7) : 1);
                foreach ($timeline as $i => $r):
                    if ($i % $tickEvery !== 0 && $i !== $n - 1) continue;
                    $x = round($padL + $i * (($innerW) / $n) + ($bw / 2) + 2, 1);
                ?>
                    <text x="<?= $x ?>" y="<?= $H - 8 ?>" text-anchor="middle" font-size="10" fill="currentColor" opacity=".55"><?= e(date('d/m', strtotime($r['d']))) ?></text>
                <?php endforeach; ?>
            </svg>
        </div>
    </section>

    <div class="analytics__grid">
        <!-- Distribuição por tipo (donut) -->
        <section class="admin-card">
            <header class="admin-card__head">
                <h2>Eventos por tipo</h2>
                <small class="admin-card__hint">distribuição</small>
            </header>
            <?php if (empty($by_type)): ?>
                <p class="admin-empty">Sem eventos no período.</p>
            <?php else: ?>
                <div class="analytics-donut">
                    <?php
                    $cx = 90; $cy = 90; $r = 70; $sw = 22;
                    $circ = 2 * pi() * $r;
                    $offset = 0;
                    ?>
                    <svg viewBox="0 0 180 180" width="180" height="180" aria-hidden="true">
                        <circle cx="<?= $cx ?>" cy="<?= $cy ?>" r="<?= $r ?>" fill="none" stroke="currentColor" stroke-opacity=".08" stroke-width="<?= $sw ?>"/>
                        <?php foreach ($by_type as $i => $t):
                            $pct = $t['c'] / $total;
                            $len = $pct * $circ;
                            $color = $donutColors[$i % count($donutColors)];
                        ?>
                            <circle cx="<?= $cx ?>" cy="<?= $cy ?>" r="<?= $r ?>"
                                    fill="none" stroke="<?= $color ?>" stroke-width="<?= $sw ?>"
                                    stroke-dasharray="<?= round($len, 2) ?> <?= round($circ - $len, 2) ?>"
                                    stroke-dashoffset="<?= round(-$offset, 2) ?>"
                                    transform="rotate(-90 <?= $cx ?> <?= $cy ?>)"
                                    stroke-linecap="butt">
                                <title><?= e($t['event_type']) ?> · <?= (int) $t['c'] ?></title>
                            </circle>
                        <?php $offset += $len; endforeach; ?>
                        <text x="<?= $cx ?>" y="<?= $cy - 2 ?>" text-anchor="middle" font-size="22" font-weight="800" fill="currentColor"><?= number_format($total, 0, ',', '.') ?></text>
                        <text x="<?= $cx ?>" y="<?= $cy + 16 ?>" text-anchor="middle" font-size="11" fill="currentColor" opacity=".6">eventos</text>
                    </svg>
                    <ul class="analytics-donut__legend">
                        <?php foreach ($by_type as $i => $t):
                            $color = $donutColors[$i % count($donutColors)];
                            $pct = round(($t['c'] / $total) * 100, 1);
                        ?>
                            <li>
                                <span class="dot" style="background: <?= e($color) ?>"></span>
                                <span class="name"><?= e($t['event_type']) ?></span>
                                <span class="val"><?= number_format((int) $t['c'], 0, ',', '.') ?> · <?= $pct ?>%</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </section>

        <!-- Top sources -->
        <section class="admin-card">
            <header class="admin-card__head">
                <h2>Top fontes</h2>
                <small class="admin-card__hint">por cliques</small>
            </header>
            <?php if (empty($sources)): ?>
                <p class="admin-empty">Sem fontes registradas.</p>
            <?php else:
                $maxS = max(array_map(fn($r)=>(int)$r['c'], $sources)); ?>
                <ol class="analytics-bars">
                    <?php foreach ($sources as $r): $pct = round(((int)$r['c'] / max(1,$maxS)) * 100); ?>
                        <li class="analytics-bars__row">
                            <span class="analytics-bars__name"><?= e((string)$r['source']) ?></span>
                            <span class="analytics-bars__track"><span class="analytics-bars__fill" style="width: <?= $pct ?>%"></span></span>
                            <span class="analytics-bars__val"><?= number_format((int)$r['c'], 0, ',', '.') ?></span>
                        </li>
                    <?php endforeach; ?>
                </ol>
            <?php endif; ?>
        </section>

        <!-- Top pages -->
        <section class="admin-card">
            <header class="admin-card__head">
                <h2>Top páginas</h2>
                <small class="admin-card__hint">por page views</small>
            </header>
            <?php if (empty($pages)): ?>
                <p class="admin-empty">Sem page views no período.</p>
            <?php else:
                $maxP = max(array_map(fn($r)=>(int)$r['c'], $pages)); ?>
                <ol class="analytics-bars">
                    <?php foreach ($pages as $r): $pct = round(((int)$r['c'] / max(1,$maxP)) * 100); ?>
                        <li class="analytics-bars__row">
                            <span class="analytics-bars__name" title="<?= e((string)$r['page_path']) ?>"><?= e((string)$r['page_path']) ?></span>
                            <span class="analytics-bars__track"><span class="analytics-bars__fill" style="width: <?= $pct ?>%"></span></span>
                            <span class="analytics-bars__val"><?= number_format((int)$r['c'], 0, ',', '.') ?></span>
                        </li>
                    <?php endforeach; ?>
                </ol>
            <?php endif; ?>
        </section>

        <!-- Top items -->
        <section class="admin-card">
            <header class="admin-card__head">
                <h2>Top produtos / serviços</h2>
                <small class="admin-card__hint">cliques nos cards</small>
            </header>
            <?php if (empty($items)): ?>
                <p class="admin-empty">Sem cliques em produtos/serviços/promoções no período.</p>
            <?php else:
                $maxI = max(array_map(fn($r)=>(int)$r['clicks'], $items));
                $typeRoute = ['product' => '/admin/products', 'service' => '/admin/services', 'promotion' => '/admin/promotions'];
            ?>
                <ol class="analytics-bars">
                    <?php foreach ($items as $r):
                        $pct = round(((int)$r['clicks'] / max(1,$maxI)) * 100);
                        $href = ($typeRoute[$r['type']] ?? '/admin') . (!empty($r['id']) ? '/' . (int)$r['id'] . '/edit' : '');
                    ?>
                        <li class="analytics-bars__row">
                            <a class="analytics-bars__name" href="<?= e($href) ?>" title="editar"><?= e((string)$r['title']) ?> <small><?= e((string)$r['type']) ?></small></a>
                            <span class="analytics-bars__track"><span class="analytics-bars__fill" style="width: <?= $pct ?>%"></span></span>
                            <span class="analytics-bars__val"><?= number_format((int)$r['clicks'], 0, ',', '.') ?></span>
                        </li>
                    <?php endforeach; ?>
                </ol>
            <?php endif; ?>
        </section>
    </div>
</div>
