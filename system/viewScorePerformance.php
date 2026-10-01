?php

declare(strict_types=1);

include_once 'controllerScorePerformance.php';

$controller = new controllerScorePerformance();

// Grava lançamento unitário se submetido via POST
$saveMessage = '';
if (isset($_POST['dataProcess']) && $_POST['dataProcess'] === 'true') {
    $saveMessage = $controller->controllerSetDataScorePerformance($_POST);
}

$rows      = $controller->controllerGetDataScorePerformance();
$view      = 'scorePerformance';
$viewError = '';

if (!is_array($rows)) {
    $viewError = (string) $rows;
    $rows = [];
}

include 'templates/template.php';
