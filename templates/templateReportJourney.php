<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="300">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Score Performance</title>
    <!-- Bootstrap CSS -->
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
        <p style='font-size: 25px; text-align: left; color: DarkViolet;'><strong>Pesquisar dados por Jornada</strong></p>
        <form id="formDataSearch" action="viewScorePerformanceReportJourney.php" method="post">
            <table class="table table-bordered celula_tabela">
                <thead>
                    <tr>
                        <th class="celula_tabela_texto">Jornada</th>
                        <th class="celula_tabela_texto">Período</th>
                        <th class="celula_tabela_texto">Sprint</th>
                        <th class="celula_tabela_objeto">Pesquisar Informações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="celula_tabela_texto"><?php print isset($viewDataContentJourney) ? $viewDataContentJourney : 'Error'; ?></td>
                        <td class="celula_tabela_texto"><input type='text' class="form-control" id='ds_periodo' name='ds_periodo'></td>
                        <td class="celula_tabela_texto"><input type='text' class="form-control" id='ds_sprint' name='ds_sprint'></td>
                        <td class="celula_tabela_objeto"><button type="submit" id="btnChart" class="btn btn-warning">Processar parâmetros</button></td>
                    </tr>
                </tbody>
            </table>
            <input type='hidden' id='dataProcessReportJourney' name='dataProcessReportJourney' value='true'>
        </form>
        <br><?php print (isset($viewData)) ? $viewData : '';?><br>
    </div>
    <footer class="container" style="font-size: 10px; width: 1500px;">
        <hr style="font-size: 20px;">
        <p>&copy; <?php print date("Y"); ?> Score Performance - Uso interno.</p>
        <p>Developer team | <a href="mailto:TeamDeliverys@kroton.onmicrosoft.com>">Delivery Managers</a></p>
    </footer>
    <!-- Script do Bootstrap JS e Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Script para adicionar gráficos de radar com Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Função para criar gráficos de radar
        function criarGraficoRadar(ctx, totais, times, rotulo) {
            new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: times,
                    datasets: [{
                        label: '',
                        data: totais,
						fill: true,
						borderColor: "rgba(153, 102, 255, 1)",
                        backgroundColor: "rgba(153, 102, 255, 0.3)",
                        pointBackgroundColor: 'rgba(198, 136, 198, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
					animation: true,
                    scales: {
                        r: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
		// Configuração para rótulos dos gráficos: 
        // Informção de rótulo        
        const labels = <?php echo (isset($dscTime) ? json_encode($dscTime) : ''); ?>
		//const label = ['Jornada Administrativa'];
        const label = <?php echo (isset($jornada) ? json_encode($jornada) : ''); ?>
        //const dados = [313, 225, 433, 0, 50, 1004];
		const dados = <?php echo (isset($score_total) ? json_encode($score_total) : ''); ?>
        // Criando os gráficos
        criarGraficoRadar(document.getElementById('ScoreJourney').getContext('2d'), dados, labels, label);
    </script>
</body>
</html>
