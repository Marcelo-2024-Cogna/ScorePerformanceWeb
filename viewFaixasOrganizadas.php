<?php
include 'controllerFaixasOrganizadas.php';
$dataTracks = new controllerFaixasOrganizadas();
$viewDataContent = $dataTracks->DadosFaixasOrganizadas();

// montar a visão das informações de banco de dados.
// encaminhar variavel $resultSet
try {
    if (is_array($viewDataContent)) {
        // variável para gerar PDF
        $view = 'FaixaOrg';
        
        // variável para gerar a lista
        $viewContent = "
                <h2 class='my-2'>Faixas organizadas</h2>
                <table class='table table-striped' style='font-size: 12px;'>
                <thead>
                    <tr>
                        <th class='sortable'>Categoria</th>
                        <th class='sortable'>Métrica</th>
                        <th class='sortable'>Faixa</th>
                        <th class='sortable'>%</th>
                        <th class='sortable'>Nota</th>
                    </tr>
                </thead>
                <tbody>";
        foreach ($viewDataContent as $key) {
            $viewContent .= "
                    <tr>
                        <td>" . $key['categoria'] . "</td>
                        <td>" . $key['metrica'] . "</td>
                        <td>" . $key['faixa'] . "</td>
                        <td>" . $key['percentual'] . "</td>                        
                        <td>" . $key['nota'] . "</td>
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