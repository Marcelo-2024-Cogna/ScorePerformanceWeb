?php

declare(strict_types=1);

include_once 'controllerScorePerformance.php';
include_once 'controllerRegras.php';

$controller  = new controllerScorePerformance();
$journeys    = $controller->getJourneys();
$squads      = $controller->getSquads();

$reportData  = [];
$rulesData   = [];
$metaIdeal   = [];
$notaIdeal   = [];
$metaTime    = [];
$notaTime    = [];
$jornada     = '';
$dscTime     = '';
$notafinal   = 0;
$viewError   = '';

if (isset($_POST['dataProcessReportTeam']) && $_POST['dataProcessReportTeam'] === 'true') {

    $rulesController = new controllerRegras();
    $rulesData       = $rulesController->dadosRegras();
    $result          = $controller->controllerDataScore($_POST);

    if (is_array($result) && is_array($rulesData)) {
        $reportData = $result;

        foreach ($reportData as $row) {
            $jornada  = htmlspecialchars((string) $row['jornada'], ENT_QUOTES | ENT_HTML5);
            $dscTime  = htmlspecialchars((string) $row['time'],    ENT_QUOTES | ENT_HTML5);

            foreach ($rulesData as $rule) {
                if (strtolower($row['metrica']) === strtolower($rule['metricas'])) {
                    $metaIdeal[] = htmlspecialchars((string) $rule['metricas'], ENT_QUOTES | ENT_HTML5);
                    $notaIdeal[] = (float) $rule['pontuacao'];
                    $metaTime[]  = htmlspecialchars((string) $row['metrica'], ENT_QUOTES | ENT_HTML5);
                    $notaTime[]  = (float) $row['nota'];
                }
            }
            $notafinal += (int) $row['nota'];
        }
    } else {
        $viewError = is_string($result) ? $result : 'Nenhum dado encontrado para os filtros informados.';
    }
}

include 'templates/templateReportTeam.php';
