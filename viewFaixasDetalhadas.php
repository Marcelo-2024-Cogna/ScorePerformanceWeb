<?php
include 'controllerFaixasDetalhadas.php';
$dataTracks = new controllerFaixasDetalhadas();
$viewDataContent = $dataTracks->DadosFaixasDetalhadas();

// montar a visão das informações de banco de dados.
// encaminhar variavel $resultSet
try {
    if (is_array($viewDataContent)) {
        // variável para gerar PDF
        $view = 'FaixaDet';

        // vairavel para gerar a lista
        $viewContent = "
                <h2 class='my-2'>Faixas detalhadas</h2>
                <table class='table table-striped' style='font-size: 12px;'>
                <thead>
                    <tr>
                        <th class='sortable'>Categoria</th>
                        <th class='sortable'>Métrica</th>
                        <th class='sortable'>Faixa</th>
                        <th class='sortable'>Referência</th>
                        <th class='sortable'>Pontuação</th>
                        <th class='sortable'>% inicial</th>
                        <th class='sortable'>% Final</th>
                        <th class='sortable'>Nota inicial</th>
                        <th class='sortable'>Nota Final</th>
                    </tr>
                </thead>
                <tbody>";
                foreach ($viewDataContent as $key) {
                    $viewContent .= "
                    <tr>
                        <td>" . $key['categoria'] . "</td>
                        <td>" . $key['metrica'] . "</td>
                        <td>" . $key['faixa'] . "</td>
                        <td>" . $key['valores_referencia'] . "</td>
                        <td>" . $key['pontuacao_maxima'] . "</td>
                        <td>" . $key['percentual_inicial'] . "</td>
                        <td>" . $key['percentual_final'] . "</td>
                        <td>" . $key['nota_inicial'] . "</td>
                        <td>" . $key['nota_final'] . "</td>
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