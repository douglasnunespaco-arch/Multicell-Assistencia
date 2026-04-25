<?php
namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Flash;
use App\Core\Session;
use App\Core\Validator;

/**
 * AuthController (admin) — login, logout.
 *
 * Proteção contra força-bruta: janela móvel em sessão.
 * 5 tentativas falhas = bloqueio de 15 minutos para essa sessão.
 * (Ambiente shared hosting sem Redis; solução proporcional.)
 */
final class AuthController
{
    private const MAX_ATTEMPTS  = 5;
    private const LOCK_SECONDS  = 900; // 15 min

    public function showLogin(): string
    {
        if (Auth::check()) {
            header('Location: /admin');
            exit;
        }
        return View::render('admin/login', [
            'page_title' => 'Entrar • Admin Multi Cell',
            'errors'     => $_SESSION['_admin_errors'] ?? [],
            'old'        => $_SESSION['_admin_old']    ?? [],
            'locked_until' => $_SESSION['_login_lock_until'] ?? null,
        ], 'admin-bare');
        // 'admin-bare' = layout sem sidebar (login/logout)
    }

    public function login(): string
    {
        Csrf::verifyOrFail();
        unset($_SESSION['_admin_errors'], $_SESSION['_admin_old']);

        // 1. Verifica lock
        $lockUntil = $_SESSION['_login_lock_until'] ?? 0;
        if ($lockUntil && time() < $lockUntil) {
            $remaining = $lockUntil - time();
            Flash::error("Muitas tentativas. Aguarde " . ceil($remaining / 60) . " min e tente novamente.");
            header('Location: /admin/login');
            exit;
        }

        // 2. Valida inputs
        $v = Validator::make($_POST)
            ->required('email', 'E-mail')->email('email', 'E-mail')->max('email', 160, 'E-mail')
            ->required('password', 'Senha')->max('password', 200, 'Senha');

        if (!$v->passes()) {
            $_SESSION['_admin_errors'] = $v->errors();
            $_SESSION['_admin_old']    = ['email' => $_POST['email'] ?? ''];
            Flash::error('Informe e-mail e senha.');
            header('Location: /admin/login');
            exit;
        }

        $email    = trim((string) $_POST['email']);
        $password = (string) $_POST['password'];

        // 3. Tenta autenticar
        if (Auth::attempt($email, $password)) {
            unset($_SESSION['_login_attempts'], $_SESSION['_login_lock_until']);
            $_SESSION['_welcome_show'] = 1; // flag consumida no Dashboard para disparar a animação
            // Carrega preferência de tema persistida no servidor (k/v em settings)
            $u = \App\Core\Auth::user();
            if (!empty($u['id'])) {
                $_SESSION['theme_pref'] = \App\Models\AdminPref::getTheme((int) $u['id'], 'auto');
            }
            Flash::success('Bem-vindo(a) de volta.');
            header('Location: /admin');
            exit;
        }

        // 4. Falhou: incrementa tentativas
        $attempts = (int) ($_SESSION['_login_attempts'] ?? 0) + 1;
        $_SESSION['_login_attempts'] = $attempts;

        if ($attempts >= self::MAX_ATTEMPTS) {
            $_SESSION['_login_lock_until'] = time() + self::LOCK_SECONDS;
            Flash::error('Limite de tentativas atingido. Aguarde 15 min.');
        } else {
            $left = self::MAX_ATTEMPTS - $attempts;
            Flash::error("E-mail ou senha inválidos. Tentativas restantes: {$left}.");
        }
        $_SESSION['_admin_old'] = ['email' => $email];
        header('Location: /admin/login');
        exit;
    }

    public function logout(): string
    {
        Csrf::verifyOrFail();
        Auth::logout();
        Flash::info('Sessão encerrada.');
        header('Location: /admin/login');
        exit;
    }
}
