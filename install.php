<?php
/**
 * Multi Cell Assistência Técnica — Instalador
 *
 * Fluxo:
 *  1. Checa se já está instalado (config.php + storage/install.lock). Se sim, bloqueia.
 *  2. Exibe formulário único: credenciais MySQL, URL pública, admin inicial.
 *  3. Ao submeter:
 *     - Testa conexão PDO.
 *     - Importa database/schema.sql.
 *     - Importa database/seed.sql (pulando a linha do admin placeholder).
 *     - Cria admin real com password_hash().
 *     - Escreve config/config.php (permissões 0600) e storage/install.lock.
 *  4. Mostra tela de sucesso com instruções de segurança (remover/proteger install.php).
 *
 * Compatível com Hostinger/shared hosting (PHP 8 + PDO MySQL/MariaDB).
 * Sem dependências externas. Não imprime credenciais após instalação.
 */
declare(strict_types=1);

define('INSTALLER_ROOT', __DIR__);
define('INSTALLER_CONFIG', INSTALLER_ROOT . '/config/config.php');
define('INSTALLER_LOCK',   INSTALLER_ROOT . '/storage/install.lock');
define('INSTALLER_SCHEMA', INSTALLER_ROOT . '/database/schema.sql');
define('INSTALLER_SEED',   INSTALLER_ROOT . '/database/seed.sql');

// -------------------------------------------------------------------
// GUARDA: já instalado → bloqueia
// -------------------------------------------------------------------
if (is_file(INSTALLER_CONFIG) && is_file(INSTALLER_LOCK)) {
    render_already_installed();
    exit;
}

// -------------------------------------------------------------------
// PRE-REQUISITOS
// -------------------------------------------------------------------
$checks = run_preflight();

// -------------------------------------------------------------------
// PROCESSAMENTO DO FORM
// -------------------------------------------------------------------
$errors = [];
$notice = null;
$form   = default_form();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = array_merge($form, array_map(fn($v) => is_string($v) ? trim($v) : $v, $_POST));
    $errors = validate_form($form);

    if (empty($errors) && !$checks['all_ok']) {
        $errors['_system'] = 'Corrija os pré-requisitos acima antes de instalar.';
    }

    if (empty($errors)) {
        try {
            $pdo = connect_pdo($form);
            import_sql($pdo, INSTALLER_SCHEMA);
            import_sql($pdo, INSTALLER_SEED, skip_admin_insert: true);
            create_admin($pdo, $form);
            write_config($form);
            write_lock();
            render_success($form);
            exit;
        } catch (\Throwable $e) {
            $errors['_system'] = 'Falha na instalação: ' . $e->getMessage();
        }
    }
}

render_form($form, $errors, $checks);
exit;

// ===================================================================
// FUNÇÕES
// ===================================================================

function default_form(): array {
    return [
        'db_host'    => 'localhost',
        'db_port'    => '3306',
        'db_name'    => 'multicell',
        'db_user'    => '',
        'db_pass'    => '',
        'app_url'    => detect_base_url(),
        'app_env'    => 'production',
        'admin_name' => 'Administrador',
        'admin_email'=> '',
        'admin_pass' => '',
        'admin_pass2'=> '',
    ];
}

function detect_base_url(): string {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
    return $scheme . '://' . $host;
}

