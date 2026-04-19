<?php
/**
 * Multi Cell Assistência Técnica — Instalador
 *
 * Segurança:
 *  - Se config/config.php e storage/install.lock existirem, o instalador
 *    se recusa a executar e recomenda remoção imediata deste arquivo.
 *  - Admin seed é criado com password_hash() (bcrypt) gerado em tempo real.
 *  - Exige marcação "must_change_password" para forçar troca no 1º login.
 *  - Credenciais do banco nunca são hardcoded; são informadas pelo usuário.
 *
 * Uso:
 *  1) Acesse https://seu-dominio/install.php
 *  2) Preencha credenciais do banco e admin
 *  3) Ao final, remova /install.php (o arquivo também recomenda isso).
 */

declare(strict_types=1);

define('INSTALL_ROOT', __DIR__);
define('CONFIG_DIR',  INSTALL_ROOT . '/config');
define('STORAGE_DIR', INSTALL_ROOT . '/storage');
define('DB_DIR',      INSTALL_ROOT . '/database');
define('LOCK_FILE',   STORAGE_DIR . '/install.lock');
define('CONFIG_FILE', CONFIG_DIR  . '/config.php');

@mkdir(STORAGE_DIR . '/logs', 0755, true);

// ---------- Bloqueio após instalado ----------
if (is_file(CONFIG_FILE) && is_file(LOCK_FILE)) {
    http_response_code(423);
    render_shell('Instalação já concluída', '
        <div class="alert alert--warning">
            A instalação já foi realizada e está travada por segurança.<br>
            <strong>Remova este arquivo (install.php)</strong> do servidor imediatamente.
        </div>
        <p style="font-size:14px;color:var(--fg-1)">
            Caso precise reinstalar, apague manualmente <code>config/config.php</code> e
            <code>storage/install.lock</code> via FTP/File Manager.
        </p>
        <a class="btn btn--primary submit" href="/">Ir para o site</a>
    ');
    exit;
}

// ---------- Pré-requisitos ----------
$errors = [];
if (PHP_VERSION_ID < 80000) {
    $errors[] = 'PHP 8.0 ou superior é obrigatório. Versão atual: ' . PHP_VERSION;
}
if (!extension_loaded('pdo_mysql')) {
    $errors[] = 'Extensão PHP pdo_mysql não encontrada.';
}
if (!extension_loaded('mbstring')) {
    $errors[] = 'Extensão PHP mbstring não encontrada.';
}
if (!is_writable(CONFIG_DIR)) {
    $errors[] = 'A pasta <code>/config</code> precisa ter permissão de escrita.';
}
if (!is_writable(STORAGE_DIR)) {
    $errors[] = 'A pasta <code>/storage</code> precisa ter permissão de escrita.';
}

$step = $_POST['step'] ?? 'form';
$feedback = '';
$input = [
    'db_host'        => $_POST['db_host']        ?? 'localhost',
    'db_port'        => $_POST['db_port']        ?? '3306',
    'db_name'        => $_POST['db_name']        ?? '',
    'db_user'        => $_POST['db_user']        ?? '',
    'db_pass'        => $_POST['db_pass']        ?? '',
    'app_url'        => $_POST['app_url']        ?? (($_SERVER['HTTPS'] ?? '') ? 'https://' : 'http://') . ($_SERVER['HTTP_HOST'] ?? 'localhost'),
    'admin_name'     => $_POST['admin_name']     ?? 'Administrador',
    'admin_email'    => $_POST['admin_email']    ?? 'admin@multicell.local',
    'admin_password' => $_POST['admin_password'] ?? '',
];

// ---------- Processamento ----------
if ($step === 'install' && empty($errors)) {
    try {
        // Validação básica
        foreach (['db_name','db_user','admin_email','admin_password','app_url'] as $k) {
            if ($input[$k] === '') {
                throw new RuntimeException("Campo obrigatório: $k");
            }
        }
        if (!filter_var($input['admin_email'], FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('E-mail do admin é inválido.');
        }
        if (strlen($input['admin_password']) < 8) {
            throw new RuntimeException('A senha do admin deve ter pelo menos 8 caracteres.');
        }

        // Conexão
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            $input['db_host'], $input['db_port'], $input['db_name']
        );
        $pdo = new PDO($dsn, $input['db_user'], $input['db_pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
        ]);

        // Executa schema.sql
        run_sql_file($pdo, DB_DIR . '/schema.sql');

        // Aplica seed (settings, unidade, slides, produtos, promoções, etc.)
        run_sql_file($pdo, DB_DIR . '/seed.sql');

        // Substitui/insere admin real com senha hasheada em tempo real
        $hash = password_hash($input['admin_password'], PASSWORD_BCRYPT, ['cost' => 12]);
        $pdo->prepare('DELETE FROM admins WHERE email = :e')
            ->execute(['e' => $input['admin_email']]);
        $pdo->prepare('INSERT INTO admins (name, email, password_hash, role, is_active, must_change_password)
                       VALUES (:n, :e, :p, "admin", 1, 1)')
            ->execute([
                'n' => $input['admin_name'],
                'e' => $input['admin_email'],
                'p' => $hash,
            ]);

        // Gera config.php real
        $appKey = bin2hex(random_bytes(24));
        $cfgTpl = <<<'PHP'
<?php
// Gerado por install.php em __GENDATE__
// NÃO commitar este arquivo. Contém credenciais do banco.
define('APP_ENV',      'production');
define('APP_URL',      %s);
define('APP_NAME',     'Multi Cell Assistência Técnica');
define('APP_TIMEZONE', 'America/Cuiaba');
define('APP_KEY',      %s);

define('DB_HOST',      %s);
define('DB_PORT',      %s);
define('DB_NAME',      %s);
define('DB_USER',      %s);
define('DB_PASS',      %s);
define('DB_CHARSET',   'utf8mb4');

define('UPLOAD_MAX_MB', 5);
PHP;
        $cfgTpl = str_replace('__GENDATE__', date('Y-m-d H:i:s'), $cfgTpl);
        $cfg = sprintf(
            $cfgTpl,
            var_export(rtrim($input['app_url'], '/'), true),
            var_export($appKey, true),
            var_export($input['db_host'], true),
            var_export($input['db_port'], true),
            var_export($input['db_name'], true),
            var_export($input['db_user'], true),
            var_export($input['db_pass'], true),
        );

        if (file_put_contents(CONFIG_FILE, $cfg) === false) {
            throw new RuntimeException('Não foi possível gravar config/config.php.');
        }
        @chmod(CONFIG_FILE, 0640);

        // Cria lock de instalação
        $lockContent = json_encode([
            'installed_at' => date('c'),
            'installed_by' => $input['admin_email'],
            'php_version'  => PHP_VERSION,
            'app_key_fp'   => substr(hash('sha256', $appKey), 0, 12),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        file_put_contents(LOCK_FILE, $lockContent);
        @chmod(LOCK_FILE, 0640);

        // Pastas de upload
        foreach (['hero','products','promotions','services','about','testimonials'] as $dir) {
            @mkdir(INSTALL_ROOT . '/uploads/' . $dir, 0755, true);
        }

        // Tenta se auto-neutralizar (renomear para install.php.done)
        $selfDisabled = @rename(__FILE__, __FILE__ . '.done');

        render_shell('Instalação concluída', '
            <div class="alert alert--success">
                Instalação concluída com sucesso.
            </div>
            <p style="font-size:14px;color:var(--fg-1)">
                Acesse o painel admin em <code>/admin/login</code> com:<br>
                <strong>E-mail:</strong> ' . htmlspecialchars($input['admin_email']) . '<br>
                <strong>Senha:</strong> (a que você cadastrou)<br>
                A troca de senha será solicitada no primeiro acesso.
            </p>
            <ol class="install-steps">
                <li>' . ($selfDisabled
                    ? 'O instalador foi renomeado para <code>install.php.done</code>. Remova-o via FTP.'
                    : '<strong>Remova agora o arquivo <code>install.php</code></strong> do servidor.') . '</li>
                <li>Confira o arquivo <code>config/config.php</code> (permissão 640).</li>
                <li>Verifique se <code>storage/install.lock</code> foi criado.</li>
            </ol>
            <a class="btn btn--primary submit" href="/admin/login">Ir para o Admin</a>
        ');
        exit;

    } catch (Throwable $e) {
        $feedback = '<div class="alert alert--error">Erro: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}

// ---------- Tela de formulário ----------
$prereqHtml = '';
if (!empty($errors)) {
    $prereqHtml = '<div class="alert alert--error"><strong>Requisitos não atendidos:</strong><ul style="margin:8px 0 0 18px;">';
    foreach ($errors as $err) $prereqHtml .= '<li>' . $err . '</li>';
    $prereqHtml .= '</ul></div>';
}

$form = <<<HTML
<span class="eyebrow">INSTALADOR</span>
<h1>Multi Cell Assistência Técnica</h1>
<p style="color:var(--fg-1); margin-top:-4px;">Configure o banco e o administrador inicial.</p>
{$prereqHtml}
{$feedback}
<form method="post" autocomplete="off">
    <input type="hidden" name="step" value="install">

    <h3 style="margin-top:24px;">Banco de dados</h3>
    <div class="row2">
        <div class="field">
            <label>Host</label>
            <input name="db_host" value="{$input['db_host']}" required>
        </div>
        <div class="field">
            <label>Porta</label>
            <input name="db_port" value="{$input['db_port']}" required>
        </div>
    </div>
    <div class="field">
        <label>Nome do banco</label>
        <input name="db_name" value="{$input['db_name']}" required>
    </div>
    <div class="row2">
        <div class="field">
            <label>Usuário</label>
            <input name="db_user" value="{$input['db_user']}" required>
        </div>
        <div class="field">
            <label>Senha</label>
            <input name="db_pass" type="password" value="">
        </div>
    </div>

    <h3 style="margin-top:24px;">Site</h3>
    <div class="field">
        <label>URL pública (sem barra final)</label>
        <input name="app_url" value="{$input['app_url']}" required>
    </div>

    <h3 style="margin-top:24px;">Administrador</h3>
    <div class="field">
        <label>Nome</label>
        <input name="admin_name" value="{$input['admin_name']}" required>
    </div>
    <div class="field">
        <label>E-mail</label>
        <input name="admin_email" type="email" value="{$input['admin_email']}" required>
    </div>
    <div class="field">
        <label>Senha (mín. 8 caracteres)</label>
        <input name="admin_password" type="password" required>
    </div>

    <button type="submit" class="btn btn--primary submit">Instalar agora</button>
</form>
HTML;

render_shell('Instalador', $form);
exit;


// ====================== FUNÇÕES AUXILIARES ======================

function render_shell(string $title, string $body): void {
    echo '<!doctype html><html lang="pt-BR" data-theme="dark"><head>';
    echo '<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">';
    echo '<title>' . htmlspecialchars($title) . ' • Multi Cell</title>';
    echo '<link rel="stylesheet" href="/assets/css/public.css">';
    echo '</head><body><main class="install-shell"><div class="install-card">';
    echo $body;
    echo '</div></main></body></html>';
}

function run_sql_file(PDO $pdo, string $path): void {
    if (!is_file($path)) {
        throw new RuntimeException("Arquivo SQL não encontrado: $path");
    }
    $sql = (string) file_get_contents($path);
    // Remove comentários de linha e blocos simples
    $sql = preg_replace('!/\*.*?\*/!s', '', $sql) ?? $sql;
    $sql = preg_replace('/^\s*--.*$/m', '', $sql) ?? $sql;

    // Divide respeitando a sintaxe (delimiter padrão ; — nossos arquivos não usam DELIMITER custom)
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    foreach ($statements as $stmt) {
        if ($stmt === '') continue;
        $pdo->exec($stmt);
    }
}
