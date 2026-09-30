<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
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
    <div class="container table-container" style="margin-bottom: 100px; margin-top: 50px;"> 
        <h1 class="my-2">Score Performance</h1>
        <p style="font-size: 20px; width: 1500px; color: #CD5646FF;"><?php print (isset($viewSetData)) ? $viewSetData : ""; ?></p>
        <input type='text' id='search' class='form-control mb-2' placeholder='Pesquisar...'>
        <?php 
            session_start();
            if(isset($_SESSION['dataLoad'])){
                print "<p style='font-size: 20px; width: 1500px; color: #6300BEFF;'>Processamento em Lote</p>";
                $dataLoad = $_SESSION['dataLoad'];
                if(is_array($dataLoad)){
                    // loop para gerar a lista dos dados carregados por lote.
                    foreach($dataLoad as $key => $value){
                        print "<p style='font-size: 15px; width: 1500px; color: black;'>".$dataLoad[$key]."</p>";
                    }
                }else{
                    print "<p style='font-size: 15px; width: 1500px; color: #CD5646FF;'>".$dataLoad."</p>";
                }
            }
            session_unset();
            print (isset($viewContent)) ? $viewContent : "<p style='font-size: 15px; width: 1500px; color: #CD5646FF;'>Sem dados para impressão</p>";
        ?>
        <br><button class="btn btn-secondary" onclick="geraPDF('<?php print $view; ?>')">Lista completa em PDF</button>
    </div>
    <footer class="container" style="font-size: 10px; width: 1500px;">
        <hr style="font-size: 20px;">
        <p>&copy; <?php print date("Y"); ?> Score Performance - Uso interno.</p>
        <p>Developer team | <a href="mailto:TeamDeliverys@kroton.onmicrosoft.com>">Delivery Managers</a></p>
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Filtragem da tabela
            $('#search').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('table tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            // Ordenação das colunas
            $('.sortable').on('click', function() {
                var index = $(this).index();
                var rows = $('table tbody tr').get();

                rows.sort(function(a, b) {
                    var A = $(a).children('td').eq(index).text().toUpperCase();
                    var B = $(b).children('td').eq(index).text().toUpperCase();

                    if (A < B) {
                        return -1;
                    }
                    if (A > B) {
                        return 1;
                    }
                    return 0;
                });

                $.each(rows, function(index, row) {
                    $('table tbody').append(row);
                });
            });
        });

        function geraPDF(view) {
            // Encode a string para garantir que caracteres especiais sejam tratados corretamente
            const stringCodificada = encodeURIComponent(view);
            // Aqui, redirecionamos para uma nova página e passamos o valor booleano como um parâmetro da URL
            window.location.href = `viewScorePerformacePrintPDF.php?PDF=${stringCodificada}`;
        }
    </script>
</body>

</html>