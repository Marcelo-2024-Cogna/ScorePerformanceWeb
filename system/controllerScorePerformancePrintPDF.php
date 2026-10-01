?php

declare(strict_types=1);

require_once __DIR__ . '/config/tcpdf/tcpdf.php';

/**
 * PDF — extensão de TCPDF com cabeçalho e rodapé padrão do sistema.
 *
 * @version 2.0.0
 */
final class PDF extends TCPDF
{
    private string $pageTitle;

    public function __construct(
        string $orientation = 'L',
        string $unit = 'mm',
        string $format = 'A4',
        string $pageTitle = 'Score Performance - Impressão'
    ) {
        parent::__construct($orientation, $unit, $format, true, 'UTF-8', false);
        $this->pageTitle = $pageTitle;
    }

    public function Header(): void
    {
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 10, $this->pageTitle, 0, 0, 'C');
        $this->Ln(10);
    }

    public function Footer(): void
    {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
    }
}

/**
 * PdfTableBuilder — constrói tabelas paginadas em documentos TCPDF.
 *
 * Extrai a lógica repetitiva de cabeçalho/linhas dos vários relatórios.
 *
 * @version 2.0.0
 */
final class PdfTableBuilder
{
    private const ROWS_PER_PAGE = 15;

    public function __construct(private readonly PDF $pdf) {}

    /**
     * Renderiza uma tabela com cabeçalho repetido a cada N linhas.
     *
     * @param  string[]                         $headers  Títulos das colunas
     * @param  int[]                            $widths   Largura de cada coluna em mm
     * @param  array<int, array<string, mixed>> $rows     Dados
     * @param  string[]                         $keys     Chaves do array de cada linha
     * @param  string                           $title    Título da página
     */
    public function renderTable(
        array $headers,
        array $widths,
        array $rows,
        array $keys,
        string $title
    ): void {
        $this->printTableHeader($headers, $widths, $title);

        $i = 0;
        foreach ($rows as $row) {
            if ($i > 0 && $i % self::ROWS_PER_PAGE === 0) {
                $this->pdf->AddPage();
                $this->printTableHeader($headers, $widths, $title);
            }

            $this->pdf->SetTextColor(0, 0, 255);
            $this->pdf->SetFont('helvetica', '', 9);

            foreach ($keys as $idx => $key) {
                $value = htmlspecialchars((string) ($row[$key] ?? ''), ENT_QUOTES | ENT_HTML5);
                $this->pdf->Cell($widths[$idx], 10, $value, 1);
            }
            $this->pdf->Ln();
            $i++;
        }
    }

    private function printTableHeader(array $headers, array $widths, string $title): void
    {
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetFont('helvetica', 'B', 12);
        $this->pdf->Cell(20, 5, $title, 0, 1);
        $this->pdf->Ln();
        $this->pdf->SetFont('helvetica', '', 10);

        foreach ($headers as $idx => $header) {
            $this->pdf->Cell($widths[$idx], 10, $header, 1);
        }
        $this->pdf->Ln();
    }
}
