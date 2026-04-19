<?php

function app_config()
{
    static $config;
    if ($config === null) {
        $config = require __DIR__ . '/../config/config.php';
    }
    return $config;
}

function db()
{
    static $db;
    if ($db === null) {
        $db = Database::instance(app_config());
    }
    return $db;
}

function fallback_content()
{
    static $fallback;
    if ($fallback === null) {
        $fallback = require __DIR__ . '/../config/fallback_content.php';
    }
    return $fallback;
}

function base_url($path = '')
{
    $base = rtrim(app_config()['base_url'] ?? '', '/');
    if ($base === '') {
        return $path ?: '/';
    }
    $path = '/' . ltrim($path, '/');
    return $base . ($path === '/' ? '' : $path);
}

function asset_url($path)
{
    return base_url($path);
}

function redirect_to($path)
{
    header('Location: ' . base_url($path));
    exit;
}

function start_session()
{
    $config = app_config();
    if (session_status() === PHP_SESSION_NONE) {
        session_name($config['security']['session_name'] ?? 'admin_session');
        session_start();
    }
}

function csrf_token()
{
    start_session();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf()
{
    start_session();
    $posted = $_POST['_csrf'] ?? '';
    if (!$posted || !hash_equals($_SESSION['csrf_token'] ?? '', $posted)) {
        http_response_code(419);
        exit('Token CSRF inválido.');
    }
}

function is_admin_logged_in()
{
    start_session();
    return !empty($_SESSION['admin_id']);
}

function require_admin()
{
    if (!is_admin_logged_in()) {
        redirect_to('/admin/login');
    }
}

function flash($key, $message = null)
{
    start_session();
    if ($message === null) {
        $value = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $value;
    }
    $_SESSION['flash'][$key] = $message;
}

function setting($key, $default = '')
{
    static $settings = null;
    if ($settings === null) {
        $settings = [];
        if (db()->configured()) {
            try {
                $stmt = db()->pdo()->query('SELECT setting_key, setting_value FROM settings');
                foreach ($stmt->fetchAll() as $row) {
                    $settings[$row['setting_key']] = $row['setting_value'];
                }
            } catch (Throwable $e) {
                $settings = [];
            }
        }
        if (!$settings) {
            $settings = fallback_content()['settings'];
        }
    }
    return $settings[$key] ?? $default;
}

function fetch_all_active($table, $order = 'sort_order ASC, id DESC')
{
    if (db()->configured()) {
        try {
            $stmt = db()->pdo()->query("SELECT * FROM {$table} WHERE is_active = 1 ORDER BY {$order}");
            return $stmt->fetchAll();
        } catch (Throwable $e) {
        }
    }
    $fallback = fallback_content();
    return $fallback[$table] ?? [];
}

function fetch_one($table)
{
    $rows = fetch_all_active($table);
    return $rows[0] ?? null;
}

function fetch_module_items($module, $config)
{
    if (!isset($config[$module])) {
        return [];
    }
    $table = $config[$module]['table'];
    $order = $config[$module]['default_order'] ?? 'id DESC';
    if (!db()->configured()) {
        return fallback_content()[$table] ?? [];
    }
    $stmt = db()->pdo()->query("SELECT * FROM {$table} ORDER BY {$order}");
    return $stmt->fetchAll();
}

function fetch_module_item($module, $id, $config)
{
    if (!isset($config[$module]) || !db()->configured()) {
        return null;
    }
    $table = $config[$module]['table'];
    $stmt = db()->pdo()->prepare("SELECT * FROM {$table} WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch();
}

function sanitize_whatsapp($number)
{
    return preg_replace('/\D+/', '', (string) $number);
}

function current_path()
{
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $script = dirname($_SERVER['SCRIPT_NAME'] ?? '') ?: '';
    if ($script !== '/' && $script !== '.') {
        $uri = preg_replace('#^' . preg_quote($script, '#') . '#', '', $uri) ?: '/';
    }
    return '/' . ltrim($uri, '/');
}

function render($view, $data = [], $layout = 'site')
{
    extract($data);
    $viewFile = __DIR__ . '/../views/' . $view . '.php';
    $layoutFile = __DIR__ . '/../views/layouts/' . $layout . '.php';
    ob_start();
    require $viewFile;
    $content = ob_get_clean();
    require $layoutFile;
}

function table_columns($table)
{
    if (!db()->configured()) {
        return [];
    }
    static $cache = [];
    if (isset($cache[$table])) {
        return $cache[$table];
    }
    $stmt = db()->pdo()->query("SHOW COLUMNS FROM {$table}");
    $cache[$table] = array_column($stmt->fetchAll(), 'Field');
    return $cache[$table];
}

function save_settings(array $allowed)
{
    if (!db()->configured()) {
        return false;
    }
    $pdo = db()->pdo();
    $stmt = $pdo->prepare('REPLACE INTO settings (setting_key, setting_value, updated_at) VALUES (:setting_key, :setting_value, NOW())');
    foreach ($allowed as $key) {
        $stmt->execute([
            'setting_key' => $key,
            'setting_value' => trim($_POST[$key] ?? ''),
        ]);
    }
    return true;
}

function handle_upload($field, $dir)
{
    if (empty($_FILES[$field]['name'])) {
        return null;
    }
    $targetDir = __DIR__ . '/../../uploads/' . trim($dir, '/');
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0775, true);
    }
    $ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
    $safeExt = in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'], true) ? $ext : 'png';
    $name = date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $safeExt;
    $target = $targetDir . '/' . $name;
    if (move_uploaded_file($_FILES[$field]['tmp_name'], $target)) {
        return 'uploads/' . trim($dir, '/') . '/' . $name;
    }
    return null;
}

