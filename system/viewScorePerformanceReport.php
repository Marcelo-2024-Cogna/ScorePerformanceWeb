<?php
include 'controllerScorePerformance.php';
$dataLoad = new controllerScorePerformance();

$viewDataContentJourney = $dataLoad->controllerDataJourney();
$viewDataContentSquads = $dataLoad->controllerDataSquads();
$viewDataContentMetrics = $dataLoad->controllerDataMetrics();
$viewDataContentCategories = $dataLoad->controllerDataCategories();

if(isset($_POST['dataProcess']) && ($_POST['dataProcess'] == 'true')){
    //Pesquisar dados conforme filtros de pesquisa
    $viewDataContent = $dataLoad->controllerDataScore($_POST);
    foreach ($viewDataContent as $key) {
        $jornada = $key['jornada'];
        $time = $key['time'];
        $metrica[] = $key['metrica'];
        $nota[] = $key['nota'];
    }
    $labels = $metrica;
    $data = $nota;
}

include 'templates/templateSearch.php';