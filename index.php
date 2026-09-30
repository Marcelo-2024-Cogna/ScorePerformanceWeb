<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Score Performance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="system/assets/templates.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top color2">
        <div class="container-fluid">
            <a class="navbar-brand color2" href="index.php">Score Performance</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link color2" href="system/viewRegras.php">REGRAS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link color2" href="system/viewFaixasDetalhadas.php">FAIXAS DETALHADAS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link color2" href="system/viewFaixasOrganizadas.php">FAIXAS ORGANIZADAS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link color2" href="system/viewLancarValores.php">LANÇAR VALORES</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link color2" href="system/viewScorePerformance.php">VALORES PROCESSADOS</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link color3 dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            SCORE PERFORMANCE
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="system/viewScorePerformanceReportJourney.php">VALORES POR JORNADA</a></li>
                            <li><a class="dropdown-item" href="system/viewScorePerformanceReportTeam.php">VALORES POR TIME</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container table-container" style="margin-bottom: 100px; margin-top: 50px;">
        <h1 class="my-2">Score Performance</h1>
        <p style="font-size: 20px; width: 1500px;">
            Bem-vindo ao Painel de maturidade dos times da Engenharia Cogna.<br>
            Essa ferramenta tem como objetivo fornecer indicadores de desempenho das Jornadas e dos Times que compõem essas Jornadas.<br>
            Veja na imagem abaixo como pensamos nessas métricas.<br><br>

            <button id="download-pdf" class="btn btn-primary">Baixar plano explicativo</button>
            <hr style="font-size: 20px; width: 1290px;">
        </P>
        <div><canvas id="pdf-viewer" style="border: 1px solid #f3f3f3;"></canvas></div>
    </div>

    <footer class="container" style="font-size: 10px; width: 1500px;">
        <hr style="font-size: 20px;">
        <p>&copy; <?php print date("Y"); ?> Score Performance - Uso interno.</p>
        <p>Developer team | <a href="mailto:TeamDeliverys@kroton.onmicrosoft.com>">Delivery Managers</a></p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.14.305/pdf.min.js"></script>
    <script>
        // script para exibir arquivo PDF
        const url = 'system/assets/ScorePerformance.pdf';
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.14.305/pdf.worker.min.js';
        pdfjsLib.getDocument(url).promise.then(pdf => {
            pdf.getPage(1).then(page => {
                const scale = 0.3;
                const viewport = page.getViewport({
                    scale
                });
                const canvas = document.getElementById('pdf-viewer');
                const context = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;
                const renderContext = {
                    canvasContext: context,
                    viewport: viewport
                };
                page.render(renderContext);
            });
        });

        // script para baixar arquivo PDF
        document.getElementById('download-pdf').addEventListener('click', function() {
            const link = document.createElement('a');
            link.href = 'system/assets/ScorePerformance.pdf';
            link.download = 'ScorePerformance.pdf';
            link.click();
        });
    </script>
</body>

</html>