<?php
/** @var array $values */
/** @var string[] $text_keys */
/** @var string[] $image_keys */
use App\Core\FormField as F;

$labels = [
    'site_name'              => 'Nome do site',
    'site_tagline'           => 'Slogan / tagline',
    'site_description'       => 'Descrição curta (meta description)',
    'contact_phone_whatsapp' => 'WhatsApp (só dígitos · DDI+DDD+número)',
    'contact_phone_display'  => 'Telefone exibido',
    'contact_email'          => 'E-mail de contato',
    'contact_address'        => 'Endereço',
    'contact_city'           => 'Cidade',
    'contact_state'          => 'UF',
    'contact_zip'            => 'CEP',
    'business_hours'         => 'Horário de funcionamento',
    'google_maps_url'        => 'URL do Google Maps',
    'instagram_url'          => 'Instagram',
    'facebook_url'           => 'Facebook',
    'tiktok_url'             => 'TikTok',
    'youtube_url'            => 'YouTube',
    'links_rating_label'     => 'Selo de avaliação (ex.: 4,9★ no Google)',
    'links_social_proof'     => 'Prova social (ex.: +1.200 clientes atendidos)',
    'delivery_hero_title'        => 'Título do hero (Delivery)',
    'delivery_hero_subtitle'     => 'Subtítulo do hero (Delivery)',
    'delivery_area'              => 'Área atendida',
    'delivery_hours'             => 'Horário de atendimento (Delivery)',
    'delivery_whatsapp_message'  => 'Mensagem pré-preenchida no WhatsApp',
];
$imgLabels = [
    'site_logo_path'    => ['Logo do site',   'PNG/SVG · fundo transparente (até 5MB)'],
    'site_favicon_path' => ['Favicon',        'PNG quadrado · 64×64px ou maior'],
    'site_og_path'      => ['Imagem OG base', '1200×630px · aparece em compartilhamentos'],
];
$longFields = ['contact_address','business_hours','site_description','google_maps_url'];
?>
<div class="admin-card">
    <header class="admin-card__head">
        <h2>Configurações gerais</h2>
        <small class="admin-card__hint">branding · contato · redes sociais</small>
    </header>
    <form method="post" action="/admin/settings/update" enctype="multipart/form-data" class="admin-form">
        <?= \App\Core\Csrf::field() ?>

        <h3 class="admin-form__section">Branding</h3>
        <div class="admin-form__grid">
            <?php foreach ($image_keys as $k):
                [$label, $hint] = $imgLabels[$k] ?? [$k, ''];
                echo F::file($k, $label, $values[$k] ?? null, ['hint' => $hint]);
            endforeach; ?>
        </div>

        <h3 class="admin-form__section">Identidade</h3>
        <?= F::text('site_name', $labels['site_name'], $values['site_name'] ?? '') ?>
        <?= F::text('site_tagline', $labels['site_tagline'], $values['site_tagline'] ?? '') ?>
        <?= F::textarea('site_description', $labels['site_description'], $values['site_description'] ?? '', ['rows' => 2]) ?>

        <h3 class="admin-form__section">Contato</h3>
        <div class="admin-form__row">
            <?= F::text('contact_phone_whatsapp', $labels['contact_phone_whatsapp'], $values['contact_phone_whatsapp'] ?? '', ['placeholder' => '5565999999999']) ?>
            <?= F::text('contact_phone_display',  $labels['contact_phone_display'],  $values['contact_phone_display'] ?? '') ?>
            <?= F::text('contact_email',          $labels['contact_email'],          $values['contact_email'] ?? '') ?>
        </div>
        <?= F::textarea('contact_address', $labels['contact_address'], $values['contact_address'] ?? '', ['rows' => 2]) ?>
        <div class="admin-form__row">
            <?= F::text('contact_city',  $labels['contact_city'],  $values['contact_city'] ?? '') ?>
            <?= F::text('contact_state', $labels['contact_state'], $values['contact_state'] ?? '') ?>
            <?= F::text('contact_zip',   $labels['contact_zip'],   $values['contact_zip'] ?? '') ?>
        </div>
        <?= F::textarea('business_hours', $labels['business_hours'], $values['business_hours'] ?? '', ['rows' => 2]) ?>
        <?= F::url('google_maps_url', $labels['google_maps_url'], $values['google_maps_url'] ?? '') ?>

        <h3 class="admin-form__section">Redes sociais</h3>
        <div class="admin-form__row">
            <?= F::url('instagram_url', $labels['instagram_url'], $values['instagram_url'] ?? '') ?>
            <?= F::url('facebook_url',  $labels['facebook_url'],  $values['facebook_url'] ?? '') ?>
            <?= F::url('tiktok_url',    $labels['tiktok_url'],    $values['tiktok_url'] ?? '') ?>
            <?= F::url('youtube_url',   $labels['youtube_url'],   $values['youtube_url'] ?? '') ?>
        </div>

        <h3 class="admin-form__section">Página /links · selo de confiança</h3>
        <div class="admin-form__row">
            <?= F::text('links_rating_label', $labels['links_rating_label'], $values['links_rating_label'] ?? '', ['placeholder' => '4,9★ no Google', 'hint' => 'Deixe em branco para ocultar.']) ?>
            <?= F::text('links_social_proof', $labels['links_social_proof'], $values['links_social_proof'] ?? '', ['placeholder' => '+1.200 clientes atendidos', 'hint' => 'Aparece ao lado do selo, abaixo do avatar.']) ?>
        </div>

        <h3 class="admin-form__section">Página /delivery · Compre pelo WhatsApp</h3>
        <?= F::text('delivery_hero_title', $labels['delivery_hero_title'], $values['delivery_hero_title'] ?? '', ['placeholder' => 'Compre sem sair de casa']) ?>
        <?= F::textarea('delivery_hero_subtitle', $labels['delivery_hero_subtitle'], $values['delivery_hero_subtitle'] ?? '', ['rows' => 2, 'placeholder' => 'Escolha no catálogo, finalize pelo WhatsApp e receba na porta de casa.']) ?>
        <div class="admin-form__row">
            <?= F::text('delivery_area',  $labels['delivery_area'],  $values['delivery_area']  ?? '', ['placeholder' => 'Várzea Grande e Cuiabá / MT']) ?>
            <?= F::text('delivery_hours', $labels['delivery_hours'], $values['delivery_hours'] ?? '', ['placeholder' => 'Seg a Sáb · 9h às 18h']) ?>
        </div>
        <?= F::textarea('delivery_whatsapp_message', $labels['delivery_whatsapp_message'], $values['delivery_whatsapp_message'] ?? '', ['rows' => 2, 'placeholder' => 'Olá! Quero fazer um pedido com entrega.', 'hint' => 'Texto que já vai preenchido no WhatsApp ao clicar em "Fazer pedido".']) ?>

        <div class="admin-form__actions">
            <button type="submit" class="admin-btn admin-btn--primary" data-testid="settings-save">Salvar configurações</button>
        </div>
    </form>
</div>
