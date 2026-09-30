<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="300">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Score Performance</title>    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/templates.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top color2">
        <div class="container-fluid">
            <a class="navbar-brand color2" href="../index.php">Score Performance</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link color2" href="../system/viewRegras.php">REGRAS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link color2" href="../system/viewFaixasDetalhadas.php">FAIXAS DETALHADAS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link color2" href="../system/viewFaixasOrganizadas.php">FAIXAS ORGANIZADAS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link color2" href="../system/viewLancarValores.php">LANÇAR VALORES</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link color2" href="../system/viewScorePerformance.php">VALORES PROCESSADOS</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link color3 dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            SCORE PERFORMANCE
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="../system/viewScorePerformanceReportJourney.php">VALORES POR JORNADA</a></li>
                            <li><a class="dropdown-item" href="../system/viewScorePerformanceReportTeam.php">VALORES POR TIME</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container table-container" style="margin-bottom: 100px; margin-top: 30px;">
        <h1 class="my-2">Score Performance</h1><br>
        <p style='font-size: 25px; text-align: left; color: DarkViolet;'><strong>Pesquisar dados por Time</strong></p>
        <form id="formDataSearch" action="viewScorePerformanceReportTeam.php" method="post">
            <table class="table table-bordered celula_tabela">
                <thead>
                    <tr>
                        <th class="celula_tabela_texto">Jornada</th>
                        <th class="celula_tabela_texto">Time</th>
                        <th class="celula_tabela_texto">Período</th>
                        <th class="celula_tabela_texto">Sprint</th>
                        <th class="celula_tabela_objeto">Pesquisar Informações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="celula_tabela_texto"><?php print isset($viewDataContentJourney) ? $viewDataContentJourney : 'Error'; ?></td>
                        <td class="celula_tabela_texto"><?php print isset($viewDataContentSquads) ? $viewDataContentSquads : 'Error'; ?></td>
                        <td class="celula_tabela_texto"><input type='text' class="form-control" id='ds_periodo' name='ds_periodo'></td>
                        <td class="celula_tabela_texto"><input type='text' class="form-control" id='ds_sprint' name='ds_sprint'></td>
                        <td class="celula_tabela_objeto"><button type="submit" id="btnChart" class="btn btn-warning">Processar parâmetros</button></td>
                    </tr>
                </tbody>
            </table>
            <input type='hidden' id='dataProcessReportTeam' name='dataProcessReportTeam' value='true'>
        </form>
        <br><?php print (isset($viewData)) ? $viewData : '';?><br>
    </div>
    <footer class="container" style="font-size: 10px; width: 1500px;">
        <hr style="font-size: 20px;">
        <p>&copy; <?php print date("Y"); ?> Score Performance - Uso interno.</p>
        <p>Developer team | <a href="mailto:TeamDeliverys@kroton.onmicrosoft.com>">Delivery Managers</a></p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Executar a função ao carregar a página
        window.onload = createChart;
        function createChart() {
            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                 // Tipos disponíveis: line, bar, pie, etc.
                type: 'line',
                data: {
                    // Apenas uma vez
                    labels: <?php echo (isset($metaIdeal) ? json_encode($metaIdeal) : ''); ?>,
                    datasets: [{
                            label: "Métrica Ideal",
                            data: <?php echo (isset($notaIdeal) ? json_encode($notaIdeal) : ''); ?>,
                            borderColor: "rgba(75, 192, 192, 1)",
                            backgroundColor: "rgba(138, 43, 226, 0)",
                            pointBackgroundColor: 'rgba((75, 192, 192, 1)',
                            borderWidth: 1
                        },
                        {
                            label: <?php echo (isset($dscTime) ? json_encode($dscTime) : ''); ?>,
                            data: <?php echo (isset($notaTime) ? json_encode($notaTime) : ''); ?>,
                            fill: true,
                            borderColor: "rgba(153, 102, 255, 1)",
                            backgroundColor: "rgba(153, 102, 255, 0)",
                            pointBackgroundColor: 'rgba(198, 136, 198, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    animation: true,
                    scales: {
                        y: {
                            min: 0,
                            max: 250,
                            ticks: {
                                font: {
                                    size: 13
                                }
                            }
                        }
                    }
                }
            });
        };
    </script>
</body>

</html>