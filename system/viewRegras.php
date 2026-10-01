?php

declare(strict_types=1);

include_once 'controllerRegras.php';

$controller = new controllerRegras();
$rows       = $controller->dadosRegras();
$view       = 'Regras';
$viewError  = '';

if (empty($rows)) {
    $viewError = 'Sem dados de regras para exibir.';
}

include 'templates/template.php';
