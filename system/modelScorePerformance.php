?php

declare(strict_types=1);

include_once 'database/DatabaseConnection.php';

/**
 * ModelScorePerformance — acesso a dados de ScorePerformance.
 *
 * Todas as queries que recebem entrada do usuário usam prepared statements.
 * A stored procedure usa bindParam para evitar SQL injection.
 *
 * @version 2.0.0
 */
final class modelScorePerformance
{
    private readonly \PDO $pdo;

    public function __construct()
    {
        $this->pdo = DatabaseConnection::getInstance()->getPdo();
    }

    // =========================================================================
    // Consultas de leitura — dados de Score Performance
    // =========================================================================

    /**
     * Busca os scores processados com filtros opcionais.
     *
     * @param  array<string, string> $filters  ['id_jornadas', 'id_time', 'ds_periodo', 'ds_sprint']
     * @param  string|null           $queryType  Tipo de pesquisa (ex: 'sql_completa')
     * @return array<int, array<string, mixed>>
     */
    public function dataScorePerformance(array $filters = [], ?string $queryType = null): array
    {
        $idJornada = $filters['id_jornadas'] ?? '';
        $idTime    = $filters['id_time']     ?? '';
        $periodo   = $filters['ds_periodo']  ?? '';
        $sprint    = $filters['ds_sprint']   ?? '';

        return match ($queryType) {
            'sql_completa' => $this->fetchScoreByAll($idJornada, $idTime, $periodo, $sprint),
            'sql_jornada_time' => $this->fetchScoreByJourneyAndTeam($idJornada, $idTime),
            'sql_jornada_time_periodo' => $this->fetchScoreByJourneyTeamPeriod($idJornada, $idTime, $periodo),
            'sql_jornada_time_sprint' => $this->fetchScoreByJourneyTeamSprint($idJornada, $idTime, $sprint),
            'sql_jornada_sprint' => $this->fetchRankingByJourneySprint($idJornada, $sprint),
            'sql_jornada_periodo' => $this->fetchRankingByJourneyPeriod($idJornada, $periodo),
            'sql_jornada' => $this->fetchRankingByJourney($idJornada),
            default => $this->fetchAllScores(),
        };
    }

    /** @return array<int, array<string, mixed>> */
    public function dataScorePerformanceJourney(): array
    {
        $stmt = $this->pdo->query(
            "SELECT id_jornadas, descricao
               FROM jornadas
              WHERE ativo = 'S'
              ORDER BY descricao ASC"
        );
        return $stmt->fetchAll() ?: [];
    }

    /** @return array<int, array<string, mixed>> */
    public function dataScorePerformanceSquads(): array
    {
        $stmt = $this->pdo->query(
            "SELECT id_time, jornadas_id, descricao
               FROM times
              WHERE ativo = 'S'
              ORDER BY descricao ASC"
        );
        return $stmt->fetchAll() ?: [];
    }

    /** @return array<int, array<string, mixed>> */
    public function dataScorePerformanceMetrics(): array
    {
        $stmt = $this->pdo->query(
            "SELECT id_metrica, descricao
               FROM metricas
              WHERE ativo = 'S'
              ORDER BY descricao ASC"
        );
        return $stmt->fetchAll() ?: [];
    }

    /** @return array<int, array<string, mixed>> */
    public function dataScorePerformanceCategories(): array
    {
        $stmt = $this->pdo->query(
            "SELECT id_categoria, descricao
               FROM categoria
              WHERE ativo = 'S'
              ORDER BY descricao ASC"
        );
        return $stmt->fetchAll() ?: [];
    }

    // =========================================================================
    // Gravação via stored procedure — usa bindParam, sem interpolação
    // =========================================================================

