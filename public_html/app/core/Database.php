<?php

class Database
{
    private static $instance = null;
    private $pdo = null;
    private $configured = false;

    private function __construct(array $config)
    {
        $db = $config['db'] ?? [];
        $user = $db['user'] ?? '';
        $pass = $db['pass'] ?? '';
        $name = $db['name'] ?? '';

        if (!$name || $user === 'CHANGE_DB_USER' || $pass === 'CHANGE_DB_PASSWORD') {
            return;
        }

        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $db['host'] ?? 'localhost',
            $db['port'] ?? 3306,
            $name,
            $db['charset'] ?? 'utf8mb4'
        );

        try {
            $this->pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            $this->configured = true;
        } catch (Throwable $e) {
            $this->configured = false;
        }
    }

    public static function instance(array $config)
    {
        if (self::$instance === null) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    public function configured()
    {
        return $this->configured && $this->pdo instanceof PDO;
    }

    public function pdo()
    {
        return $this->pdo;
    }
}
