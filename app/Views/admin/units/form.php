<?php
/** @var ?array $row */
use App\Core\FormField as F;
$isEdit = !empty($row);
$action = $isEdit ? '/admin/units/' . (int) $row['id'] . '/update' : '/admin/units/store';
$row = $row ?? [
    'name'=>'','address'=>'','city'=>'Várzea Grande','state'=>'MT','zip_code'=>'',
    'phone'=>'','whatsapp'=>'','hours_text'=>'',
    'latitude'=>'','longitude'=>'','map_embed_url'=>'',
    'sort_order'=>0,'is_active'=>1
];
?>
<div class="admin-card">
    <header class="admin-card__head">
        <h2><?= $isEdit ? 'Editar unidade #' . (int) $row['id'] : 'Nova unidade' ?></h2>
        <a class="admin-btn admin-btn--sm" href="/admin/units">← Voltar</a>
    </header>
    <form method="post" action="<?= e($action) ?>" class="admin-form">
        <?= \App\Core\Csrf::field() ?>
        <?= F::text('name', 'Nome da unidade', $row['name'], ['required' => true, 'placeholder' => 'Ex.: Multi Cell Várzea Grande']) ?>
        <?= F::text('address', 'Endereço', $row['address'], ['required' => true, 'placeholder' => 'Rua, número, bairro']) ?>
        <div class="admin-form__row">
            <?= F::text('city', 'Cidade', $row['city'], ['required' => true]) ?>
            <?= F::text('state', 'UF', $row['state'], ['required' => true, 'placeholder' => 'MT']) ?>
            <?= F::text('zip_code', 'CEP', $row['zip_code'] ?? '', ['placeholder' => '78000-000']) ?>
        </div>
        <div class="admin-form__row">
            <?= F::text('phone', 'Telefone', $row['phone'] ?? '', ['placeholder' => '(65) 0000-0000']) ?>
            <?= F::text('whatsapp', 'WhatsApp', $row['whatsapp'] ?? '', ['placeholder' => '5565900000000']) ?>
        </div>
        <?= F::text('hours_text', 'Horário (texto)', $row['hours_text'] ?? '', ['placeholder' => 'Seg a Sex 8h–18h · Sáb 8h–12h']) ?>
        <div class="admin-form__row">
            <?= F::text('latitude', 'Latitude', (string) ($row['latitude'] ?? ''), ['placeholder' => '-15.6487']) ?>
            <?= F::text('longitude', 'Longitude', (string) ($row['longitude'] ?? ''), ['placeholder' => '-56.1322']) ?>
        </div>
        <?= F::textarea('map_embed_url', 'Google Maps embed URL', $row['map_embed_url'] ?? '', ['rows' => 2, 'hint' => 'Cole apenas a URL do iframe src do Google Maps']) ?>
        <div class="admin-form__row">
            <?= F::number('sort_order', 'Ordem', $row['sort_order']) ?>
            <?= F::checkbox('is_active', 'Ativa no site', $row['is_active']) ?>
        </div>
        <div class="admin-form__actions">
            <a class="admin-btn" href="/admin/units">Cancelar</a>
            <button type="submit" class="admin-btn admin-btn--primary">Salvar</button>
        </div>
    </form>
</div>
