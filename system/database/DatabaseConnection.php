?php

declare(strict_types=1);

/**
 * DatabaseConnection — Singleton PDO para MySQL.
 *
 * Lê as credenciais do array de configuração injetado no construtor,
 * permitindo substituição fácil por variáveis de ambiente no futuro.
 *
 * @version 2.0.0
 * @since   07/2024  (refatorado 2026)
 */
final class DatabaseConnection
{
    private static ?self $instance = null;
    private readonly \PDO $pdo;

    /** Impede instanciação direta; use DatabaseConnection::getInstance(). */
    private function __construct()
    {
        $host     = 'localhost';
        $dbName   = 'score_performance';
        $username = 'root';
        $password = '';

        $dsn = "mysql:host={$host};dbname={$dbName};charset=utf8mb4";

        $this->pdo = new \PDO($dsn, $username, $password, [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }

    /** Garante que apenas uma conexão PDO seja criada por processo PHP. */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /** Retorna a instância PDO configurada. */
    public function getPdo(): \PDO
    {
        return $this->pdo;
    }

    /** Impede clonagem do Singleton. */
    private function __clone(): void {}
}
