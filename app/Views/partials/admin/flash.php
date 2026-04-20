<?php
$flash = \App\Core\Flash::pull();
if (empty($flash)) return;
?>
<div class="admin-flash-stack" data-testid="admin-flash-stack">
<?php foreach ($flash as $f): ?>
    <div class="admin-flash admin-flash--<?= e($f['type']) ?>" data-testid="flash-<?= e($f['type']) ?>" role="alert">
        <?= e($f['message']) ?>
    </div>
<?php endforeach; ?>
</div>