function slugify($text)
{
    if (function_exists('iconv')) {
        $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
    }
    $text = strtolower((string) $text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-');
}

function save_module_item($module, $config, $id = null)
{
    if (!isset($config[$module]) || !db()->configured()) {
        return false;
    }

    $definition = $config[$module];
    $table = $definition['table'];
    $fields = $definition['fields'];
    $columns = table_columns($table);
    $data = [];

    foreach ($fields as $name => $field) {
        if (!in_array($name, $columns, true)) {
            continue;
        }

        if (($field['type'] ?? '') === 'checkbox') {
            $data[$name] = isset($_POST[$name]) ? 1 : 0;
            continue;
        }

        if (($field['type'] ?? '') === 'image') {
            $uploaded = handle_upload($name, $definition['upload_dir'] ?? 'uploads');
            if ($uploaded !== null) {
                $data[$name] = $uploaded;
            } elseif ($id) {
                $existing = fetch_module_item($module, $id, $config);
                $data[$name] = $existing[$name] ?? '';
            }
            continue;
        }

        $value = trim($_POST[$name] ?? '');
        if ($name === 'slug' && $value === '') {
            $source = $_POST['name'] ?? $_POST['title'] ?? '';
            $value = slugify($source);
        }
        $data[$name] = $value;
    }

    if ($id) {
        $sets = [];
        foreach (array_keys($data) as $field) {
            $sets[] = "{$field} = :{$field}";
        }
        if (in_array('updated_at', $columns, true)) {
            $sets[] = 'updated_at = NOW()';
        }
        $sql = "UPDATE {$table} SET " . implode(', ', $sets) . " WHERE id = :id";
        $stmt = db()->pdo()->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    if (in_array('created_at', $columns, true)) {
        $data['created_at'] = date('Y-m-d H:i:s');
    }
    if (in_array('updated_at', $columns, true)) {
        $data['updated_at'] = date('Y-m-d H:i:s');
    }

    $fieldList = implode(', ', array_keys($data));
    $paramList = ':' . implode(', :', array_keys($data));
    $stmt = db()->pdo()->prepare("INSERT INTO {$table} ({$fieldList}) VALUES ({$paramList})");
    return $stmt->execute($data);
}

function delete_module_item($module, $id, $config)
{
    if (!isset($config[$module]) || !db()->configured()) {
        return false;
    }
    $table = $config[$module]['table'];
    $stmt = db()->pdo()->prepare("DELETE FROM {$table} WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}

function toggle_module_item($module, $id, $config)
{
    if (!isset($config[$module]) || !db()->configured()) {
        return false;
    }
    $table = $config[$module]['table'];
    $stmt = db()->pdo()->prepare("UPDATE {$table} SET is_active = IF(is_active = 1, 0, 1), updated_at = NOW() WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}

function analytics_track($eventName, array $payload = [])
{
    if (!db()->configured()) {
        return false;
    }

    try {
        $stmt = db()->pdo()->prepare('
            INSERT INTO analytics_events (
                event_name, page_path, source_label, entity_type, entity_id, entity_label, cta_label, unit_label,
                metadata_json, ip_address, user_agent, referer_url, created_at
            ) VALUES (
                :event_name, :page_path, :source_label, :entity_type, :entity_id, :entity_label, :cta_label, :unit_label,
                :metadata_json, :ip_address, :user_agent, :referer_url, NOW()
            )
        ');

        return $stmt->execute([
            'event_name' => $eventName,
            'page_path' => $payload['page_path'] ?? current_path(),
            'source_label' => $payload['source_label'] ?? ($_SERVER['HTTP_REFERER'] ?? 'direct'),
            'entity_type' => $payload['entity_type'] ?? '',
            'entity_id' => $payload['entity_id'] ?? null,
            'entity_label' => $payload['entity_label'] ?? '',
            'cta_label' => $payload['cta_label'] ?? '',
            'unit_label' => $payload['unit_label'] ?? '',
            'metadata_json' => json_encode($payload, JSON_UNESCAPED_UNICODE),
            'ip_address' => substr($_SERVER['REMOTE_ADDR'] ?? '', 0, 45),
            'user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
            'referer_url' => substr($_SERVER['HTTP_REFERER'] ?? '', 0, 255),
        ]);
    } catch (Throwable $e) {
        return false;
    }
}

function analytics_summary()
{
    if (!db()->configured()) {
        return [
            'totals' => ['clicks' => 0, 'whatsapp' => 0, 'reservations' => 0, 'page_views' => 0],
            'rankings' => ['pages' => [], 'ctas' => [], 'entities' => [], 'sources' => []],
        ];
    }

    $pdo = db()->pdo();
    $totals = [
        'clicks' => (int) $pdo->query("SELECT COUNT(*) FROM analytics_events")->fetchColumn(),
        'whatsapp' => (int) $pdo->query("SELECT COUNT(*) FROM analytics_events WHERE event_name = 'whatsapp_click'")->fetchColumn(),
        'reservations' => (int) $pdo->query("SELECT COUNT(*) FROM analytics_events WHERE event_name = 'reservation_submit'")->fetchColumn(),
        'page_views' => (int) $pdo->query("SELECT COUNT(*) FROM analytics_events WHERE event_name = 'page_view'")->fetchColumn(),
    ];

    $rankings = [
        'pages' => $pdo->query("SELECT page_path AS label, COUNT(*) AS total FROM analytics_events GROUP BY page_path ORDER BY total DESC LIMIT 10")->fetchAll(),
        'ctas' => $pdo->query("SELECT cta_label AS label, COUNT(*) AS total FROM analytics_events WHERE cta_label <> '' GROUP BY cta_label ORDER BY total DESC LIMIT 10")->fetchAll(),
        'entities' => $pdo->query("SELECT CONCAT(entity_type, ': ', entity_label) AS label, COUNT(*) AS total FROM analytics_events WHERE entity_label <> '' GROUP BY entity_type, entity_label ORDER BY total DESC LIMIT 10")->fetchAll(),
        'sources' => $pdo->query("SELECT source_label AS label, COUNT(*) AS total FROM analytics_events WHERE source_label <> '' GROUP BY source_label ORDER BY total DESC LIMIT 10")->fetchAll(),
    ];

    return compact('totals', 'rankings');
}

function admin_user()
{
    start_session();
    return [
        'id' => $_SESSION['admin_id'] ?? null,
        'name' => $_SESSION['admin_name'] ?? 'Administrador',
        'email' => $_SESSION['admin_email'] ?? '',
    ];
}

function login_admin($email, $password)
{
    if (!db()->configured()) {
        return false;
    }
    $stmt = db()->pdo()->prepare('SELECT * FROM admins WHERE email = :email LIMIT 1');
    $stmt->execute(['email' => $email]);
    $admin = $stmt->fetch();
    if (!$admin || !password_verify($password, $admin['password_hash'])) {
        return false;
    }
    start_session();
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_name'] = $admin['name'];
    $_SESSION['admin_email'] = $admin['email'];
    return true;
}

function logout_admin()
{
    start_session();
    $_SESSION = [];
    session_destroy();
}

function format_date_br($value)
{
    if (!$value) {
        return '';
    }
    $time = strtotime($value);
    return $time ? date('d/m/Y', $time) : $value;
}

function reservation_rows()
{
    if (!db()->configured()) {
        return [];
    }
    $stmt = db()->pdo()->query('SELECT * FROM lead_reservations ORDER BY created_at DESC LIMIT 50');
    return $stmt->fetchAll();
}
