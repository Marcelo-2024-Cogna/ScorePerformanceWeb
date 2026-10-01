?php

declare(strict_types=1);

include_once 'controllerFaixasDetalhadas.php';

$controller = new controllerFaixasDetalhadas();
$rows       = $controller->dadosFaixasDetalhadas();
$view       = 'FaixaDet';
$viewError  = '';

if (empty($rows)) {
    $viewError = 'Sem dados de faixas detalhadas para exibir.';
}

include 'templates/template.php';
