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
        <p style="font-size:25px; color:DarkViolet;"><strong>Pesquisar dados por Time</strong></p>

        <form id="formDataSearch" action="viewScorePerformanceReportTeam.php" method="post">
            <table class="table table-bordered celula_tabela">
                <thead>
                    <tr>
                        <th class="celula_tabela_texto">Jornada</th>
                        <th class="celula_tabela_texto">Time</th>
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
                        <td class="celula_tabela_texto">
                            <select class="form-control" id="id_time" name="id_time" required>
                                <option value="" disabled selected>Escolha...</option>
                                <?php foreach ($squads as $s): ?>
                                    <option value="<?= htmlspecialchars((string)$s['id_time'], ENT_QUOTES|ENT_HTML5) ?>">
                                        <?= htmlspecialchars((string)$s['descricao'], ENT_QUOTES|ENT_HTML5) ?>
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
            <input type="hidden" id="dataProcessReportTeam" name="dataProcessReportTeam" value="true">
        </form>

        <?php if (!empty($viewError)): ?>
            <p class="text-danger" style="font-size:15px;"><?= htmlspecialchars($viewError, ENT_QUOTES|ENT_HTML5) ?></p>
        <?php endif; ?>

        <?php if (!empty($reportData)): ?>
        <h3>Comparativo entre métrica esperada e métrica do time</h3>
        <div class="container">
            <div class="container chart-container" style="border-style:none;">
                <canvas id="myChart"></canvas>
            </div><br>
            <div style="border-style:none;">
                <p style="font-size:25px; color:DarkViolet;"><strong>Dados detalhados do <?= htmlspecialchars($dscTime, ENT_QUOTES|ENT_HTML5) ?></strong></p>
                <table class="table table-striped" style="font-size:12px;">
                    <thead>
                        <tr>
                            <th>Time</th><th>Categoria</th><th>Métrica</th>
                            <th>Sprint</th><th>Valor</th><th>Faixa</th><th>Nota</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($reportData as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars((string)($row['time']      ?? ''), ENT_QUOTES|ENT_HTML5) ?></td>
                            <td><?= htmlspecialchars((string)($row['categoria'] ?? ''), ENT_QUOTES|ENT_HTML5) ?></td>
                            <td><?= htmlspecialchars((string)($row['metrica']   ?? ''), ENT_QUOTES|ENT_HTML5) ?></td>
                            <td><?= htmlspecialchars((string)($row['sprint']    ?? ''), ENT_QUOTES|ENT_HTML5) ?></td>
                            <td><?= htmlspecialchars((string)($row['valor']     ?? ''), ENT_QUOTES|ENT_HTML5) ?></td>
                            <td><?= htmlspecialchars((string)($row['faixa']     ?? ''), ENT_QUOTES|ENT_HTML5) ?></td>
                            <td><?= htmlspecialchars((string)($row['nota']      ?? ''), ENT_QUOTES|ENT_HTML5) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <p style="font-size:25px; text-align:right; color:DarkViolet;">
                    <strong>Score total: <?= htmlspecialchars((string)$notafinal, ENT_QUOTES|ENT_HTML5) ?></strong>
                </p>
                <?php include __DIR__ . '/partials/performance_bands.php'; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    window.onload = function () {
        const canvas = document.getElementById('myChart');
        if (!canvas) { return; }
        new Chart(canvas.getContext('2d'), {
            type: 'line',
            data: {
                labels: <?= json_encode($metaIdeal ?? []) ?>,
                datasets: [
                    {
                        label: 'Métrica Ideal',
                        data: <?= json_encode($notaIdeal ?? []) ?>,
                        borderColor: 'rgba(75,192,192,1)',
                        backgroundColor: 'rgba(138,43,226,0)',
                        borderWidth: 1
                    },
                    {
                        label: <?= json_encode($dscTime ?? '') ?>,
                        data: <?= json_encode($notaTime ?? []) ?>,
                        fill: true,
                        borderColor: 'rgba(153,102,255,1)',
                        backgroundColor: 'rgba(153,102,255,0)',
                        borderWidth: 1
                    }
                ]
            },
            options: { animation: true, scales: { y: { min: 0, max: 250 } } }
        });
    };
    </script>
</body>
</html>