function run_preflight(): array {
    $checks = [];
    $checks['php'] = [
        'label' => 'PHP 8.0+',
        'ok'    => version_compare(PHP_VERSION, '8.0.0', '>='),
        'hint'  => 'Versão atual: ' . PHP_VERSION,
    ];
    $checks['pdo_mysql'] = [
        'label' => 'Extensão PDO MySQL',
        'ok'    => extension_loaded('pdo_mysql'),
        'hint'  => 'Habilite `pdo_mysql` no PHP da hospedagem.',
    ];
    $checks['mbstring'] = [
        'label' => 'Extensão mbstring',
        'ok'    => extension_loaded('mbstring'),
        'hint'  => 'Habilite `mbstring` no PHP da hospedagem.',
    ];
    $checks['config_dir'] = [
        'label' => 'Pasta config/ gravável',
        'ok'    => is_writable(INSTALLER_ROOT . '/config'),
        'hint'  => 'Ajuste permissão para 755 (ou 775) na pasta config/.',
    ];
    $checks['storage_dir'] = [
        'label' => 'Pasta storage/ gravável',
        'ok'    => is_writable(INSTALLER_ROOT . '/storage'),
        'hint'  => 'Ajuste permissão para 755 (ou 775) na pasta storage/.',
    ];
    $checks['schema_file'] = [
        'label' => 'database/schema.sql presente',
        'ok'    => is_file(INSTALLER_SCHEMA),
        'hint'  => 'Arquivo ausente no deploy.',
    ];
    $checks['seed_file'] = [
        'label' => 'database/seed.sql presente',
        'ok'    => is_file(INSTALLER_SEED),
        'hint'  => 'Arquivo ausente no deploy.',
    ];
    $checks['all_ok'] = !in_array(false, array_map(fn($c) => $c['ok'], $checks), true);
    return $checks;
}

function validate_form(array $f): array {
    $e = [];
    foreach (['db_host','db_name','db_user','app_url','admin_name','admin_email','admin_pass'] as $k) {
        if (($f[$k] ?? '') === '') $e[$k] = 'Obrigatório.';
    }
    if (!empty($f['admin_email']) && !filter_var($f['admin_email'], FILTER_VALIDATE_EMAIL)) {
        $e['admin_email'] = 'E-mail inválido.';
    }
    if (!empty($f['admin_pass']) && mb_strlen($f['admin_pass']) < 8) {
        $e['admin_pass'] = 'Use 8+ caracteres.';
    }
    if (($f['admin_pass'] ?? '') !== ($f['admin_pass2'] ?? '')) {
        $e['admin_pass2'] = 'As senhas não conferem.';
    }
    if (!empty($f['app_url']) && !preg_match('#^https?://#i', $f['app_url'])) {
        $e['app_url'] = 'Use http:// ou https://';
    }
    if (!in_array($f['app_env'] ?? '', ['production','development'], true)) {
        $e['app_env'] = 'Inválido.';
    }
    return $e;
}

function connect_pdo(array $f): \PDO {
    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
        $f['db_host'], $f['db_port'] ?: '3306', $f['db_name']
    );
    $opts = [
        \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
    ];
    return new \PDO($dsn, $f['db_user'], $f['db_pass'], $opts);
}

/**
 * Divide um arquivo .sql em statements respeitando strings e comentários.
 */
function import_sql(\PDO $pdo, string $file, bool $skip_admin_insert = false): void {
    $sql = (string) file_get_contents($file);
    // Remove comentários de linha (-- ...) e multi-linha (/* ... */) simples
    $sql = preg_replace('#/\*.*?\*/#s', '', $sql) ?? $sql;
    $sql = preg_replace('#^\s*--[^\n]*\n#m', '', $sql) ?? $sql;

    foreach (split_sql($sql) as $stmt) {
        $stmt = trim($stmt);
        if ($stmt === '') continue;
        if ($skip_admin_insert && preg_match('/INSERT\s+IGNORE\s+INTO\s+`admins`/i', $stmt)) {
            continue; // admin real é criado por create_admin()
        }
        $pdo->exec($stmt);
    }
}

function split_sql(string $sql): array {
    $parts = [];
    $buf = '';
    $in = false;
    $q  = '';
    $len = strlen($sql);
    for ($i = 0; $i < $len; $i++) {
        $c = $sql[$i];
        if ($in) {
            $buf .= $c;
            if ($c === $q && ($i === 0 || $sql[$i-1] !== '\\')) {
                $in = false;
            }
            continue;
        }
        if ($c === "'" || $c === '"' || $c === '`') {
            $in = true; $q = $c; $buf .= $c; continue;
        }
        if ($c === ';') {
            $parts[] = $buf; $buf = ''; continue;
        }
        $buf .= $c;
    }
    if (trim($buf) !== '') $parts[] = $buf;
    return $parts;
}

