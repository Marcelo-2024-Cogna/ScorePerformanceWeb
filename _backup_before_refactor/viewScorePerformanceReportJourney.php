<?php
    include 'controllerScorePerformance.php';
    $dataLoad = new controllerScorePerformance();
    $viewDataContentJourney = $dataLoad->controllerDataJourney();

    if(isset($_POST['dataProcessReportJourney']) && ($_POST['dataProcessReportJourney'] == 'true')){
         
        // Pesquisar dados conforme filtros de pesquisa
        $viewDataContent = $dataLoad->controllerDataScore($_POST);
        if(is_array($viewDataContent)){
 
            // Montar informações da segunda linha do gráfico: resultado do time
            foreach ($viewDataContent as $key) {
                $jornada = $key['jornada'];
                $dscTime[] = $key['time'];
                $score_total[] = $key['score_total'];
            }
        
            $viewData = "
            <div class='container'>
                <div class='row'>
                    <div class='col-md-6'>
                        <div class='card mb-4'>
                            <div class='card-body'>
                                <h5 class='card-title'>".$jornada."</h5>
                                <canvas id='ScoreJourney' style='max-height: 300px;'></canvas>
                            </div>
                        </div>
                    </div>
                    <div class='col-md-6'>
                        
                            <table class='table table-striped' style='font-size: 12px;'>
                            <thead>
                                <tr><th>Ranking</th>
                                    <th>Times</th>
                                    <th>Período</th>
                                    <th>Sprint</th>
                                    <th>Score Total</th>
                                </tr>
                            </thead>
                            <tbody>";
                            $totalScore = 0;
                            foreach ($viewDataContent as $key) {
                                $viewData .= "
                                <tr><td>". $key['ranking'] ."</td>
                                    <td>". $key['time'] ."</td>
                                    <td>". $key['periodo'] ."</td>
                                    <td>". $key['sprint'] ."</td>
                                    <td>". $key['score_total'] ."</td>
                                </tr>";
                                $totalScore = intval($key['score_total']) + $totalScore;
                            }                            
                            $viewData .= "
                            </tbody>
                            </table>
                            <p style='font-size: 25px; text-align: right; color: DarkViolet;'><strong>Score total da jornada: ".$totalScore."</strong><p>
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
                </div>
            </div>";
        }else{
            $viewData = "<p style='font-size: 15px; width: 1500px; color: #CD5646FF;'>".$viewDataContent."<p>";
        }
    }
    include 'templates/templateReportJourney.php';
?>