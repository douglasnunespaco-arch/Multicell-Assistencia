<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title ?? 'Admin | Multi Cell') ?></title>
    <link rel="stylesheet" href="<?= asset_url('assets/css/admin.css') ?>">
</head>
<body>
<div class="admin-shell">
    <aside class="admin-sidebar">
        <div class="admin-brand">
            <img src="<?= asset_url('assets/img/logo.png') ?>" alt="Multi Cell">
            <div>
                <strong>Multi Cell</strong>
                <small>Painel Admin</small>
            </div>
        </div>
        <nav>
            <a href="<?= base_url('/admin/dashboard') ?>">Dashboard</a>
            <a href="<?= base_url('/admin/module/hero_slides') ?>">Hero / Slides</a>
            <a href="<?= base_url('/admin/module/services') ?>">Serviços</a>
            <a href="<?= base_url('/admin/module/products') ?>">Produtos</a>
            <a href="<?= base_url('/admin/module/promotions') ?>">Promoções</a>
            <a href="<?= base_url('/admin/module/testimonials') ?>">Depoimentos</a>
            <a href="<?= base_url('/admin/module/about_blocks') ?>">Sobre</a>
            <a href="<?= base_url('/admin/module/branches') ?>">Unidade</a>
            <a href="<?= base_url('/admin/settings') ?>">Settings</a>
            <a href="<?= base_url('/admin/leads') ?>">Leads / Reservas</a>
            <a href="<?= base_url('/admin/analytics') ?>">Analytics</a>
            <a href="<?= base_url('/admin/logout') ?>">Sair</a>
        </nav>
    </aside>
    <section class="admin-content">
        <header class="admin-topbar">
            <div>
                <h1><?= htmlspecialchars($title ?? 'Painel Admin') ?></h1>
                <p><?= htmlspecialchars($subtitle ?? 'Gestão operacional do site.') ?></p>
            </div>
            <div class="admin-user">
                <strong><?= htmlspecialchars(admin_user()['name']) ?></strong>
                <small><?= htmlspecialchars(admin_user()['email']) ?></small>
            </div>
        </header>
        <?php if ($success = flash('success')): ?><div class="alert success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
        <?php if ($error = flash('error')): ?><div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <?= $content ?>
    </section>
</div>
</body>
</html>