function create_admin(\PDO $pdo, array $f): void {
    $hash = password_hash($f['admin_pass'], PASSWORD_BCRYPT, ['cost' => 12]);
    // Evita duplicar se rodar novamente por erro
    $pdo->prepare('DELETE FROM `admins` WHERE `email` = :e')->execute([':e' => $f['admin_email']]);
    $pdo->prepare(
        'INSERT INTO `admins` (`name`,`email`,`password_hash`,`role`,`is_active`,`must_change_password`)
         VALUES (:n, :e, :h, "admin", 1, 0)'
    )->execute([
        ':n' => $f['admin_name'],
        ':e' => $f['admin_email'],
        ':h' => $hash,
    ]);
}

function write_config(array $f): void {
    $key = bin2hex(random_bytes(24)); // 48 chars
    $lines = [
        '<?php',
        '/**',
        ' * Multi Cell — config gerada pelo instalador em ' . date('Y-m-d H:i:s') . '.',
        ' * NÃO COMMITAR este arquivo. Regenere via install.php em caso de perda.',
        ' */',
        "define('APP_ENV',      " . var_export($f['app_env'], true) . ");",
        "define('APP_URL',      " . var_export(rtrim($f['app_url'], '/'), true) . ");",
        "define('APP_NAME',     'Multi Cell Assistência Técnica');",
        "define('APP_TIMEZONE', 'America/Cuiaba');",
        "define('APP_KEY',      " . var_export($key, true) . ");",
        "define('DB_HOST',      " . var_export($f['db_host'], true) . ");",
        "define('DB_PORT',      " . var_export((string) ($f['db_port'] ?: '3306'), true) . ");",
        "define('DB_NAME',      " . var_export($f['db_name'], true) . ");",
        "define('DB_USER',      " . var_export($f['db_user'], true) . ");",
        "define('DB_PASS',      " . var_export($f['db_pass'], true) . ");",
        "define('DB_CHARSET',   'utf8mb4');",
        "define('UPLOAD_MAX_MB', 5);",
        '',
    ];
    file_put_contents(INSTALLER_CONFIG, implode("\n", $lines));
    @chmod(INSTALLER_CONFIG, 0600);
}

function write_lock(): void {
    file_put_contents(INSTALLER_LOCK, date('c') . "\n");
    @chmod(INSTALLER_LOCK, 0600);
}

