?php

declare(strict_types=1);

session_start();

include_once 'controllerLoadDataTemplate.php';

$result = '';

try {
    if (!isset($_FILES['arquivo']['name']) || $_FILES['arquivo']['error'] !== UPLOAD_ERR_OK) {
        throw new \RuntimeException('Nenhum arquivo enviado ou erro no upload.');
    }

    $extension = strtolower(pathinfo($_FILES['arquivo']['name'], PATHINFO_EXTENSION));
    $filesType = $_FILES['arquivo']['type'];
    $filesName = $_FILES['arquivo']['name'];
    $filesTemp = $_FILES['arquivo']['tmp_name'];

    $loader = new controllerLoadDataTemplate();
    $result = $loader->lerDadosArquivos($filesTemp, $filesType, $filesName, $extension);

} catch (\RuntimeException $e) {
    $result = $e->getMessage();
}

$_SESSION['dataLoad'] = $result;

header('Location: viewScorePerformance.php');
exit;
