<?php
    include 'controllerScorePerformance.php';
    $dataLoad = new controllerScorePerformance();
    $viewDataContent = $dataLoad->controllerGetDataScorePerformance(); 

    //Grava o lançamento e processa os valores para verificar a nota da faixa
    if(isset($_POST['dataProcess']) && ($_POST['dataProcess'] == 'true')){
        $viewSetData = $dataLoad->controllerSetDataScorePerformance($_POST);
    }
    // montar a visão das informações de banco de dados.
    // encaminhar variavel $resultSet
    try{
        if (is_array($viewDataContent)) {
            header('Refresh:60');
            // variável para gerar PDF
            $view = 'scorePerformance';

            // variável para gerar lista
            $viewContent = "
                <h2 class='my-2'>Dados Processados</h2>
                <table class='table table-striped' style='font-size: 12px;'>
                <thead>
                    <tr>
                        <th >Jornada</th>
                        <th >Time</th>
                        <th >Período</th>
                        <th >Categoria</th>
                        <th >Métrica</th>
                        <th >Valor</th>
                        <th >Faixa</th>                        
                        <th >Nota</th>
                    </tr>
                </thead>
                <tbody>";
                foreach ($viewDataContent as $key) {
                    $viewContent .= "
                    <tr>
                        <td>" . $key['jornada'] . "</td>
                        <td>" . $key['time'] . "</td>
                        <td>" . $key['periodo'] . "</td>
                        <td>" . $key['categoria'] . "</td>
                        <td>" . $key['metrica'] . "</td>
                        <td>" . $key['valor'] . "</td>
                        <td>" . $key['faixa'] . "</td>
                        <td>" . $key['nota'] . "</td>
                    </tr>";
                }
            $viewContent .= "
                </tbody>
                </table>";
        }else{
            throw new Exception($viewDataContent); 
        }
    }
    catch (Exception $e) {
        $viewContent = $e->getMessage();
    }   
    include 'templates/template.php';
?>

