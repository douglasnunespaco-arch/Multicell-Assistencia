<?php
/** @var ?array $branch */
/** @var array $errors */
/** @var array $old */
$errors = $errors ?? [];
$old    = $old ?? [];
$flash  = \App\Core\Flash::pull();
?>
<section class="page-banner">
    <div class="container">
        <nav class="breadcrumb"><a href="/">Início</a> / <span>Reservar</span></nav>
        <span class="eyebrow">Reserva de atendimento</span>
        <h1>Reserve seu horário em <em style="color:var(--brand);font-style:normal;">30 segundos</em>.</h1>
        <p>Preencha os dados abaixo e confirmamos seu atendimento no WhatsApp.</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="reserve-grid">
            <form id="form" class="form-card" method="post" action="/reservar" data-reveal novalidate>
                <?= \App\Core\Csrf::field() ?>
                <input type="text" name="website" tabindex="-1" autocomplete="off" style="position:absolute;left:-9999px;" aria-hidden="true">

                <?php foreach ($flash as $f): ?>
                    <div class="alert alert--<?= e($f['type']) ?>" role="alert" style="margin-bottom:14px;padding:10px 14px;border-radius:8px;font-size:14px;<?= $f['type']==='error' ? 'background:rgba(255,77,109,.1);border:1px solid rgba(255,77,109,.35);color:#ffb3c1;' : 'background:rgba(20,241,149,.08);border:1px solid rgba(20,241,149,.35);color:#b5f7d3;' ?>">
                        <?= e($f['message']) ?>
                    </div>
                <?php endforeach; ?>

                <div class="form-grid">
                    <div class="field full">
                        <label>Nome <span class="req">*</span></label>
                        <input type="text" name="customer_name" required maxlength="160" placeholder="Seu nome completo" value="<?= e($old['customer_name'] ?? '') ?>">
                        <?php if (!empty($errors['customer_name'])): ?><small class="field-error" style="color:#ff4d6d;font-size:12px;"><?= e($errors['customer_name']) ?></small><?php endif; ?>
                    </div>
                    <div class="field">
                        <label>Telefone / WhatsApp <span class="req">*</span></label>
                        <input type="tel" name="phone" required maxlength="20" placeholder="(65) 90000-0000" inputmode="tel" value="<?= e($old['phone'] ?? '') ?>">
                        <?php if (!empty($errors['phone'])): ?><small class="field-error" style="color:#ff4d6d;font-size:12px;"><?= e($errors['phone']) ?></small><?php endif; ?>
                    </div>
                    <div class="field">
                        <label>Tipo de atendimento</label>
                        <select name="service_type">
                            <?php foreach (['loja'=>'Levar na loja','retirar'=>'Solicitar retirada','expressa'=>'Atendimento expresso'] as $k => $lbl): ?>
                                <option value="<?= e($k) ?>" <?= (($old['service_type'] ?? '') === $k) ? 'selected' : '' ?>><?= e($lbl) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="field">
                        <label>Marca do aparelho</label>
                        <input type="text" name="device_brand" maxlength="80" placeholder="Ex: Samsung, Apple, Xiaomi" value="<?= e($old['device_brand'] ?? '') ?>">
                    </div>
                    <div class="field">
                        <label>Modelo</label>
                        <input type="text" name="device_model" maxlength="120" placeholder="Ex: Galaxy S22, iPhone 13" value="<?= e($old['device_model'] ?? '') ?>">
                    </div>
                    <div class="field full">
                        <label>Qual o problema?</label>
                        <textarea name="issue_description" rows="3" placeholder="Descreva brevemente o defeito ou serviço desejado."><?= e($old['issue_description'] ?? '') ?></textarea>
                    </div>
                    <div class="field">
                        <label>Dia desejado</label>
                        <input type="date" name="preferred_date" min="<?= date('Y-m-d') ?>" value="<?= e($old['preferred_date'] ?? '') ?>">
                        <?php if (!empty($errors['preferred_date'])): ?><small class="field-error" style="color:#ff4d6d;font-size:12px;"><?= e($errors['preferred_date']) ?></small><?php endif; ?>
                    </div>
                    <div class="field">
                        <label>Período</label>
                        <select name="preferred_period">
                            <?php foreach (['' => 'Sem preferência', 'manha' => 'Manhã', 'tarde' => 'Tarde', 'noite' => 'Noite'] as $k => $lbl): ?>
                                <option value="<?= e($k) ?>" <?= (($old['preferred_period'] ?? '') === $k) ? 'selected' : '' ?>><?= e($lbl) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="field full">
                        <label>Observação</label>
                        <textarea name="notes" rows="2" placeholder="Algo mais que a equipe precisa saber?"><?= e($old['notes'] ?? '') ?></textarea>
                    </div>
                </div>

                <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:20px;align-items:center;">
                    <button type="submit" class="btn btn--primary btn--lg"
                            data-track="cta_click" data-track-source="reserve_submit">
                        <?= icon('whatsapp', 18) ?> Reservar e abrir WhatsApp
                    </button>
                    <small style="color:var(--fg-2);font-size:12px;max-width:260px;">
                        Seus dados serão enviados apenas à nossa equipe para agendar o atendimento.
                    </small>
                </div>
            </form>

            <aside class="reserve-aside" data-reveal>
                <h3>Por que reservar?</h3>
                <ul>
                    <li><?= icon('clock', 18) ?> <span>Atendimento ágil, sem espera.</span></li>
                    <li><?= icon('shield-check', 18) ?> <span>Orçamento grátis antes de iniciar.</span></li>
                    <li><?= icon('award', 18) ?> <span>Garantia de 90 dias em serviços.</span></li>
                    <li><?= icon('heart', 18) ?> <span>Atendimento humano e transparente.</span></li>
                </ul>
                <hr class="section-divider" style="margin:10px 0;">
                <?php if ($branch): ?>
                    <div style="font-size:14px;color:var(--fg-1);">
                        <strong style="color:var(--fg-0);display:block;margin-bottom:4px;"><?= e($branch['name']) ?></strong>
                        <?= e($branch['address']) ?><br>
                        <?= e($branch['city']) ?>/<?= e($branch['state']) ?><br>
                        <?= e($branch['hours_text'] ?? \App\Models\Setting::get('hours_default')) ?>
                    </div>
                <?php endif; ?>
                <a href="<?= whatsapp_link('reserve_aside') ?>" class="btn btn--primary btn--block" data-track="whatsapp_click" data-track-source="reserve_aside">
                    <?= icon('whatsapp', 16) ?> Prefiro já falar no WhatsApp
                </a>
            </aside>
        </div>
    </div>
</section>
