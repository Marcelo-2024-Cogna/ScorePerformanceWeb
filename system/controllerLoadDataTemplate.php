?php

declare(strict_types=1);

include_once 'controllerScorePerformance.php';

/**
 * ControllerLoadDataTemplate — processa o upload em lote via CSV.
 *
 * Usa composição em vez de herança: recebe controllerScorePerformance
 * como dependência injetada no construtor.
 *
 * @version 2.0.0
 */
final class controllerLoadDataTemplate
{
    /** Colunas esperadas no CSV (separador ponto-e-vírgula). */
    private const EXPECTED_HEADER = 'ID_JORNADA;ID_TIME;ID_CATEGORIA;ID_METRICA;PERIODO;SPRINT;VALOR';

    /** Tipos de arquivo aceitos. */
    private const ALLOWED_TYPES = ['text/csv', 'text/plain', 'application/vnd.ms-excel', 'csv', 'txt'];

    private readonly controllerScorePerformance $scoreController;

    public function __construct(?controllerScorePerformance $scoreController = null)
    {
        $this->scoreController = $scoreController ?? new controllerScorePerformance();
    }

    // =========================================================================
    // Validação do CSV
    // =========================================================================

    /**
     * Valida o arquivo CSV contra o cabeçalho esperado e as regras de cada coluna.
     *
     * @param  string   $filePath         Caminho temporário do arquivo
     * @param  string[] $expectedColumns  Array com os nomes das colunas esperadas
     * @return string[]  Lista de erros encontrados (vazia se válido)
     */
    public function validarTemplate(string $filePath, array $expectedColumns): array
    {
        $errors = [];

        if (!file_exists($filePath) || !is_readable($filePath)) {
            return ['Arquivo não encontrado ou não pode ser lido.'];
        }

        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            return ['Erro ao abrir o arquivo.'];
        }

        $header = fgetcsv($handle, separator: ';');

        if ($header !== $expectedColumns) {
            fclose($handle);
            return ['Template inválido. Colunas fora do padrão esperado.'];
        }

        $lineNumber = 1;
        while (($row = fgetcsv($handle, separator: ';')) !== false) {
            $lineNumber++;

            if (count($row) !== count($expectedColumns)) {
                $errors[] = "Linha {$lineNumber}: número de colunas incorreto.";
                continue;
            }

            $errors = array_merge($errors, $this->validateRow($row, $expectedColumns, $lineNumber));
        }

        fclose($handle);
        return $errors;
    }

    // =========================================================================
    // Processamento em lote
    // =========================================================================

    /**
     * Lê e processa o arquivo CSV em lote.
     *
     * Cada linha é processada de forma independente; falhas individuais
     * não interrompem o lote.
     *
     * @return array<int, string>|string  Array de resultados por linha ou mensagem de erro
     */
    public function lerDadosArquivos(
        string $fileTemp,
        string $filesType,
        string $filesName,
        string $extension
    ): array|string {
        if (!in_array($filesType, self::ALLOWED_TYPES, true) &&
            !in_array($extension, self::ALLOWED_TYPES, true)) {
            return "Tipo de arquivo não permitido: {$filesName}";
        }

        $expectedCols = explode(';', self::EXPECTED_HEADER);
        $errors = $this->validarTemplate($fileTemp, $expectedCols);

        if (!empty($errors)) {
            return "Erros encontrados:\n" . implode("\n", $errors);
        }

        $handle = fopen($fileTemp, 'r');
        if ($handle === false) {
            return "Erro ao abrir o arquivo: {$filesName}";
        }

        fgets($handle); // pula cabeçalho

        $results    = [];
        $lineNumber = 1;

        set_time_limit(300);

        while (($line = fgets($handle)) !== false) {
            $lineNumber++;
            $line = rtrim($line, "\r\n");

            if ($line === '') {
                continue;
            }

            $cols = explode(';', $line);

            if (!isset($cols[6]) || trim($cols[1]) === '' || trim($cols[6]) === '') {
                continue;
            }

            $results[] = $this->scoreController->controllerSetDataScorePerformance([
                'id_jornadas'  => trim($cols[0]),
                'id_time'      => trim($cols[1]),
                'id_categoria' => trim($cols[2]),
                'id_metrica'   => trim($cols[3]),
                'ds_periodo'   => trim($cols[4]),
                'ds_sprint'    => trim($cols[5]),
                'valor_regra'  => trim($cols[6]),
            ]);
        }

        fclose($handle);
        set_time_limit(30); // restaura limite padrão

        return array_values(array_unique($results));
    }

    // =========================================================================
    // Privado
    // =========================================================================

    /**
     * Valida o conteúdo de uma linha do CSV.
     *
     * @param  string[] $row
     * @param  string[] $columns
     * @return string[]
     */
    private function validateRow(array $row, array $columns, int $lineNumber): array
    {
        $errors = [];

        foreach ($columns as $index => $column) {
            $value = $row[$index] ?? '';

            $isNumericColumn = in_array($column, ['ID_JORNADA', 'ID_TIME', 'ID_CATEGORIA', 'ID_METRICA'], true);
            $isFloatColumn   = $column === 'VALOR';

            if ($isNumericColumn && filter_var($value, FILTER_VALIDATE_INT) === false) {
                $errors[] = "Linha {$lineNumber}: valor inválido na coluna '{$column}' (esperado inteiro).";
            } elseif ($isFloatColumn && filter_var($value, FILTER_VALIDATE_FLOAT) === false) {
                $errors[] = "Linha {$lineNumber}: valor inválido na coluna '{$column}' (esperado decimal).";
            } elseif ($value === '' && in_array($column, ['PERIODO', 'SPRINT'], true)) {
                $errors[] = "Linha {$lineNumber}: campo obrigatório '{$column}' está vazio.";
            }
        }

        return $errors;
    }
}
