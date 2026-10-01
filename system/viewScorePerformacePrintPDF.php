?php

declare(strict_types=1);

include_once 'controllerScorePerformancePrintPDF.php';

try {
    $pdfKey = filter_input(INPUT_GET, 'PDF', FILTER_SANITIZE_SPECIAL_CHARS);

    if (empty($pdfKey)) {
        throw new \InvalidArgumentException('Parâmetro PDF não informado.');
    }

    $pdf = new PDF('L', 'mm', 'A4');
    $pdf->SetCreator('Score Performance');
    $pdf->SetAuthor('Projeto Score Performance: ' . date('d-m-Y'));
    $pdf->SetTitle('Processamento de dados - Métricas de Maturidade');
    $pdf->SetSubject('Impressão da lista selecionada');
    $pdf->SetKeywords('TCPDF, PDF, ScorePerformance, v2.0.0');
    $pdf->AddPage();

    $builder = new PdfTableBuilder($pdf);

    match ($pdfKey) {
        'Regras' => (static function () use ($pdf, $builder): void {
            include_once 'controllerRegras.php';
            $rows = (new controllerRegras())->dadosRegras();
            if (empty($rows)) {
                throw new \RuntimeException('Dados das Regras não encontrados.');
            }
            $builder->renderTable(
                ['Categoria', 'Métricas', 'Entendimento', 'Percentual', 'Pontuação'],
                [20, 55, 160, 20, 20],
                $rows,
                ['categoria', 'metricas', 'entendimento', 'percentual', 'pontuacao'],
                'Informações das Regras'
            );
        })(),

        'FaixaDet' => (static function () use ($pdf, $builder): void {
            include_once 'controllerFaixasDetalhadas.php';
            $rows = (new controllerFaixasDetalhadas())->dadosFaixasDetalhadas();
            if (empty($rows)) {
                throw new \RuntimeException('Dados das Faixas Detalhadas não encontrados.');
            }
            $builder->renderTable(
                ['Categoria', 'Métricas', 'Faixas', 'Pontuação', '% Inicial', '% Final', 'Nt. Inicial', 'Nt. Final'],
                [20, 60, 60, 20, 20, 20, 20, 20],
                $rows,
                ['categoria', 'metrica', 'faixa', 'pontuacao_maxima', 'percentual_inicial', 'percentual_final', 'nota_inicial', 'nota_final'],
                'Informações das Faixas Detalhadas'
            );
        })(),

        'FaixaOrg' => (static function () use ($pdf, $builder): void {
            include_once 'controllerFaixasOrganizadas.php';
            $rows = (new controllerFaixasOrganizadas())->dadosFaixasOrganizadas();
            if (empty($rows)) {
                throw new \RuntimeException('Dados das Faixas Organizadas não encontrados.');
            }
            $builder->renderTable(
                ['Categoria', 'Métricas', 'Faixas', 'Percentual', 'Fx. Inicial', 'Fx. Final', 'Nota'],
                [20, 60, 60, 20, 20, 20, 20],
                $rows,
                ['categoria', 'metrica', 'faixa', 'percentual', 'flag_inicial', 'flag_final', 'nota'],
                'Informações das Faixas Organizadas'
            );
        })(),

        'scorePerformance' => (static function () use ($pdf, $builder): void {
            include_once 'controllerScorePerformance.php';
            $rows = (new controllerScorePerformance())->controllerGetDataScorePerformance();
            if (!is_array($rows) || empty($rows)) {
                throw new \RuntimeException('Dados do Score Performance não encontrados.');
            }
            $builder->renderTable(
                ['Categoria', 'Métricas', 'Faixas', 'Jornadas', 'Times', 'Valor', 'Nota'],
                [20, 60, 50, 55, 55, 15, 15],
                $rows,
                ['categoria', 'metrica', 'faixa', 'jornada', 'time', 'valor', 'nota'],
                'Score Performance - Resultado Processado'
            );
        })(),

        default => throw new \InvalidArgumentException("Tipo de PDF desconhecido: {$pdfKey}"),
    };

    $filename = match ($pdfKey) {
        'Regras'           => 'ScorePerformance_Regras.pdf',
        'FaixaDet'         => 'ScorePerformance_faixasDetalhadas.pdf',
        'FaixaOrg'         => 'ScorePerformance_faixasOrganizadas.pdf',
        'scorePerformance' => 'ScorePerformance_Resultados.pdf',
        default            => 'ScorePerformance.pdf',
    };

    $pdf->Output($filename, 'D');

} catch (\Throwable $e) {
    http_response_code(400);
    echo htmlspecialchars('Erro na geração do PDF: ' . $e->getMessage(), ENT_QUOTES | ENT_HTML5);
}
