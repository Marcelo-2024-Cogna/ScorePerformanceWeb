?php

declare(strict_types=1);

include_once 'controllerScorePerformance.php';

$controller = new controllerScorePerformance();

// Arrays para construção dos <select> no template
$journeys   = $controller->getJourneys();
$squads     = $controller->getSquads();
$metrics    = $controller->getMetrics();
$categories = $controller->getCategories();

include 'templates/templateLoad.php';
