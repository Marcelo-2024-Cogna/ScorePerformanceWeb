<?php
include 'controllerScorePerformance.php';
//dados para seleção e confecção do formulário de inserção.
$dataLoad = new controllerScorePerformance();
$viewDataContentJourney = $dataLoad->controllerDataJourney();
$viewDataContentSquads = $dataLoad->controllerDataSquads();
$viewDataContentMetrics = $dataLoad->controllerDataMetrics();
$viewDataContentCategories = $dataLoad->controllerDataCategories();

include 'templates/templateLoad.php';
?>