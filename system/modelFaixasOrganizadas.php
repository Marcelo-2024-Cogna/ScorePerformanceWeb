?php

declare(strict_types=1);

include_once 'database/DatabaseConnection.php';

/**
 * ModelFaixasOrganizadas — acesso a dados das faixas organizadas.
 *
 * @version 2.0.0
 */
final class modelFaixasOrganizadas
{
    private readonly \PDO $pdo;

    public function __construct()
    {
        $this->pdo = DatabaseConnection::getInstance()->getPdo();
    }

    /**
     * Retorna todas as faixas organizadas.
     *
     * @return array<int, array<string, mixed>>
     */
    public function buscaDadosFaixasOrg(): array
    {
        $sql = "SELECT faixa,
                       flag_inicial,
                       flag_final,
                       nota,
                       percentual,
                       categoria,
                       metrica
                  FROM painel_faixas_org
                 ORDER BY categoria, metrica, faixa";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll() ?: [];
    }
}