// -------------------------------------------------------------------
// TEMPLATES (minimalistas, sem dependência do front público)
// -------------------------------------------------------------------
function layout_begin(string $title): void {
    ?><!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title><?= htmlspecialchars($title) ?> · Multi Cell Installer</title>
<style>
    :root { --brand:#14F195; --bg:#0A0A0B; --bg1:#111214; --fg:#e7e9ee; --fg2:#9aa0a6; --border:#22252b; --danger:#ff4d6d; }
    * { box-sizing:border-box; }
    body { margin:0; background:var(--bg); color:var(--fg); font:15px/1.55 system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif; }
    .wrap { max-width:760px; margin:40px auto; padding:0 20px; }
    h1 { font-size:26px; margin:0 0 6px; }
    h1 small { display:block; color:var(--fg2); font-size:13px; font-weight:400; margin-top:8px; }
    h2 { font-size:16px; margin:28px 0 10px; color:var(--brand); text-transform:uppercase; letter-spacing:.08em; }
    .card { background:var(--bg1); border:1px solid var(--border); border-radius:12px; padding:24px; margin:16px 0; }
    .grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
    .grid .full { grid-column:1/-1; }
    @media (max-width:640px) { .grid { grid-template-columns:1fr; } }
    label { display:block; font-size:13px; color:var(--fg2); margin-bottom:4px; }
    input, select { width:100%; background:#0e0f11; border:1px solid var(--border); color:var(--fg); padding:10px 12px; border-radius:8px; font:inherit; }
    input:focus, select:focus { outline:none; border-color:var(--brand); }
    .btn { display:inline-flex; align-items:center; gap:8px; background:var(--brand); color:#06110a; border:0; padding:12px 20px; border-radius:999px; font-weight:700; cursor:pointer; text-decoration:none; }
    .btn:hover { filter:brightness(1.08); }
    .btn--ghost { background:transparent; color:var(--fg); border:1px solid var(--border); }
    .check { display:flex; justify-content:space-between; gap:12px; padding:10px 0; border-bottom:1px dashed var(--border); }
    .check:last-child { border-bottom:0; }
    .ok { color:var(--brand); font-weight:700; }
    .fail { color:var(--danger); font-weight:700; }
    .hint { color:var(--fg2); font-size:12px; }
    .alert { padding:12px 14px; border-radius:8px; margin:10px 0; font-size:14px; }
    .alert--danger { background:rgba(255,77,109,.1); border:1px solid rgba(255,77,109,.35); color:#ffb3c1; }
    .alert--success { background:rgba(20,241,149,.08); border:1px solid rgba(20,241,149,.35); color:#b5f7d3; }
    .alert--warning { background:rgba(255,186,73,.08); border:1px solid rgba(255,186,73,.35); color:#ffd89b; }
    .field-error { color:var(--danger); font-size:12px; margin-top:4px; }
    code { background:#0e0f11; padding:2px 6px; border-radius:4px; font-size:13px; }
    ul.steps { padding-left:22px; }
    ul.steps li { margin:8px 0; }
</style>
</head>
<body>
<div class="wrap">
<?php
}

function layout_end(): void {
    ?></div></body></html><?php
}

function render_form(array $form, array $errors, array $checks): void {
    layout_begin('Instalação');
    ?>
    <h1>Multi Cell · Instalador
        <small>Preencha os dados abaixo para gerar <code>config/config.php</code>, importar o schema/seed e criar o administrador.</small>
    </h1>

    <?php if (!empty($errors['_system'])): ?>
        <div class="alert alert--danger"><?= htmlspecialchars($errors['_system']) ?></div>
    <?php endif; ?>

    <div class="card">
        <h2>Pré-requisitos</h2>
        <?php foreach ($checks as $k => $c): if ($k === 'all_ok') continue; ?>
            <div class="check">
                <span><?= htmlspecialchars($c['label']) ?></span>
                <span class="<?= $c['ok'] ? 'ok' : 'fail' ?>"><?= $c['ok'] ? 'OK' : 'Falha' ?></span>
            </div>
            <?php if (!$c['ok']): ?>
                <p class="hint"><?= htmlspecialchars($c['hint']) ?></p>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <form method="post" class="card">
        <h2>Banco de dados (MySQL/MariaDB)</h2>
        <div class="grid">
            <div>
                <label>Host</label>
                <input name="db_host" value="<?= htmlspecialchars($form['db_host']) ?>" required>
                <?php fe($errors, 'db_host') ?>
            </div>
            <div>
                <label>Porta</label>
                <input name="db_port" value="<?= htmlspecialchars($form['db_port']) ?>">
            </div>
            <div>
                <label>Database</label>
                <input name="db_name" value="<?= htmlspecialchars($form['db_name']) ?>" required>
                <?php fe($errors, 'db_name') ?>
            </div>
            <div>
                <label>Usuário</label>
                <input name="db_user" value="<?= htmlspecialchars($form['db_user']) ?>" required autocomplete="off">
                <?php fe($errors, 'db_user') ?>
            </div>
            <div class="full">
                <label>Senha</label>
                <input name="db_pass" type="password" value="<?= htmlspecialchars($form['db_pass']) ?>" autocomplete="new-password">
            </div>
        </div>

        <h2>Aplicação</h2>
        <div class="grid">
            <div class="full">
                <label>URL pública</label>
                <input name="app_url" value="<?= htmlspecialchars($form['app_url']) ?>" required>
                <?php fe($errors, 'app_url') ?>
            </div>
            <div class="full">
                <label>Ambiente</label>
                <select name="app_env">
                    <option value="production" <?= $form['app_env']==='production'?'selected':'' ?>>production (recomendado)</option>
                    <option value="development" <?= $form['app_env']==='development'?'selected':'' ?>>development</option>
                </select>
            </div>
        </div>

        <h2>Administrador inicial</h2>
        <div class="grid">
            <div>
                <label>Nome</label>
                <input name="admin_name" value="<?= htmlspecialchars($form['admin_name']) ?>" required>
                <?php fe($errors, 'admin_name') ?>
            </div>
            <div>
                <label>E-mail de login</label>
                <input name="admin_email" type="email" value="<?= htmlspecialchars($form['admin_email']) ?>" required autocomplete="off">
                <?php fe($errors, 'admin_email') ?>
            </div>
            <div>
                <label>Senha (8+ caracteres)</label>
                <input name="admin_pass" type="password" required autocomplete="new-password">
                <?php fe($errors, 'admin_pass') ?>
            </div>
            <div>
                <label>Confirmar senha</label>
                <input name="admin_pass2" type="password" required autocomplete="new-password">
                <?php fe($errors, 'admin_pass2') ?>
            </div>
        </div>

        <div style="margin-top:22px;display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
            <button class="btn" type="submit">Instalar sistema</button>
            <small class="hint">O instalador é de uso único. Após concluir, remova <code>install.php</code> ou proteja via .htaccess.</small>
        </div>
    </form>

    <?php
    layout_end();
}

function fe(array $errors, string $k): void {
    if (!empty($errors[$k])) {
        echo '<div class="field-error">' . htmlspecialchars($errors[$k]) . '</div>';
    }
}

function render_success(array $form): void {
    layout_begin('Instalação concluída');
    ?>
    <h1>Instalação concluída ✓
        <small>O sistema está pronto. Siga as ações abaixo para deixar o deploy seguro.</small>
    </h1>
    <div class="alert alert--success">
        <strong>Admin criado:</strong> <?= htmlspecialchars($form['admin_email']) ?>
    </div>
    <div class="card">
        <h2>Ações de segurança obrigatórias</h2>
        <ul class="steps">
            <li>🔒 <strong>Remova ou proteja</strong> o arquivo <code>install.php</code> (via FTP, cPanel ou regra <code>.htaccess</code>).</li>
            <li>🔒 Confirme que a pasta <code>config/</code> não é pública — ela já tem <code>.htaccess</code> de proteção, mas revise no painel.</li>
            <li>🔒 Arquivos sensíveis criados com permissão <code>0600</code>: <code>config/config.php</code> e <code>storage/install.lock</code>.</li>
            <li>🔑 Guarde suas credenciais de admin em local seguro. A senha não é recuperável automaticamente.</li>
        </ul>
    </div>
    <div class="card">
        <h2>Próximo passo</h2>
        <p>Abra o site para validar o front público:</p>
        <p><a class="btn" href="/">Ir para a home →</a></p>
        <p class="hint" style="margin-top:16px;">O painel <code>/admin</code> será habilitado na próxima fase (Fase 4).</p>
    </div>
    <?php
    layout_end();
}

function render_already_installed(): void {
    layout_begin('Já instalado');
    ?>
    <h1>Sistema já instalado
        <small>O arquivo <code>storage/install.lock</code> existe. O instalador está desativado por segurança.</small>
    </h1>
    <div class="alert alert--warning">
        Para reinstalar, remova manualmente <code>config/config.php</code> e <code>storage/install.lock</code> (e, em seguida, apague tudo do banco se quiser começar do zero).
    </div>
    <div class="card">
        <p><a class="btn" href="/">Ir para o site</a></p>
    </div>
    <?php
    layout_end();
}
