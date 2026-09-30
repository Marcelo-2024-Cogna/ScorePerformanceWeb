<?php
try {
    // Receber os dados JSON do corpo da requisição
    if(filter_var($_GET['PDF'])){
        
        // Criar o objeto PDF
        include 'controllerScorePerformancePrintPDF.php';
        // L = igual a paisagem
        // P = igual a retrato
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Projeto Score Performance: '.date('d-m-Y'));
        $pdf->SetTitle('Processamento de dados - Métricas de Maturidade');
        $pdf->SetSubject('Impressão da lista selecionada');
        $pdf->SetKeywords('TCPDF, PDF, ScorePerformance, v 1.0.1');
        $pdf->AddPage();

        switch (filter_var($_GET['PDF'])) {
            case 'Regras':
                include 'controllerRegras.php';
                $dataLoad = new controllerRegras();
                $dataProcessPDF = $dataLoad->DadosRegras();
                if(!$dataProcessPDF){
                    throw new Exception("Dados das Regras para geração do PDF não informados.");
                }else{
                    // Cabeçalho da tabela
                    $laberlPag = "Informações das Regras";
                    $listaGerada = 'ScorePerformance_Regras.pdf';
                    $pdf->SetTextColor(0, 0, 0); // Preto
                    $pdf->SetFont('helvetica', 'B', 12);
                    $pdf->Cell(20, 05, $laberlPag,0,1);
                    $pdf->Ln();
                    $pdf->SetFont('helvetica', '', 10);
                    $pdf->Cell(20, 10, 'Categoria'   , 1);
                    $pdf->Cell(55, 10, 'Metricas'    , 1);
                    $pdf->Cell(160,10, 'Entendimento', 1);
                    $pdf->Cell(20, 10, 'Percentual'  , 1);
                    $pdf->Cell(20, 10, 'Pontuação'   , 1);
                    $pdf->Ln();
                    $pdf->SetTextColor(0, 0, 255); // Azul
                    $pdf->SetFont('helvetica', '', 9);
                    $i =0;
                    foreach ($dataProcessPDF as $key){
                        // Acrescentar cabecalho nas páginas seguintes.
                        if($i >= 15){
                            $i = 0;
                            $pdf->AddPage();
                            $pdf->SetTextColor(0, 0, 0); // Preto
                            $pdf->SetFont('helvetica', 'B', 12);
                            $pdf->Cell(20, 05, $laberlPag,0,1);
                            $pdf->Ln();
                            $pdf->SetFont('helvetica', '', 10);
                            $pdf->Cell(20, 10, 'Categoria'   , 1);
                            $pdf->Cell(55, 10, 'Metricas'    , 1);
                            $pdf->Cell(160,10, 'Entendimento', 1);
                            $pdf->Cell(20, 10, 'Percentual'  , 1);
                            $pdf->Cell(20, 10, 'Pontuação'   , 1);
                            $pdf->Ln();
                        }                        
                        $pdf->SetTextColor(0, 0, 255); // Azul
                        $pdf->SetFont('helvetica', '', 9);
                        $pdf->Cell(20, 10, $key['categoria'   ], 1);
                        $pdf->Cell(55, 10, $key['metricas'    ], 1);
                        $pdf->Cell(160,10, $key['entendimento'], 1);
                        $pdf->Cell(20, 10, $key['percentual'  ], 1);
                        $pdf->Cell(20, 10, $key['pontuacao'   ], 1);
                        $pdf->Ln();
                    }
                }
                break;
            case 'FaixaDet':
                include 'controllerFaixasDetalhadas.php';
                $dataLoad = new controllerFaixasDetalhadas();
                $dataProcessPDF = $dataLoad->DadosFaixasDetalhadas(); 
                if(!$dataProcessPDF){
                    throw new Exception("Dados das Faixas Detalhadas para geração do PDF não informados.");
                }else{
                    // Cabeçalho da tabela
                    $laberlPag = "Informações das Faixas Detalhadas";
                    $listaGerada = 'ScorePerformance_faixasDetalhadas.pdf';
                    $pdf->SetTextColor(0, 0, 0); // Preto
                    $pdf->SetFont('helvetica', 'B', 12);
                    $pdf->Cell(20, 05, $laberlPag, 0, 1);
                    $pdf->Ln();
                    $pdf->SetFont('helvetica', '', 10);
                    $pdf->Cell(20, 10, 'Categoria'  , 1);
                    $pdf->Cell(60, 10, 'Metricas'   , 1);
                    $pdf->Cell(60, 10, 'Faixas'     , 1);
                    $pdf->Cell(20, 10, 'Pontuação'  , 1);
                    $pdf->Cell(20, 10, '% Inicial'  , 1);
                    $pdf->Cell(20, 10, '% Final'    , 1);
                    $pdf->Cell(20, 10, 'Nt. Inicial', 1);
                    $pdf->Cell(20, 10, 'Nt. Final'  , 1);
                    $pdf->Ln();
                    $pdf->SetFont('helvetica', '', 9);
                    $pdf->SetTextColor(0, 0, 255); // Azul
                    $i =0;
                    foreach ($dataProcessPDF as $key){
                        // Acrescentar cabecalho nas páginas seguintes.
                        if($i >= 15){
                            $i = 0;
                            $pdf->AddPage();
                            $pdf->SetFont('helvetica', 'B', 12);
                            $pdf->SetTextColor(0, 0, 0); // Preto
                            $pdf->Cell(20, 05, $laberlPag, 0, 1);
                            $pdf->Ln();
                            $pdf->SetFont('helvetica', '', 10);
                            $pdf->Cell(20, 10, 'Categoria'  , 1);
                            $pdf->Cell(60, 10, 'Metricas'   , 1);
                            $pdf->Cell(60, 10, 'Faixas'     , 1);
                            $pdf->Cell(20, 10, 'Pontuação'  , 1);
                            $pdf->Cell(20, 10, '% Inicial'  , 1);
                            $pdf->Cell(20, 10, '% Final'    , 1);
                            $pdf->Cell(20, 10, 'Nt. Inicial', 1);
                            $pdf->Cell(20, 10, 'Nt. Final'  , 1);
                            $pdf->Ln();
                        }
                        $pdf->SetFont('helvetica', '', 9);
                        $pdf->SetTextColor(0, 0, 255); // Azul
                        $pdf->Cell(20, 10, $key['categoria'         ], 1);
                        $pdf->Cell(60, 10, $key['metrica'           ], 1);
                        $pdf->Cell(60, 10, $key['faixa'             ], 1);
                        $pdf->Cell(20, 10, $key['pontuacao_maxima'  ], 1);
                        $pdf->Cell(20, 10, $key['percentual_inicial'], 1);
                        $pdf->Cell(20, 10, $key['percentual_final'  ], 1);
                        $pdf->Cell(20, 10, $key['nota_inicial'      ], 1);
                        $pdf->Cell(20, 10, $key['nota_final'        ], 1);                                                                        
                        $pdf->Ln();
                        $i++;                        
                    }
                }
                break;

            case 'FaixaOrg':
                include 'controllerFaixasOrganizadas.php';
                $dataLoad = new controllerFaixasOrganizadas();
                $dataProcessPDF = $dataLoad->DadosFaixasOrganizadas(); 
                if(!$dataProcessPDF){
                    throw new Exception("Dados das Faixas Organizadas para geração do PDF não informados.");
                }else{
                    // Cabeçalho da tabela
                    $laberlPag = "Informações das Faixas Organizadas";
                    $listaGerada = 'ScorePerformance_faixasOrganizadas.pdf';
                    $pdf->SetTextColor(0, 0, 0); // Preto
                    $pdf->SetFont('helvetica', 'B', 12);
                    $pdf->Cell(20, 05, $laberlPag, 0, 1);
                    $pdf->Ln();
                    $pdf->SetFont('helvetica', '', 10);
                    $pdf->Cell(20, 10, 'Categoria'  , 1);
                    $pdf->Cell(60, 10, 'Métricas'   , 1);
                    $pdf->Cell(60, 10, 'Faixas'     , 1);
                    $pdf->Cell(20, 10, 'Percentual' , 1);
                    $pdf->Cell(20, 10, 'Fx. Inicial', 1);
                    $pdf->Cell(20, 10, 'Fx. Final'  , 1);
                    $pdf->Cell(20, 10, 'Nota'       , 1);           
                    $pdf->Ln();
                    $pdf->SetFont('helvetica', '', 9);
                    $pdf->SetTextColor(0, 0, 255); // Azul
                    $i =0;
                    foreach ($dataProcessPDF as $key){
                        // Acrescentar cabecalho nas páginas seguintes.
                        if($i >= 15){
                            $i = 0;
                            $pdf->AddPage();
                            $pdf->SetFont('helvetica', 'B', 12);
                            $pdf->SetTextColor(0, 0, 0); // Preto
                            $pdf->Cell(20, 05, $laberlPag, 0 ,1);
                            $pdf->Ln();
                            $pdf->SetFont('helvetica', '', 10);
                            $pdf->Cell(20, 10, 'Categoria'  , 1);
                            $pdf->Cell(60, 10, 'Métricas'   , 1);
                            $pdf->Cell(60, 10, 'Faixas'     , 1);
                            $pdf->Cell(20, 10, 'Percentual' , 1);
                            $pdf->Cell(20, 10, 'Fx. Inicial', 1);
                            $pdf->Cell(20, 10, 'Fx. Final'  , 1);
                            $pdf->Cell(20, 10, 'Nota'       , 1);
                            $pdf->Ln();
                        }
                        $pdf->SetFont('helvetica', '', 9);
                        $pdf->SetTextColor(0, 0, 255); // Azul            
                        $pdf->Cell(20, 10, $key['categoria'       ], 1);
                        $pdf->Cell(60, 10, $key['metrica'         ], 1);
                        $pdf->Cell(60, 10, $key['faixa'           ], 1);
                        $pdf->Cell(20, 10, $key['percentual'      ], 1);
                        $pdf->Cell(20, 10, $key['flag_inicial'    ], 1);
                        $pdf->Cell(20, 10, $key['flag_final'      ], 1);
                        $pdf->Cell(20, 10, $key['nota'            ], 1);                                                                        
                        $pdf->Ln();
                        $i++;
                    }                      
                }
                break;

            case 'scorePerformance':
                include 'controllerScorePerformance.php';
                $dataLoad = new controllerScorePerformance();
                $dataProcessPDF = $dataLoad->controllerGetDataScorePerformance(); 
                if(!$dataProcessPDF){
                    throw new Exception("Dados das Faixas Detalhadas para geração do PDF não informados.");
                }else{
                    // Cabeçalho da tabela
                    $laberlPag = "Score Performance - Resultado Processado";
                    $listaGerada = 'ScorePerformance_Resultados.pdf';
                    $pdf->SetTextColor(255, 0, 0); // Vermelho
                    $pdf->SetFont('helvetica', 'B', 12);
                    $pdf->Cell(20, 05, $laberlPag, 0, 1);
                    $pdf->Ln();
                    $pdf->SetTextColor(0, 0, 0); // Preto
                    $pdf->SetFont('helvetica', '', 10);
                    $pdf->Cell(20, 10, 'Categoria'  , 1);
                    $pdf->Cell(60, 10, 'Métricas'   , 1);
                    $pdf->Cell(50, 10, 'Faixas'     , 1);
                    $pdf->Cell(55, 10, 'Jornadas'   , 1);
                    $pdf->Cell(55, 10, 'Times'      , 1);
                    $pdf->Cell(15, 10, 'Valor'      , 1);
                    $pdf->Cell(15, 10, 'Nota'       , 1);   
                    $pdf->Ln();
                    $pdf->SetFont('helvetica', '', 9);
                    $pdf->SetTextColor(0, 0, 255); // Azul
                    $i =0;
                    foreach ($dataProcessPDF as $key){
                        // Acrescentar cabecalho nas páginas seguintes.
                        if($i >= 15){
                            $i = 0;
                            $pdf->AddPage();
                            $pdf->SetTextColor(255, 0, 0); // Vermelho
                            $pdf->SetFont('helvetica', 'B', 12);
                            $pdf->Cell(20, 05, $laberlPag, 0, 1);
                            $pdf->Ln();
                            $pdf->SetTextColor(0, 0, 0); // Preto                         
                            $pdf->SetFont('helvetica', '', 10);
                            $pdf->Cell(20, 10, 'Categoria'  , 1);
                            $pdf->Cell(60, 10, 'Métricas'   , 1);
                            $pdf->Cell(50, 10, 'Faixas'     , 1);
                            $pdf->Cell(55, 10, 'Jornadas'   , 1);
                            $pdf->Cell(55, 10, 'Times'      , 1);
                            $pdf->Cell(15, 10, 'Valor'      , 1);
                            $pdf->Cell(15, 10, 'Nota'       , 1);   
                            $pdf->Ln();
                        }                  
                        $pdf->SetFont('helvetica', '', 9);
                        $pdf->SetTextColor(0, 0, 255); // Azul
                        $pdf->Cell(20, 10, $key['categoria' ], 1);
                        $pdf->Cell(60, 10, $key['metrica'   ], 1);
                        $pdf->Cell(50, 10, $key['faixa'     ], 1);                        
                        $pdf->Cell(55, 10, $key['jornada'   ], 1);
                        $pdf->Cell(55, 10, $key['time'      ], 1);
                        $pdf->Cell(15, 10, $key['valor'     ], 1);
                        $pdf->Cell(15, 10, $key['nota'      ], 1);                                                                        
                        $pdf->Ln();
                        $i++;                        
                    }
                }
                break;
            default:
                throw new Exception("Erro na pesquisa dos dados para impressão");
                break;
        }
        $pdf->Output($listaGerada, 'D');
    }else{
        throw new Exception("Dados para geração do PDF não informados");
    }
} catch (\Throwable $th) {
    $dataProcessPDF = $th->getMessage();
}
?>