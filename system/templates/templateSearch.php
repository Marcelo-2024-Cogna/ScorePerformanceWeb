<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="120">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Score Performance</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 56px;
        }

        section {
            padding: 60px 0;
        }

        .color2 {
            color: #f3f3f3;
            background-color: #a66af9;
        }

        .chart-container {
            width: 100%;
            height: 400px;
            display:grid; 
            grid-template-columns: 1fr 1fr;
        }

        @media (max-width: 500px) {
            .chart-container {
                height: 200px;
            }
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top color2">
        <div class="container-fluid">
            <a class="navbar-brand color2" href="../index.php">Score Performance</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
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
                        <a class="nav-link color2" href="../system/viewScorePerformance.php">SCORE PERFORMANCE</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link color2" href="../system/viewScorePerformanceReport.php">RELATÓRIOS</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container table-container" style="margin-bottom: 100px; margin-top: 30px; width:1700px;">
        <h1 class="my-2">Score Performance</h1>
        <h2>Pesquisar dados processados</h2>
        <form id="formLancamento" action="viewScorePerformanceReport.php" method="post">
            <table class="table table-bordered" style='font-size: 12px; width: 650px;'>
                <thead>
                    <tr>
                        <th style='font-size: 12px; width: 250px;'>Jornada</th>
                        <th style='font-size: 12px; width: 250px;'>Time</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style='font-size: 12px; width: 250px;'><?php print isset($viewDataContentJourney) ? $viewDataContentJourney : 'Error'; ?></td>
                        <td style='font-size: 12px; width: 250px;'><?php print isset($viewDataContentSquads) ? $viewDataContentSquads : 'Error'; ?></td>
                        <td><button type="submit" class="btn btn-primary">Pesquisar</button></td>
                    </tr>
                </tbody>
            </table>
            <input type='hidden' id='dataProcess' name='dataProcess' value='true'>
        </form><br>
        <!-- Divs onde os gráficos serão desenhados -->

        <?php
        if (!isset($viewDataContent)) {
            print "<p style='font-size: 15px; width: 1500px; color: black;'>Selecione uma Jornada e um time e clique em pesquisar<p>";
        }else{
            print "
            <h2>Dados processados: ". $jornada ."</h2>
            <div class='chart-container'>
                <div style='border-style: none;'>
                    <canvas id='myChart'></canvas>
                </div>
                <div style='border-style: none;'>
                    <table class='table table-striped' style='font-size: 12px;'>
                    <thead>
                        <tr>
                            <th >Categoria</th>
                            <th >Métrica</th>
                            <th >Valor</th>
                            <th >Faixa</th>                        
                            <th >Nota</th>
                        </tr>
                    </thead>
                    <tbody>";
                    foreach ($viewDataContent as $key) {
                        print "
                        <tr>
                            <td>" . $key['categoria'] . "</td>                        
                            <td>" . $key['metrica'] . "</td>
                            <td>" . $key['valor'] . "</td>
                            <td>" . $key['faixa'] . "</td>
                            <td>" . $key['nota'] . "</td>
                        </tr>";
                    }
                    print "
                    </tbody>
                    </table>
                </div>
            </div>";?>
            <script>
                var ctx = document.getElementById('myChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    // Opções  (line, bar, pie, radar, scatter, bubble, doughnut, polarArea).
                    type: 'line',
                    // Preenchendo dados
                    data: {
                        labels: <?php echo json_encode($labels); ?>,
                        datasets: [{
                            label: <?php echo json_encode($time);?>,
                            data: <?php echo json_encode($data); ?>,
                            fill: true,
                            backgroundColor: 'rgba(198, 136, 198, 0.2)',
                            borderColor: 'rgba(91, 37, 93, 1)',
                            pointBackgroundColor: 'rgba(198, 136, 198, 1)',
                            borderWidth: 1
                        }]
                    },
                    // Formantando área do gráfico
                    options: {
                        animation: true,
                        scales: {
                            y: {
                                min: 0,
                                max: 250,
                                ticks: {
                                    font: {
                                        size: 10
                                    }
                                }
                            }
                        }
                    }
                });
                console.log("Gráfico gerado com sucesso");
            </script>
        <?php
        }
        ?>
    </div>
    <footer class="container" style="font-size: 10px; width: 1500px;">
        <hr style="font-size: 20px;">
        <p>&copy; <?php print date("Y"); ?> Score Performance - Uso interno.</p>
        <p>Developer team | <a href="mailto:TeamDeliverys@kroton.onmicrosoft.com>">Delivery Managers</a></p>
    </footer>
</body>

</html>