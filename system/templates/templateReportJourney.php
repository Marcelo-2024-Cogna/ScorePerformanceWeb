<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="300">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Score Performance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/templates.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include __DIR__ . '/partials/navbar.php'; ?>

    <div class="container table-container" style="margin-bottom:100px; margin-top:30px;">
        <h1 class="my-2">Score Performance</h1><br>
        <p style="font-size:25px; color:DarkViolet;"><strong>Pesquisar dados por Jornada</strong></p>

        <form id="formDataSearch" action="viewScorePerformanceReportJourney.php" method="post">
            <table class="table table-bordered celula_tabela">
                <thead>
                    <tr>
                        <th class="celula_tabela_texto">Jornada</th>
                        <th class="celula_tabela_texto">Período</th>
                        <th class="celula_tabela_texto">Sprint</th>
                        <th class="celula_tabela_objeto">Pesquisar</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="celula_tabela_texto">
                            <select class="form-control" id="id_jornadas" name="id_jornadas" required>
                                <option value="" disabled selected>Escolha...</option>
                                <?php foreach ($journeys as $j): ?>
                                    <option value="<?= htmlspecialchars((string)$j['id_jornadas'], ENT_QUOTES|ENT_HTML5) ?>">
                                        <?= htmlspecialchars((string)$j['descricao'], ENT_QUOTES|ENT_HTML5) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td class="celula_tabela_texto"><input type="text" class="form-control" id="ds_periodo" name="ds_periodo"></td>
                        <td class="celula_tabela_texto"><input type="text" class="form-control" id="ds_sprint"  name="ds_sprint"></td>
                        <td class="celula_tabela_objeto">
                            <button type="submit" id="btnChart" class="btn btn-warning">Processar parâmetros</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <input type="hidden" id="dataProcessReportJourney" name="dataProcessReportJourney" value="true">
        </form>

        <?php if (!empty($viewError)): ?>
            <p class="text-danger" style="font-size:15px;"><?= htmlspecialchars($viewError, ENT_QUOTES|ENT_HTML5) ?></p>
        <?php endif; ?>

        <?php if (!empty($reportData)): ?>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($jornada, ENT_QUOTES|ENT_HTML5) ?></h5>
                            <canvas id="ScoreJourney" style="max-height:300px;"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <table class="table table-striped" style="font-size:12px;">
                        <thead>
                            <tr>
                                <th>Ranking</th><th>Times</th><th>Período</th>
                                <th>Sprint</th><th>Score Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($reportData as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars((string)($row['ranking']     ?? ''), ENT_QUOTES|ENT_HTML5) ?></td>
                                <td><?= htmlspecialchars((string)($row['time']        ?? ''), ENT_QUOTES|ENT_HTML5) ?></td>
                                <td><?= htmlspecialchars((string)($row['periodo']     ?? ''), ENT_QUOTES|ENT_HTML5) ?></td>
                                <td><?= htmlspecialchars((string)($row['sprint']      ?? ''), ENT_QUOTES|ENT_HTML5) ?></td>
                                <td><?= htmlspecialchars((string)($row['score_total'] ?? ''), ENT_QUOTES|ENT_HTML5) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <p style="font-size:25px; text-align:right; color:DarkViolet;">
                        <strong>Score total da jornada: <?= htmlspecialchars((string)$totalScore, ENT_QUOTES|ENT_HTML5) ?></strong>
                    </p>
                    <?php include __DIR__ . '/partials/performance_bands.php'; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    (function () {
        const canvas = document.getElementById('ScoreJourney');
        if (!canvas) { return; }
        new Chart(canvas.getContext('2d'), {
            type: 'radar',
            data: {
                labels: <?= json_encode($dscTime ?? []) ?>,
                datasets: [{
                    label: <?= json_encode($jornada ?? '') ?>,
                    data: <?= json_encode($scoreTotal ?? []) ?>,
                    fill: true,
                    borderColor: 'rgba(153,102,255,1)',
                    backgroundColor: 'rgba(153,102,255,0.3)',
                    pointBackgroundColor: 'rgba(198,136,198,1)',
                    borderWidth: 1
                }]
            },
            options: { responsive: true, animation: true, scales: { r: { beginAtZero: true } } }
        });
    })();
    </script>
</body>
</html>