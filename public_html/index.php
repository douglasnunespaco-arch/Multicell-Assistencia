<?php
declare(strict_types=1);

require __DIR__ . '/app/core/Database.php';
require __DIR__ . '/app/core/functions.php';

$modulesConfig = require __DIR__ . '/app/config/modules.php';
$path = current_path();
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($path === '/track' && $method === 'POST') {
    $payload = json_decode(file_get_contents('php://input'), true) ?: [];
    $event = $payload['event_name'] ?? 'cta_click';
    analytics_track($event, $payload);
    header('Content-Type: application/json');
    echo json_encode(['ok' => true]);
    exit;
}

if ($path === '/submit-reservation' && $method === 'POST') {
    verify_csrf();
    $payload = [
        'customer_name' => trim($_POST['customer_name'] ?? ''),
        'customer_phone' => trim($_POST['customer_phone'] ?? ''),
        'device_brand' => trim($_POST['device_brand'] ?? ''),
        'device_model' => trim($_POST['device_model'] ?? ''),
        'service_type' => trim($_POST['service_type'] ?? ''),
        'desired_date' => trim($_POST['desired_date'] ?? ''),
        'desired_period' => trim($_POST['desired_period'] ?? ''),
        'issue_description' => trim($_POST['issue_description'] ?? ''),
        'notes' => trim($_POST['notes'] ?? ''),
        'status_label' => 'novo',
    ];

    if (db()->configured()) {
        $stmt = db()->pdo()->prepare('
            INSERT INTO lead_reservations (
                customer_name, customer_phone, device_brand, device_model, service_type,
                desired_date, desired_period, issue_description, notes, status_label, created_at, updated_at
            ) VALUES (
                :customer_name, :customer_phone, :device_brand, :device_model, :service_type,
                :desired_date, :desired_period, :issue_description, :notes, :status_label, NOW(), NOW()
            )
        ');
        $stmt->execute($payload);
    }

    analytics_track('reservation_submit', [
        'page_path' => '/reservar',
        'cta_label' => 'Enviar reserva',
        'entity_type' => 'reservation',
        'entity_label' => $payload['service_type'],
    ]);

    $phone = sanitize_whatsapp(setting('whatsapp_number', '5500000000000'));
    $message = "Olá! Quero reservar atendimento.%0A";
    $message .= "Nome: " . rawurlencode($payload['customer_name']) . "%0A";
    $message .= "Telefone: " . rawurlencode($payload['customer_phone']) . "%0A";
    $message .= "Aparelho: " . rawurlencode(trim($payload['device_brand'] . ' ' . $payload['device_model'])) . "%0A";
    $message .= "Serviço: " . rawurlencode($payload['service_type']) . "%0A";
    $message .= "Data/Período: " . rawurlencode(trim($payload['desired_date'] . ' ' . $payload['desired_period'])) . "%0A";
    $message .= "Defeito: " . rawurlencode($payload['issue_description']);
    header('Location: https://wa.me/' . $phone . '?text=' . $message);
    exit;
}

if ($path === '/admin' || $path === '/admin/login') {
    if ($method === 'POST') {
        verify_csrf();
        $ok = login_admin(trim($_POST['email'] ?? ''), trim($_POST['password'] ?? ''));
        if ($ok) redirect_to('/admin/dashboard');
        render('admin_login', ['title' => 'Login Admin', 'errorMessage' => 'Usuário ou senha inválidos.'], 'admin');
        exit;
    }
    render('admin_login', ['title' => 'Login Admin'], 'admin');
    exit;
}

if ($path === '/admin/logout') {
    logout_admin();
    redirect_to('/admin/login');
}

if (str_starts_with($path, '/admin')) {
    require_admin();

    if ($path === '/admin/dashboard') {
        render('admin_dashboard', ['title' => 'Dashboard', 'subtitle' => 'Visão rápida de analytics, módulos e reservas.', 'summary' => analytics_summary(), 'leads' => reservation_rows()], 'admin');
        exit;
    }

    if ($path === '/admin/settings') {
        $keys = ['brand_name','tagline','hero_title','hero_subtitle','primary_cta_label','primary_cta_url','secondary_cta_label','secondary_cta_url','whatsapp_number','instagram_url','facebook_url','address','business_hours','seo_title','seo_description','announcement_bar','footer_note'];
        if ($method === 'POST') {
            verify_csrf();
            if (save_settings($keys)) flash('success', 'Settings salvos com sucesso.');
            else flash('error', 'Banco não configurado. Edite o config.php e importe o SQL.');
            redirect_to('/admin/settings');
        }
        render('admin_settings', ['title' => 'Settings', 'subtitle' => 'Dados centrais da marca, CTAs e SEO básico.', 'keys' => $keys], 'admin');
        exit;
    }

    if ($path === '/admin/leads') {
        render('admin_leads', ['title' => 'Leads / Reservas', 'subtitle' => 'Solicitações enviadas pelo site.', 'rows' => reservation_rows()], 'admin');
        exit;
    }

    if ($path === '/admin/analytics') {
        render('admin_analytics', ['title' => 'Analytics', 'subtitle' => 'Cliques, páginas, origens e entidades.', 'summary' => analytics_summary()], 'admin');
        exit;
    }

    if (preg_match('#^/admin/module/([a-z_]+)(?:/(new|edit|delete|toggle)(?:/([0-9]+))?)?$#', $path, $matches)) {
        $module = $matches[1] ?? '';
        $action = $matches[2] ?? 'list';
        $id = isset($matches[3]) ? (int) $matches[3] : null;

        if (!isset($modulesConfig[$module])) {
            http_response_code(404);
            exit('Módulo não encontrado.');
        }

        if ($action === 'delete' && $id) {
            delete_module_item($module, $id, $modulesConfig);
            flash('success', 'Item excluído.');
            redirect_to('/admin/module/' . $module);
        }
        if ($action === 'toggle' && $id) {
            toggle_module_item($module, $id, $modulesConfig);
            flash('success', 'Status atualizado.');
            redirect_to('/admin/module/' . $module);
        }
        if (in_array($action, ['new', 'edit'], true)) {
            if ($method === 'POST') {
                verify_csrf();
                save_module_item($module, $modulesConfig, $id);
                flash('success', 'Item salvo com sucesso.');
                redirect_to('/admin/module/' . $module);
            }
            render('admin_module_form', ['title' => $modulesConfig[$module]['title'], 'subtitle' => 'Cadastro e edição.', 'module' => $module, 'moduleDef' => $modulesConfig[$module], 'item' => $id ? fetch_module_item($module, $id, $modulesConfig) : []], 'admin');
            exit;
        }
        render('admin_module_list', ['title' => $modulesConfig[$module]['title'], 'subtitle' => 'Gerencie registros do módulo.', 'module' => $module, 'moduleDef' => $modulesConfig[$module], 'items' => fetch_module_items($module, $modulesConfig)], 'admin');
        exit;
    }

    http_response_code(404);
    exit('Página administrativa não encontrada.');
}

switch ($path) {
    case '/': render('home', ['title' => setting('seo_title', 'Multi Cell')]); break;
    case '/assistencia': render('page_services', ['title' => 'Assistência Técnica | Multi Cell']); break;
    case '/produtos': render('page_products', ['title' => 'Produtos | Multi Cell']); break;
    case '/promocoes': render('page_promotions', ['title' => 'Promoções | Multi Cell']); break;
    case '/reservar': render('page_reservation', ['title' => 'Reservar Atendimento | Multi Cell']); break;
    case '/sobre': render('page_about', ['title' => 'Sobre | Multi Cell']); break;
    case '/contato': render('page_contact', ['title' => 'Contato | Multi Cell']); break;
    case '/links': render('page_links', ['title' => 'Links | Multi Cell']); break;
    default:
        http_response_code(404);
        render('page_about', ['title' => 'Página não encontrada | Multi Cell']);
}
