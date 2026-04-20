<?php
/**
 * @var int    $leadId        ID da reserva criada
 * @var string $waUrl         URL final do WhatsApp com mensagem pronta
 * @var string $customerName  Nome do cliente (para saudação)
 */
?>
<section class="page-banner">
    <div class="container">
        <nav class="breadcrumb"><a href="/">Início</a> / <a href="/reservar">Reservar</a> / <span>Sucesso</span></nav>
        <span class="eyebrow">Reserva confirmada</span>
        <h1>Pronto, <em style="color:var(--brand);font-style:normal;"><?= e($customerName ?: 'tudo certo') ?></em>!</h1>
        <p>Sua reserva foi registrada. Continue no WhatsApp para confirmar o horário com a equipe.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="form-card" data-reveal style="max-width:720px;margin:0 auto;text-align:center;">
            <div style="display:flex;flex-direction:column;align-items:center;gap:12px;margin-bottom:16px;">
                <span style="width:56px;height:56px;border-radius:50%;background:rgba(20,241,149,.12);border:1px solid rgba(20,241,149,.4);display:flex;align-items:center;justify-content:center;color:var(--brand);">
                    <?= icon('check-circle', 28) ?>
                </span>
                <strong style="font-size:18px;color:var(--fg-0);">Reserva #<?= (int) $leadId ?> registrada</strong>
                <small style="color:var(--fg-2);">Seus dados foram salvos. Um atendente confirmará no WhatsApp.</small>
            </div>

            <div style="display:flex;gap:10px;flex-wrap:wrap;justify-content:center;margin:18px 0 8px;">
                <a href="<?= e($waUrl) ?>" class="btn btn--primary btn--lg"
                   data-track="whatsapp_click" data-track-source="reservation_success_primary">
                    <?= icon('whatsapp', 18) ?> Abrir WhatsApp agora
                </a>
                <a href="/" class="btn btn--ghost btn--lg">Voltar ao site</a>
            </div>

            <p style="color:var(--fg-2);font-size:13px;margin-top:14px;display:flex;gap:8px;align-items:center;justify-content:center;">
                <?= icon('info', 14) ?>
                <span>Se o WhatsApp não abrir automaticamente, use o botão acima.</span>
            </p>
        </div>
    </div>
</section>

<script>
// Auto-abre o WhatsApp em nova aba (pop-ups podem bloquear; o botão acima é fallback).
(function() {
    try {
        var url = <?= json_encode($waUrl, JSON_UNESCAPED_SLASHES) ?>;
        if (url) { window.open(url, '_blank', 'noopener'); }
    } catch (e) {}
})();
</script>
