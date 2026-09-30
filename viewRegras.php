<?php
include 'controllerRegras.php';
$dataRules = new controllerRegras();
$viewDataContent = $dataRules->DadosRegras();

// montar a visão das informações de banco de dados.
// encaminhar variavel $resultSet
try {
    if (is_array($viewDataContent)) {
        // variável para gerar PDF
        $view = 'Regras';

        // variável para gerar lista
        $viewContent = "
                            <h2 class='my-2'>Regras e pontuação</h2>
                            <table class='table table-striped' style='font-size: 12px;'>
                            <thead>
                                <tr>
                                    <th class='sortable'>Categoria</th>
                                    <th class='sortable'>Métrica</th>
                                    <th class='sortable'>Regra</th>
                                    <th class='sortable'>%</th>
                                    <th class='sortable'>Pontuação</th>
                                    <th class='sortable'>Atualização</th>
                                </tr>
                            </thead>
                            <tbody>";

        foreach ($viewDataContent as $key) {
            $viewContent .= "
                                <tr>
                                    <td>" . $key['categoria'] . "</td>
                                    <td>" . $key['metricas'] . "</td>
                                    <td>" . $key['regras'] . "</td>
                                    <td>" . $key['percentual'] . "</td>
                                    <td>" . $key['pontuacao'] . "</td>
                                    <td>" . $key['atualizacao'] . "</td>                    
                                </tr>";
        }
        $viewContent .= "
                            </tbody>
                            </table>";
    } else {
        throw new Exception($viewDataContent);
    }
} catch (Exception $e) {
    $viewContent = $e->getMessage();
}
include 'templates/template.php';
?>
