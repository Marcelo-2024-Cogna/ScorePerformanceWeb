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
    </style>
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
    <div class="container table-container" style="margin-bottom: 100px; margin-top: 30px;">
        <h1 class="my-2">Score Performance</h1>
        <h2>Lançamento valores unitariamente</h2>
        <form id="formLancamento" action="viewScorePerformance.php" method="post">
            <table class="table table-bordered" style='font-size: 12px; width: 1300px;'>
                <thead>
                    <tr>
                        <th style='font-size: 12px; width: 250px;'>Jornada</th>
                        <th style='font-size: 12px; width: 250px;'>Time</th>
                        <th>Período</th>
                        <th>Sprint</th>
                        <th style='font-size: 12px; width: 250px;'>Categoria</th>
                        <th style='font-size: 12px; width: 250px;'>Metrica</th>
                        <th>Valor</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style='font-size: 12px; width: 250px;'><?php print isset($viewDataContentJourney) ? $viewDataContentJourney : 'Error'; ?></td>
                        <td style='font-size: 12px; width: 250px;'><?php print isset($viewDataContentSquads) ? $viewDataContentSquads : 'Error'; ?></td>
                        <td style='font-size: 12px; width: 250px;'><input type='text' id='ds_periodo' name='ds_periodo' class="form-control"></td>
                        <td style='font-size: 12px; width: 250px;'><input type='text' id='ds_sprint' name='ds_sprint' class="form-control"></td>
                        <td style='font-size: 12px; width: 250px;'><?php print isset($viewDataContentCategories) ? $viewDataContentCategories : 'Error'; ?></td>
                        <td style='font-size: 12px; width: 250px;'><?php print isset($viewDataContentMetrics) ? $viewDataContentMetrics : 'Error'; ?></td>
                        <td style='font-size: 12px; width: 250px;'><input type="number" step="0.001" id='valor_regra' name='valor_regra' onblur="formatDecimal(this)" class="form-control"></td>
                        <td><button type="submit" class="btn btn-primary">Processar</button></td>
                    </tr>
                </tbody>
            </table>
            <input type='hidden' id='dataProcess' name='dataProcess' value='true'>
        </form>
        <br>
        <h2>Lançamento valores em lote</h2>
        <form id="formLancamentoMassa" action="viewScorePerformanceLoad.php" method="post" enctype="multipart/form-data">
            <p style="font-size: 20px; width: 1500px;"><label for="arquivo">Selecione o arquivo texto</label></p>
            <input type="file" name="arquivo" id="arquivo" required>
            <br><br>
            <input type="submit" value="Enviar arquivo" class="btn btn-primary">
            <input type='hidden' id='dataLoad' name='dataLoad' value='true'>
        </form>
    </div>
    <footer class="container" style="font-size: 10px; width: 1500px;">
        <hr style="font-size: 20px;">
        <p>&copy; <?php print date("Y"); ?> Score Performance - Uso interno.</p>
        <p>Developer team | <a href="mailto:TeamDeliverys@kroton.onmicrosoft.com>">Delivery Managers</a></p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        // Método para formatar valores em decimal/float
        function formatDecimal(input) {
            // Converte para número decimal
            let value = parseFloat(input.value.replace(',', '.'));
            if (value > -1) {
                if (!isNaN(value)) {
                    // Formata com 3 casas decimais
                    input.value = value.toFixed(3);
                    console.log('Dados formatdos com sucesso');
                } else {
                    // Limpa o campo se o valor for inválido
                    input.value = '';
                    console.log('Erro ao formatar número');
                }                
            } else {
                input.value = 0;
                console.log('Número informado é negativo');
            }
        }
    </script>
</body>
</html>