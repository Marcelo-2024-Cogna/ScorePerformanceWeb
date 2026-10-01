?php

declare(strict_types=1);

include_once 'controllerScorePerformance.php';

$controller = new controllerScorePerformance();
$journeys   = $controller->getJourneys();

$reportData  = [];
$dscTime     = [];
$scoreTotal  = [];
$jornada     = '';
$totalScore  = 0;
$viewError   = '';

if (isset($_POST['dataProcessReportJourney']) && $_POST['dataProcessReportJourney'] === 'true') {

    $result = $controller->controllerDataScore($_POST);

    if (is_array($result)) {
        $reportData = $result;

        foreach ($reportData as $row) {
            $jornada      = htmlspecialchars((string) $row['jornada'],     ENT_QUOTES | ENT_HTML5);
            $dscTime[]    = htmlspecialchars((string) $row['time'],        ENT_QUOTES | ENT_HTML5);
            $scoreTotal[] = (float) $row['score_total'];
            $totalScore  += (int)   $row['score_total'];
        }
    } else {
        $viewError = is_string($result) ? $result : 'Nenhum dado encontrado para os filtros informados.';
    }
}

include 'templates/templateReportJourney.php';