    /**
     * Grava um registro de Score Performance via stored procedure.
     *
     * @param  array<string, mixed> $parameters
     * @return array<int, array<string, mixed>>
     * @throws \RuntimeException se os parâmetros forem inválidos ou a procedure falhar
     */
    public function setDataScorePerformance(array $parameters): array
    {
        $required = ['p_id_jornada', 'p_id_time', 'p_id_categoria', 'p_id_metrica',
                     'p_ds_periodo', 'p_ds_sprint', 'p_vl_regra'];

        foreach ($required as $key) {
            if (!isset($parameters[$key]) || $parameters[$key] === '') {
                throw new \RuntimeException("Parâmetro obrigatório ausente: {$key}");
            }
        }

        // Prepared statement para a stored procedure — sem interpolação de string
        $sql = "CALL ScorePerformance(:idJornada, :idTime, :idCategoria, :idMetrica,
                                     :dsPeriodo, :dsSprint, :vlRegra, @p_return)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':idJornada',  $parameters['p_id_jornada'],   \PDO::PARAM_INT);
        $stmt->bindParam(':idTime',     $parameters['p_id_time'],      \PDO::PARAM_INT);
        $stmt->bindParam(':idCategoria',$parameters['p_id_categoria'], \PDO::PARAM_INT);
        $stmt->bindParam(':idMetrica',  $parameters['p_id_metrica'],   \PDO::PARAM_INT);
        $stmt->bindParam(':dsPeriodo',  $parameters['p_ds_periodo'],   \PDO::PARAM_STR);
        $stmt->bindParam(':dsSprint',   $parameters['p_ds_sprint'],    \PDO::PARAM_STR);
        $stmt->bindParam(':vlRegra',    $parameters['p_vl_regra'],     \PDO::PARAM_STR);
        $stmt->execute();

        $result = $this->pdo->query("SELECT @p_return AS dadosGravados");
        return $result->fetchAll() ?: [];
    }

    // =========================================================================
    // Métodos privados — cada tipo de consulta em método dedicado
    // =========================================================================

    /** @return array<int, array<string, mixed>> */
    private function fetchAllScores(): array
    {
        $stmt = $this->pdo->query(
            "SELECT periodo, sprint, valor, jornada, time, categoria, metrica, faixa, nota
               FROM score_performance"
        );
        return $stmt->fetchAll() ?: [];
    }

    /** @return array<int, array<string, mixed>> */
    private function fetchScoreByAll(string $jornada, string $time, string $periodo, string $sprint): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT periodo, sprint, valor, jornada, time, categoria, metrica, faixa, nota, pontuacao
               FROM score_performance
              WHERE id_jornadas = :idJornada
                AND id_time     = :idTime
                AND periodo     = :dsPeriodo
                AND sprint      = :dsSprint"
        );
        $stmt->execute([':idJornada' => $jornada, ':idTime' => $time,
                        ':dsPeriodo' => $periodo, ':dsSprint' => $sprint]);
        return $stmt->fetchAll() ?: [];
    }

    /** @return array<int, array<string, mixed>> */
    private function fetchScoreByJourneyAndTeam(string $jornada, string $time): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT periodo, sprint, valor, jornada, time, categoria, metrica, faixa, nota, pontuacao
               FROM score_performance
              WHERE id_jornadas = :idJornada
                AND id_time     = :idTime"
        );
        $stmt->execute([':idJornada' => $jornada, ':idTime' => $time]);
        return $stmt->fetchAll() ?: [];
    }

    /** @return array<int, array<string, mixed>> */
    private function fetchScoreByJourneyTeamPeriod(string $jornada, string $time, string $periodo): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT periodo, sprint, valor, jornada, time, categoria, metrica, faixa, nota, pontuacao
               FROM score_performance
              WHERE id_jornadas = :idJornada
                AND id_time     = :idTime
                AND periodo     = :dsPeriodo"
        );
        $stmt->execute([':idJornada' => $jornada, ':idTime' => $time, ':dsPeriodo' => $periodo]);
        return $stmt->fetchAll() ?: [];
    }

    /** @return array<int, array<string, mixed>> */
    private function fetchScoreByJourneyTeamSprint(string $jornada, string $time, string $sprint): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT periodo, sprint, valor, jornada, time, categoria, metrica, faixa, nota, pontuacao
               FROM score_performance
              WHERE id_jornadas = :idJornada
                AND id_time     = :idTime
                AND sprint      = :dsSprint"
        );
        $stmt->execute([':idJornada' => $jornada, ':idTime' => $time, ':dsSprint' => $sprint]);
        return $stmt->fetchAll() ?: [];
    }

    /** @return array<int, array<string, mixed>> */
    private function fetchRankingByJourneySprint(string $jornada, string $sprint): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT r.jornada, r.time, r.periodo, r.sprint, r.score_total, r.ranking
               FROM score_performance_ranking r
              WHERE r.id_jornadas = :idJornada
                AND r.sprint      = :dsSprint"
        );
        $stmt->execute([':idJornada' => $jornada, ':dsSprint' => $sprint]);
        return $stmt->fetchAll() ?: [];
    }

    /** @return array<int, array<string, mixed>> */
    private function fetchRankingByJourneyPeriod(string $jornada, string $periodo): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT r.jornada, r.time, r.periodo, r.sprint, r.score_total, r.ranking
               FROM score_performance_ranking r
              WHERE r.id_jornadas = :idJornada
                AND r.periodo     = :dsPeriodo"
        );
        $stmt->execute([':idJornada' => $jornada, ':dsPeriodo' => $periodo]);
        return $stmt->fetchAll() ?: [];
    }

    /** @return array<int, array<string, mixed>> */
    private function fetchRankingByJourney(string $jornada): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT r.jornada, r.time, r.periodo, r.sprint, r.score_total, r.ranking
               FROM score_performance_ranking r
              WHERE r.id_jornadas = :idJornada"
        );
        $stmt->execute([':idJornada' => $jornada]);
        return $stmt->fetchAll() ?: [];
    }
}
