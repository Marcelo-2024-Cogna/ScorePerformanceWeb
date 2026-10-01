?php

declare(strict_types=1);

include_once 'modelScorePerformance.php';

/**
 * ControllerScorePerformance — orquestra operações de ScorePerformance.
 *
 * Retorna arrays de dados ou strings de erro; não gera HTML.
 * Os <select> HTML são construídos exclusivamente pelas views/templates.
 *
 * @version 2.0.0
 */
class controllerScorePerformance
{
    private readonly modelScorePerformance $model;

    // Dados de apoio carregados uma única vez
    private readonly array $journeys;
    private readonly array $squads;
    private readonly array $metrics;
    private readonly array $categories;
    private readonly array $allScores;

    public function __construct()
    {
        $this->model      = new modelScorePerformance();
        $this->journeys   = $this->model->dataScorePerformanceJourney();
        $this->squads     = $this->model->dataScorePerformanceSquads();
        $this->metrics    = $this->model->dataScorePerformanceMetrics();
        $this->categories = $this->model->dataScorePerformanceCategories();
        $this->allScores  = $this->model->dataScorePerformance();
    }

    // =========================================================================
    // Dados de apoio para formulários — retornam arrays, não HTML
    // =========================================================================

    /** @return array<int, array<string, mixed>> */
    public function getJourneys(): array
    {
        return $this->journeys;
    }

    /** @return array<int, array<string, mixed>> */
    public function getSquads(): array
    {
        return $this->squads;
    }

    /** @return array<int, array<string, mixed>> */
    public function getMetrics(): array
    {
        return $this->metrics;
    }

    /** @return array<int, array<string, mixed>> */
    public function getCategories(): array
    {
        return $this->categories;
    }

    // =========================================================================
    // Pesquisa com filtros
    // =========================================================================

    /**
     * Busca scores aplicando os filtros informados.
     *
     * @param  array<string, string> $params  Filtros da requisição POST
     * @return array<int, array<string, mixed>>|string  Array de resultados ou mensagem de erro
     */
    public function controllerDataScore(array $params): array|string
    {
        $queryType = $this->resolveQueryType($params);

        if ($queryType === null) {
            return 'Parâmetros de pesquisa insuficientes.';
        }

        $result = $this->model->dataScorePerformance($params, $queryType);

        return count($result) > 0
            ? $result
            : 'Dados não encontrados para a pesquisa informada.';
    }

    // =========================================================================
    // Gravação
    // =========================================================================

    /**
     * Grava um registro de Score Performance.
     *
     * @param  array<string, mixed> $values  Dados do formulário/CSV
     * @return string  Mensagem de resultado da stored procedure
     */
    public function controllerSetDataScorePerformance(array $values): string
    {
        $idJornada  = trim((string) ($values['id_jornadas']  ?? ''));
        $idTime     = trim((string) ($values['id_time']      ?? ''));
        $idCategoria= trim((string) ($values['id_categoria'] ?? ''));
        $idMetrica  = trim((string) ($values['id_metrica']   ?? ''));
        $periodo    = trim((string) ($values['ds_periodo']   ?? ''));
        $sprint     = trim((string) ($values['ds_sprint']    ?? ''));
        $valor      = trim((string) ($values['valor_regra']  ?? ''));

        if ($idJornada === '' || $idTime === '') {
            return 'Erro: Jornada e Time são obrigatórios.';
        }
        if ($idCategoria === '' || $idMetrica === '') {
            return 'Erro: Categoria e Métrica são obrigatórias.';
        }
        if ($periodo === '' || $sprint === '') {
            return 'Erro: Período e Sprint são obrigatórios.';
        }

        try {
            $rows = $this->model->setDataScorePerformance([
                'p_id_jornada'   => (int) $idJornada,
                'p_id_time'      => (int) $idTime,
                'p_id_categoria' => (int) $idCategoria,
                'p_id_metrica'   => (int) $idMetrica,
                'p_ds_periodo'   => $periodo,
                'p_ds_sprint'    => $sprint,
                'p_vl_regra'     => $valor,
            ]);

            return $rows[0]['dadosGravados'] ?? 'Gravado com sucesso.';
        } catch (\RuntimeException $e) {
            return 'Erro na gravação: ' . $e->getMessage();
        }
    }

