?php

declare(strict_types=1);

include_once 'database/DatabaseConnection.php';

/**
 * ModelRegras — acesso a dados das regras de pontuação.
 *
 * @version 2.0.0
 */
final class modelRegras
{
    private readonly \PDO $pdo;

    public function __construct()
    {
        $this->pdo = DatabaseConnection::getInstance()->getPdo();
    }

    /**
     * Retorna todas as regras ativas do painel.
     *
     * @return array<int, array<string, mixed>>
     */
    public function buscaDadosRegras(): array
    {
        $sql = "SELECT categoria,
                       metricas,
                       entendimento,
                       regras,
                       percentual,
                       pontuacao,
                       atualizacao
                  FROM painel_regras
                 ORDER BY categoria, metricas";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll() ?: [];
    }
}
