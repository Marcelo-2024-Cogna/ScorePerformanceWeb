<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="120">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Score Performance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/templates.css">
</head>
<body>
    <?php include __DIR__ . '/partials/navbar.php'; ?>

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
                        <th class="celula_tabela_texto">Métrica</th>
                        <th class="celula_tabela_texto">Valor</th>
                        <th class="celula_tabela_objeto">Gravar Informações</th>
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
                        <td class="celula_tabela_texto"><input type="text" class="form-control" id="ds_periodo" name="ds_periodo" required></td>
                        <td class="celula_tabela_texto"><input type="text" class="form-control" id="ds_sprint"  name="ds_sprint"  required></td>
                        <td class="celula_tabela_texto">
                            <select class="form-control" id="id_categoria" name="id_categoria" required>
                                <option value="" disabled selected>Escolha...</option>
                                <?php foreach ($categories as $c): ?>
                                    <option value="<?= htmlspecialchars((string)$c['id_categoria'], ENT_QUOTES|ENT_HTML5) ?>">
                                        <?= htmlspecialchars((string)$c['descricao'], ENT_QUOTES|ENT_HTML5) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td class="celula_tabela_texto">
                            <select class="form-control" id="id_metrica" name="id_metrica" required>
                                <option value="" disabled selected>Escolha...</option>
                                <?php foreach ($metrics as $m): ?>
                                    <option value="<?= htmlspecialchars((string)$m['id_metrica'], ENT_QUOTES|ENT_HTML5) ?>">
                                        <?= htmlspecialchars((string)$m['descricao'], ENT_QUOTES|ENT_HTML5) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td class="celula_tabela_texto">
                            <input type="number" step="0.001" id="valor_regra" name="valor_regra"
                                   onblur="formatDecimal(this)" class="form-control">
                        </td>
                        <td class="celula_tabela_objeto">
                            <button type="submit" class="btn btn-warning">Enviar dados</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <input type="hidden" id="dataProcess" name="dataProcess" value="true">
        </form>

        <br><h4>Lançamento de valores em lote</h4>
        <form id="formLancamentoMassa" action="viewScorePerformanceLoad.php" method="post" enctype="multipart/form-data">
            <p style="font-size: 15px;"><label for="arquivo">Selecione o template preenchido</label></p>
            <input type="file" name="arquivo" id="arquivo" class="btn btn-secondary" required>
            <input type="submit" value="Enviar arquivo" class="btn btn-warning">
        </form>

        <br><h4>Instrução de preenchimento</h4>
        <button id="download-zip" class="btn btn-primary">Baixar template e instrução</button>
    </div>

    <?php include __DIR__ . '/partials/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function formatDecimal(input) {
        let value = parseFloat(input.value.replace(',', '.'));
        if (isNaN(value) || value < 0) {
            input.value = value < 0 ? 0 : '';
        } else {
            input.value = value.toFixed(3);
        }
    }
    document.getElementById('download-zip').addEventListener('click', function () {
        const link = document.createElement('a');
        link.href = 'assets/templateScorePerformance/templateScorePerformance.rar';
        link.download = 'templateScorePerformance.rar';
        link.click();
    });
    </script>
</body>
</html>