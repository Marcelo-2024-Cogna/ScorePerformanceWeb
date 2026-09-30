<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="120">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Score Performance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/templates.css">
</head>

<body>
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
        <h1 class="my-2">Score Performance</h1>
        <h4>Lançamento de valores unitariamente</h4>
        <form id="formLancamento" action="viewScorePerformance.php" method="post">
            <table class="table table-bordered celula_tabela">
                <thead>
                    <tr>
                        <th class="celula_tabela_texto">Jornada</th>
                        <th class="celula_tabela_texto">Time</th>
                        <th class="celula_tabela_texto">Período [PI X/AA]</th>
                        <th class="celula_tabela_texto">Sprint</th>
                        <th class="celula_tabela_texto">Categoria</th>
                        <th class="celula_tabela_texto">Metrica</th>
                        <th class="celula_tabela_texto">Valor</th>
                        <th class="celula_tabela_objeto">Gravar Informações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="celula_tabela_texto"><?php print isset($viewDataContentJourney) ? $viewDataContentJourney : 'Error'; ?></td>
                        <td class="celula_tabela_texto"><?php print isset($viewDataContentSquads) ? $viewDataContentSquads : 'Error'; ?></td>
                        <td class="celula_tabela_texto"><input type='text' class="form-control" id='ds_periodo' name='ds_periodo' required></td>
                        <td class="celula_tabela_texto"><input type='text' class="form-control" id='ds_sprint' name='ds_sprint' required></td>
                        <td class="celula_tabela_texto"><?php print isset($viewDataContentCategories) ? $viewDataContentCategories : 'Error'; ?></td>
                        <td class="celula_tabela_texto"><?php print isset($viewDataContentMetrics) ? $viewDataContentMetrics : 'Error'; ?></td>
                        <td class="celula_tabela_texto"><input type="number" step="0.001" id='valor_regra' name='valor_regra' onblur="formatDecimal(this)" class="form-control"></td>
                        <td class="celula_tabela_objeto"><button type="submit" class="btn btn-warning">Enviar dados</button></td>
                    </tr>
                </tbody>
            </table>
            <input type='hidden' id='dataProcess' name='dataProcess' value='true'>
        </form>
        <br><h4>Lançamento de valores em lote</h4>
        <form id="formLancamentoMassa" action="viewScorePerformanceLoad.php" method="post" enctype="multipart/form-data">
            <p style="font-size: 15px; width: 1500px;"><label for="arquivo">Selecione o template preenchido</label></p>
            <input type="file" name="arquivo" id="arquivo" class="btn btn-secondary" required>
            <input type="submit" value="Enviar arquivo" class="btn btn-warning">
            <input type='hidden' id='dataLoad' name='dataLoad' value='true'>
        </form>
        <br><h4>Instrução de preenchimento</h4>
        <button id="download-zip" class="btn btn-primary">Baixar template e instrução</button>
    </div>    
    <footer class="container" style="font-size: 10px; width: 1500px;">
        <hr style="font-size: 20px;">
        <p>&copy; <?php print date("Y"); ?> Score Performance - Uso interno.</p>
        <p>Developer team | <a href="mailto:TeamDeliverys@kroton.onmicrosoft.com>">Delivery Managers</a></p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
                    console.log('Dados formatados com sucesso');
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
        // script para baixar arquivo PDF
        document.getElementById('download-zip').addEventListener('click', function() {
            const link = document.createElement('a');
            link.href = 'assets/templateScorePerformance/templateScorePerformance.rar';
            link.download = 'templateScorePerformance.rar';
            link.click();
        });
    </script>
</body>
</html>