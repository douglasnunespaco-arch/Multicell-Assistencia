<?php
namespace App\Core;

/**
 * Auth — autenticação simples de admins via sessão.
 */
final class Auth
{
    public static function attempt(string $email, string $password): bool
    {
        $row = Database::fetch(
            'SELECT id, name, email, password_hash, role, is_active, must_change_password
             FROM admins WHERE email = :e LIMIT 1',
            ['e' => $email]
        );
        if (!$row || (int) $row['is_active'] !== 1) {
            return false;
        }
        if (!password_verify($password, $row['password_hash'])) {
            return false;
        }
        Session::regenerate();
        $_SESSION['admin'] = [
            'id'   => (int) $row['id'],
            'name' => $row['name'],
            'email' => $row['email'],
            'role' => $row['role'],
            'must_change_password' => (int) $row['must_change_password'] === 1,
        ];
        Database::update('admins', [
            'last_login_at' => date('Y-m-d H:i:s'),
        ], ['id' => $row['id']]);
        return true;
    }

    public static function check(): bool
    {
        return !empty($_SESSION['admin']['id']);
    }

    public static function user(): ?array
    {
        return $_SESSION['admin'] ?? null;
    }

    public static function logout(): void
    {
        unset($_SESSION['admin']);
        Session::regenerate();
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            header('Location: /admin/login');
            exit;
        }
    }
}
