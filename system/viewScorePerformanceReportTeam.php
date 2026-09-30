<?php
    include 'controllerScorePerformance.php';
    $dataLoad = new controllerScorePerformance();
    $viewDataContentJourney = $dataLoad->controllerDataJourney();
    $viewDataContentSquads = $dataLoad->controllerDataSquads();

    if(isset($_POST['dataProcessReportTeam']) && ($_POST['dataProcessReportTeam'] == 'true')){

        // Pesquisa dados para montar o gráfico dos resultados
        require_once "controllerRegras.php";
        $dataRules = new controllerRegras();
        $viewDataRange = $dataRules->DadosRegras();
        
        // Pesquisar dados conforme filtros de pesquisa
        $viewDataContent = $dataLoad->controllerDataScore($_POST);
        if((is_array($viewDataContent))&&(is_array($viewDataRange))){

            // Montar informações da segunda linha do gráfico: resultado do time
            foreach ($viewDataContent as $key) {
                $jornada = $key['jornada'];
                $dscTime = $key['time'];

                // Montar informações da primeira linha do gráfico: resultado esperado
                foreach ($viewDataRange as $values) {
                    if(strtolower($key['metrica']) === strtolower($values['metricas'])){
                        // Label da primeira linha: Ideal
                        $metaIdeal[] = $values['metricas'];
                        $notaIdeal[] = $values['pontuacao'];

                        // Dados da segunda linha: Time
                        $metaTime[] = $key['metrica'];
                        $notaTime[] = $key['nota'];
                    }
                }
            }
                       
            // Montar informações detalhads conforme pesquisa.
            $viewData = "
            <h3>Comparativo entre métrica esperada e métrica do time</h3>
            <div class='container'>
                <div class='container chart-container' style='border-style: none;'>
                    <canvas id='myChart'></canvas>
                </div><br>
                <div style='border-style: none;'>
                    <p style='font-size: 25px; text-align: left; color: DarkViolet;'><strong>Dados detalhados do ".$dscTime."</strong></p>
                    <table class='table table-striped' style='font-size: 12px;'>
                    <thead>
                        <tr><th >Time</th>
                            <th >Categoria</th>
                            <th >Métrica</th>
                            <th >Sprint</th>
                            <th >Valor</th>
                            <th >Faixa</th>
                            <th >Nota</th>
                        </tr>
                    </thead>
                    <tbody>";
                    $notafinal = 0;
                    foreach ($viewDataContent as $key) {
                        $viewData .= "
                        <tr><td>" . $key['time'] . "</td>
                            <td>" . $key['categoria'] . "</td>
                            <td>" . $key['metrica'] . "</td>
                            <td>" . $key['sprint'] . "</td>
                            <td>" . $key['valor'] . "</td>
                            <td>" . $key['faixa'] . "</td>
                            <td>" . $key['nota'] . "</td>
                        </tr>";
                        $notafinal = intval($key['nota']) + $notafinal;
                    }
                    $viewData .= "
                    </tbody>
                    </table>
                    <p style='font-size: 25px; text-align: right; color: DarkViolet;'><strong>Score total: ".$notafinal."</strong><p>
                    <table style='font-size:15px; '>
                    <thead><tr><th>Faixas de desempenho</th></tr></thead>
                    <tbody>
                        <tr><td style='color: red; font-weight: bold;'>Não atendeu</td>
                            <td align='center'>0</td>
                            <td align='center'>149</td>
                        </tr>
                        <tr><td style='color: orange; font-weight: bold;'>Atendeu parcialmente</td>
                            <td align='center'>150</td>
                            <td align='center'>839</td>
                        </tr>
                        <tr><td style='color: blue; font-weight: bold;'>Atendeu totalmente</td>
                            <td align='center'>840</td>
                            <td align='center'>1000</td>
                        </tr>
                        <tr><td style='color: green; font-weight: bold;'>Superou</td>
                            <td align='center'>1001</td>
                            <td align='center'>1092</td>
                        </tr>
                    </tbody>
                    </table>
                </div>
            </div>";
        }else{
            $viewData = "<p style='font-size: 15px; width: 1500px; color: #CD5646FF;'>".$viewDataContent."<p>";
        }
    }
    include 'templates/templateReportTeam.php';
?>