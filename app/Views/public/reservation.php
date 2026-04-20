<?php /** @var ?array $branch */ ?>
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
            <form class="form-card" method="post" action="/reservar" data-reveal>
                <?= \App\Core\Csrf::field() ?>
                <input type="text" name="website" tabindex="-1" autocomplete="off" style="position:absolute;left:-9999px;" aria-hidden="true">

                <div class="form-grid">
                    <div class="field full">
                        <label>Nome <span class="req">*</span></label>
                        <input type="text" name="customer_name" required maxlength="160" placeholder="Seu nome completo">
                    </div>
                    <div class="field">
                        <label>Telefone / WhatsApp <span class="req">*</span></label>
                        <input type="tel" name="phone" required maxlength="20" placeholder="(65) 90000-0000" inputmode="tel">
                    </div>
                    <div class="field">
                        <label>Tipo de atendimento</label>
                        <select name="service_type">
                            <option value="loja">Levar na loja</option>
                            <option value="retirar">Solicitar retirada</option>
                            <option value="expressa">Atendimento expresso</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>Marca do aparelho</label>
                        <input type="text" name="device_brand" maxlength="80" placeholder="Ex: Samsung, Apple, Xiaomi">
                    </div>
                    <div class="field">
                        <label>Modelo</label>
                        <input type="text" name="device_model" maxlength="120" placeholder="Ex: Galaxy S22, iPhone 13">
                    </div>
                    <div class="field full">
                        <label>Qual o problema?</label>
                        <textarea name="issue_description" rows="3" placeholder="Descreva brevemente o defeito ou serviço desejado."></textarea>
                    </div>
                    <div class="field">
                        <label>Dia desejado</label>
                        <input type="date" name="preferred_date" min="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="field">
                        <label>Período</label>
                        <select name="preferred_period">
                            <option value="">Sem preferência</option>
                            <option value="manha">Manhã</option>
                            <option value="tarde">Tarde</option>
                            <option value="noite">Noite</option>
                        </select>
                    </div>
                    <div class="field full">
                        <label>Observação</label>
                        <textarea name="notes" rows="2" placeholder="Algo mais que a equipe precisa saber?"></textarea>
                    </div>
                </div>

                <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:20px;align-items:center;">
                    <button type="submit" class="btn btn--primary btn--lg"
                            data-track="cta_click" data-track-source="reserve_submit">
                        <?= icon('phone', 18) ?> Reservar e abrir WhatsApp
                    </button>
                    <small style="color:var(--fg-2);font-size:12px;max-width:260px;">
                        Seus dados serão enviados apenas à nossa equipe para agendar o atendimento.
                    </small>
                </div>
                <p style="color:var(--warning);font-size:13px;margin-top:18px;">
                    <?= icon('clock', 14) ?> O envio completo do formulário é processado na próxima fase. Por enquanto, use o botão do WhatsApp para agilizar o contato.
                </p>
            </form>

            <aside class="reserve-aside" data-reveal>
                <h3>Por que reservar?</h3>
                <ul>
                    <li><?= icon('clock', 18) ?> <span>Atendimento ágil, sem espera.</span></li>
                    <li><?= icon('shield', 18) ?> <span>Orçamento grátis antes de iniciar.</span></li>
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
                    <?= icon('phone', 16) ?> Prefiro já falar no WhatsApp
                </a>
            </aside>
        </div>
    </div>
</section>
