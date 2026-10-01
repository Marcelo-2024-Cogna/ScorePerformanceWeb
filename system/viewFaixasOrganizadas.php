?php

declare(strict_types=1);

include_once 'controllerFaixasOrganizadas.php';

$controller = new controllerFaixasOrganizadas();
$rows       = $controller->dadosFaixasOrganizadas();
$view       = 'FaixaOrg';
$viewError  = '';

if (empty($rows)) {
    $viewError = 'Sem dados de faixas organizadas para exibir.';
}

include 'templates/template.php';
