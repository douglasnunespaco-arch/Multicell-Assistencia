<?php
/**
 * Email · Weekly digest. HTML inline-styled (compat com clientes de email).
 * Variáveis disponíveis (do WeeklyDigest.php):
 *   $totalWeek (int) · $prevWeek (int) · $leadsWeek (int) · $daysHit (int)
 *   $delta (int) · $pct (int) · $weekNum (int) · $top (array)
 *   $siteName (string) · $appUrl (string) · $goal (int)
 */
$arrow = $delta >= 0 ? '↑' : '↓';
$tone  = $delta >= 0 ? '#0FB878' : '#C81C39';
$h = fn($s) => htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
?><!doctype html>
<html lang="pt-BR"><head><meta charset="utf-8"><title>Resumo semanal</title></head>
<body style="margin:0;padding:24px;font-family:-apple-system,Segoe UI,Roboto,Arial,sans-serif;background:#f4f5f8;color:#14161c;">
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:640px;margin:0 auto;background:#ffffff;border-radius:14px;overflow:hidden;border:1px solid #e2e5eb;">
    <tr>
      <td style="padding:28px 28px 0;background:linear-gradient(135deg,#0A0A0B 0%,#15171B 100%);color:#fff;">
        <div style="display:inline-block;background:#14F195;color:#0A0A0B;padding:6px 12px;border-radius:999px;font-size:11px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;">SEMANA <?= (int) $weekNum ?></div>
        <h1 style="margin:14px 0 4px;font-size:26px;font-weight:800;letter-spacing:-.02em;"><?= $h($siteName) ?> · resumo</h1>
        <p style="margin:0 0 24px;color:#b8bcc4;font-size:14px;">7 dias de movimento real no seu site.</p>
      </td>
    </tr>
    <tr>
      <td style="padding:28px;">
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
          <tr>
            <td style="padding:14px 16px;background:#f4f5f8;border-radius:10px;width:50%;">
              <div style="font-size:11px;color:#6a6f78;text-transform:uppercase;letter-spacing:.08em;font-weight:600;">CLIQUES NA SEMANA</div>
              <div style="font-size:32px;font-weight:800;color:#14161c;line-height:1.1;margin-top:4px;"><?= number_format($totalWeek, 0, ',', '.') ?></div>
              <div style="font-size:13px;color:<?= $tone ?>;font-weight:700;margin-top:4px;"><?= $arrow ?> <?= ($delta >= 0 ? '+' : '') . number_format($delta, 0, ',', '.') ?> vs semana anterior <?= $pct !== 0 ? '(' . ($delta >= 0 ? '+' : '') . $pct . '%)' : '' ?></div>
            </td>
            <td style="width:14px;"></td>
            <td style="padding:14px 16px;background:#f4f5f8;border-radius:10px;width:50%;">
              <div style="font-size:11px;color:#6a6f78;text-transform:uppercase;letter-spacing:.08em;font-weight:600;">METAS BATIDAS</div>
              <div style="font-size:32px;font-weight:800;color:#14161c;line-height:1.1;margin-top:4px;"><?= (int) $daysHit ?>/7</div>
              <div style="font-size:13px;color:#3a3f48;margin-top:4px;">dias com ≥ <?= (int) $goal ?> cliques</div>
            </td>
          </tr>
        </table>

        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="margin-top:18px;">
          <tr>
            <td style="padding:14px 16px;background:#f4f5f8;border-radius:10px;">
              <div style="font-size:11px;color:#6a6f78;text-transform:uppercase;letter-spacing:.08em;font-weight:600;">RESERVAS RECEBIDAS</div>
              <div style="font-size:24px;font-weight:800;color:#14161c;line-height:1.1;margin-top:4px;"><?= (int) $leadsWeek ?> <span style="font-weight:500;font-size:13px;color:#3a3f48;">novos leads na semana</span></div>
            </td>
          </tr>
        </table>

        <?php if (!empty($top)): ?>
        <h2 style="margin:24px 0 10px;font-size:15px;color:#14161c;border-top:1px solid #e2e5eb;padding-top:18px;">🔥 Hot path da semana</h2>
        <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:separate;border-spacing:0 6px;">
          <?php foreach ($top as $i => $r): ?>
          <tr>
            <td style="padding:10px 14px;background:#f4f5f8;border-radius:8px;">
              <span style="display:inline-block;width:24px;color:#6a6f78;font-weight:700;font-size:12px;">#<?= $i + 1 ?></span>
              <strong style="font-size:14px;color:#14161c;"><?= $h($r['title']) ?></strong>
              <span style="font-size:11px;color:#6a6f78;text-transform:uppercase;letter-spacing:.06em;margin-left:6px;"><?= $h($r['type']) ?></span>
              <span style="float:right;color:#0a7a4d;font-weight:700;background:rgba(15,184,120,.12);padding:3px 10px;border-radius:99px;font-size:13px;"><?= number_format((int) $r['c'], 0, ',', '.') ?> cliques</span>
            </td>
          </tr>
          <?php endforeach; ?>
        </table>
        <?php endif; ?>

        <?php if ($appUrl): ?>
        <p style="margin:28px 0 0;text-align:center;">
          <a href="<?= $h($appUrl) ?>/admin" style="display:inline-block;padding:12px 22px;background:#14F195;color:#0A0A0B;text-decoration:none;border-radius:999px;font-weight:700;font-size:14px;">Abrir o painel →</a>
        </p>
        <?php endif; ?>

        <p style="margin:24px 0 0;font-size:11px;color:#6a6f78;text-align:center;">
          Você recebe esse resumo toda segunda às 9h. Para ajustar a meta diária, acesse <em>Configurações</em> no painel.
        </p>
      </td>
    </tr>
  </table>
</body></html>