    /**
     * Retorna todos os scores para exibição geral.
     *
     * @return array<int, array<string, mixed>>|string
     */
    public function controllerGetDataScorePerformance(): array|string
    {
        return count($this->allScores) > 0
            ? $this->allScores
            : 'Dados não encontrados.';
    }

    // =========================================================================
    // Métodos legados (compatibilidade com views existentes)
    // Retornam HTML de <select> — mantidos para não quebrar os templates
    // =========================================================================

    /** @deprecated Substituir pelo array de getJourneys() no template */
    public function controllerDataJourney(): string
    {
        return $this->buildSelect('id_jornadas', 'id_jornadas', $this->journeys, 'id_jornadas', 'descricao');
    }

    /** @deprecated Substituir pelo array de getSquads() no template */
    public function controllerDataSquads(): string
    {
        return $this->buildSelect('id_time', 'id_time', $this->squads, 'id_time', 'descricao');
    }

    /** @deprecated Substituir pelo array de getMetrics() no template */
    public function controllerDataMetrics(): string
    {
        return $this->buildSelect('id_metrica', 'id_metrica', $this->metrics, 'id_metrica', 'descricao');
    }

    /** @deprecated Substituir pelo array de getCategories() no template */
    public function controllerDataCategories(): string
    {
        return $this->buildSelect('id_categoria', 'id_categoria', $this->categories, 'id_categoria', 'descricao');
    }

    // =========================================================================
    // Privado
    // =========================================================================

    /**
     * Determina o tipo de query com base nos filtros informados.
     *
     * @param  array<string, string> $params
     */
    private function resolveQueryType(array $params): ?string
    {
        $jornada = $params['id_jornadas'] ?? '';
        $hasTime = isset($params['id_time']) && $params['id_time'] !== '';
        $periodo = $params['ds_periodo'] ?? '';
        $sprint  = $params['ds_sprint']  ?? '';

        if ($jornada === '') {
            return null;
        }

        return match (true) {
            $hasTime  && $periodo !== '' && $sprint !== '' => 'sql_completa',
            $hasTime  && $periodo !== '' && $sprint === '' => 'sql_jornada_time_periodo',
            $hasTime  && $periodo === '' && $sprint !== '' => 'sql_jornada_time_sprint',
            $hasTime  && $periodo === '' && $sprint === '' => 'sql_jornada_time',
            !$hasTime && $periodo !== '' && $sprint === '' => 'sql_jornada_periodo',
            !$hasTime && $periodo === '' && $sprint !== '' => 'sql_jornada_sprint',
            default                                        => 'sql_jornada',
        };
    }

    /**
     * Constrói HTML de <select> para compatibilidade com templates existentes.
     *
     * @param string                          $id
     * @param string                          $name
     * @param array<int, array<string, mixed>> $rows
     * @param string                          $valueKey
     * @param string                          $labelKey
     */
    private function buildSelect(string $id, string $name, array $rows, string $valueKey, string $labelKey): string
    {
        $html = "<select class='form-control' id='" . htmlspecialchars($id, ENT_QUOTES | ENT_HTML5)
              . "' name='" . htmlspecialchars($name, ENT_QUOTES | ENT_HTML5) . "' required>"
              . "<option value='' disabled selected>Escolha...</option>";

        foreach ($rows as $row) {
            $value = htmlspecialchars((string) ($row[$valueKey] ?? ''), ENT_QUOTES | ENT_HTML5);
            $label = htmlspecialchars((string) ($row[$labelKey] ?? ''), ENT_QUOTES | ENT_HTML5);
            $html .= "<option value='{$value}'>{$label}</option>";
        }

        return $html . '</select>';
    }
}
