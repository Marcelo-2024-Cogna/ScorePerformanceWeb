<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Score Performance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/templates.css">
</head>
<body>
    <?php include __DIR__ . '/partials/navbar.php'; ?>

    <div class="container table-container" style="margin-bottom: 100px; margin-top: 50px;">
        <h1 class="my-2">Score Performance</h1>

        <?php if (!empty($saveMessage)): ?>
            <p class="text-success" style="font-size:16px;"><?= htmlspecialchars($saveMessage, ENT_QUOTES | ENT_HTML5) ?></p>
        <?php endif; ?>

        <?php if (!empty($viewError)): ?>
            <p class="text-danger" style="font-size:15px;"><?= htmlspecialchars($viewError, ENT_QUOTES | ENT_HTML5) ?></p>
        <?php endif; ?>

        <?php
        // Resultado de carga em lote (sessão)
        if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
        if (!empty($_SESSION['dataLoad'])) {
            echo "<p style='font-size:16px; color:#6300BE;'><strong>Processamento em Lote</strong></p>";
            $dataLoad = $_SESSION['dataLoad'];
            if (is_array($dataLoad)) {
                foreach ($dataLoad as $msg) {
                    echo "<p style='font-size:14px;'>" . htmlspecialchars((string) $msg, ENT_QUOTES | ENT_HTML5) . "</p>";
                }
            } else {
                echo "<p class='text-danger' style='font-size:14px;'>" . htmlspecialchars((string) $dataLoad, ENT_QUOTES | ENT_HTML5) . "</p>";
            }
            unset($_SESSION['dataLoad']);
        }
        ?>

        <input type="text" id="search" class="form-control mb-2" placeholder="Pesquisar...">

        <?php if (!empty($rows)): ?>
        <h2 class="my-2">
        <?php if ($view === 'Regras'): ?>
            Regras e pontuação
        <?php elseif ($view === 'FaixaDet'): ?>
            Faixas detalhadas
        <?php elseif ($view === 'FaixaOrg'): ?>
            Faixas organizadas
        <?php else: ?>
            Dados Processados
        <?php endif; ?>
        </h2>
        <table class="table table-striped" style="font-size: 12px;">
            <thead>
            <?php if ($view === 'Regras'): ?>
                <tr>
                    <th class="sortable">Categoria</th>
                    <th class="sortable">Métrica</th>
                    <th class="sortable">Regra</th>
                    <th class="sortable">%</th>
                    <th class="sortable">Pontuação</th>
                    <th class="sortable">Atualização</th>
                </tr>
            <?php elseif ($view === 'FaixaDet'): ?>
                <tr>
                    <th class="sortable">Categoria</th>
                    <th class="sortable">Métrica</th>
                    <th class="sortable">Faixa</th>
                    <th class="sortable">Referência</th>
                    <th class="sortable">Pontuação</th>
                    <th class="sortable">% inicial</th>
                    <th class="sortable">% Final</th>
                    <th class="sortable">Nota inicial</th>
                    <th class="sortable">Nota Final</th>
                </tr>
            <?php elseif ($view === 'FaixaOrg'): ?>
                <tr>
                    <th class="sortable">Categoria</th>
                    <th class="sortable">Métrica</th>
                    <th class="sortable">Faixa</th>
                    <th class="sortable">%</th>
                    <th class="sortable">Nota</th>
                </tr>
            <?php else: ?>
                <tr>
                    <th>Jornada</th>
                    <th>Time</th>
                    <th>Período</th>
                    <th>Categoria</th>
                    <th>Métrica</th>
                    <th>Valor</th>
                    <th>Faixa</th>
                    <th>Nota</th>
                </tr>
            <?php endif; ?>
            </thead>
            <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                <?php if ($view === 'Regras'): ?>
                    <td><?= htmlspecialchars((string)($row['categoria']   ?? ''), ENT_QUOTES | ENT_HTML5) ?></td>
                    <td><?= htmlspecialchars((string)($row['metricas']    ?? ''), ENT_QUOTES | ENT_HTML5) ?></td>
                    <td><?= htmlspecialchars((string)($row['regras']      ?? ''), ENT_QUOTES | ENT_HTML5) ?></td>
                    <td><?= htmlspecialchars((string)($row['percentual']  ?? ''), ENT_QUOTES | ENT_HTML5) ?></td>
                    <td><?= htmlspecialchars((string)($row['pontuacao']   ?? ''), ENT_QUOTES | ENT_HTML5) ?></td>
                    <td><?= htmlspecialchars((string)($row['atualizacao'] ?? ''), ENT_QUOTES | ENT_HTML5) ?></td>
                <?php elseif ($view === 'FaixaDet'): ?>
                    <td><?= htmlspecialchars((string)($row['categoria']          ?? ''), ENT_QUOTES | ENT_HTML5) ?></td>
                    <td><?= htmlspecialchars((string)($row['metrica']            ?? ''), ENT_QUOTES | ENT_HTML5) ?></td>
                    <td><?= htmlspecialchars((string)($row['faixa']              ?? ''), ENT_QUOTES | ENT_HTML5) ?></td>
                    <td><?= htmlspecialchars((string)($row['valores_referencia'] ?? ''), ENT_QUOTES | ENT_HTML5) ?></td>
                    <td><?= htmlspecialchars((string)($row['pontuacao_maxima']   ?? ''), ENT_QUOTES | ENT_HTML5) ?></td>
                    <td><?= htmlspecialchars((string)($row['percentual_inicial'] ?? ''), ENT_QUOTES | ENT_HTML5) ?></td>
                    <td><?= htmlspecialchars((string)($row['percentual_final']   ?? ''), ENT_QUOTES | ENT_HTML5) ?></td>
                    <td><?= htmlspecialchars((string)($row['nota_inicial']       ?? ''), ENT_QUOTES | ENT_HTML5) ?></td>
                    <td><?= htmlspecialchars((string)($row['nota_final']         ?? ''), ENT_QUOTES | ENT_HTML5) ?></td>
                <?php elseif ($view === 'FaixaOrg'): ?>
                    <td><?= htmlspecialchars((string)($row['categoria']  ?? ''), ENT_QUOTES | ENT_HTML5) ?></td>
                    <td><?= htmlspecialchars((string)($row['metrica']    ?? ''), ENT_QUOTES | ENT_HTML5) ?></td>
                    <td><?= htmlspecialchars((string)($row['faixa']      ?? ''), ENT_QUOTES | ENT_HTML5) ?></td>
                    <td><?= htmlspecialchars((string)($row['percentual'] ?? ''), ENT_QUOTES | ENT_HTML5) ?></td>
                    <td><?= htmlspecialchars((string)($row['nota']       ?? ''), ENT_QUOTES | ENT_HTML5) ?></td>
                <?php else: ?>
                    <td><?= htmlspecialchars((string)($row['jornada']   ?? ''), ENT_QUOTES | ENT_HTML5) ?></td>
                    <td><?= htmlspecialchars((string)($row['time']      ?? ''), ENT_QUOTES | ENT_HTML5) ?></td>
                    <td><?= htmlspecialchars((string)($row['periodo']   ?? ''), ENT_QUOTES | ENT_HTML5) ?></td>
                    <td><?= htmlspecialchars((string)($row['categoria'] ?? ''), ENT_QUOTES | ENT_HTML5) ?></td>
                    <td><?= htmlspecialchars((string)($row['metrica']   ?? ''), ENT_QUOTES | ENT_HTML5) ?></td>
                    <td><?= htmlspecialchars((string)($row['valor']     ?? ''), ENT_QUOTES | ENT_HTML5) ?></td>
                    <td><?= htmlspecialchars((string)($row['faixa']     ?? ''), ENT_QUOTES | ENT_HTML5) ?></td>
                    <td><?= htmlspecialchars((string)($row['nota']      ?? ''), ENT_QUOTES | ENT_HTML5) ?></td>
                <?php endif; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <button class="btn btn-secondary" onclick="geraPDF('<?= htmlspecialchars($view ?? '', ENT_QUOTES | ENT_HTML5) ?>')">Lista completa em PDF</button>
        <?php else: ?>
            <?php if (empty($viewError)): ?>
                <p style="color:#CD5646; font-size:15px;">Sem dados para impressão</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/partials/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function () {
        $('#search').on('keyup', function () {
            var value = $(this).val().toLowerCase();
            $('table tbody tr').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
        $('.sortable').on('click', function () {
            var index = $(this).index();
            var rows = $('table tbody tr').get();
            rows.sort(function (a, b) {
                var A = $(a).children('td').eq(index).text().toUpperCase();
                var B = $(b).children('td').eq(index).text().toUpperCase();
                return A < B ? -1 : A > B ? 1 : 0;
            });
            $.each(rows, function (index, row) { $('table tbody').append(row); });
        });
    });
    function geraPDF(view) {
        window.location.href = 'viewScorePerformacePrintPDF.php?PDF=' + encodeURIComponent(view);
    }
    </script>
</body>
</html>
