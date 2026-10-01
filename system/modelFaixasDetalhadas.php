?php

declare(strict_types=1);

include_once 'database/DatabaseConnection.php';

/**
 * ModelFaixasDetalhadas — acesso a dados das faixas detalhadas.
 *
 * @version 2.0.0
 */
final class modelFaixasDetalhadas
{
    private readonly \PDO $pdo;

    public function __construct()
    {
        $this->pdo = DatabaseConnection::getInstance()->getPdo();
    }

    /**
     * Retorna todas as faixas detalhadas.
     *
     * @return array<int, array<string, mixed>>
     */
    public function buscaDadosFaixasDet(): array
    {
        $sql = "SELECT faixa,
                       valores_referencia,
                       pontuacao_maxima,
                       percentual_inicial,
                       percentual_final,
                       nota_inicial,
                       nota_final,
                       categoria,
                       metrica
                  FROM painel_faixas_det
                 ORDER BY categoria, metrica, faixa";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll() ?: [];
    }

    /**
     * Retorna apenas as faixas com classificação "Atendeu Totalmente".
     *
     * @return array<int, array<string, mixed>>
     */
    public function buscaDadosFaixasIdeal(): array
    {
        $sql = "SELECT fx.metrica, fx.nota_inicial
                  FROM painel_faixas_det fx
                 WHERE LOWER(fx.faixa) = 'atendeu totalmente'
                 ORDER BY fx.metrica";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll() ?: [];
    }
}
