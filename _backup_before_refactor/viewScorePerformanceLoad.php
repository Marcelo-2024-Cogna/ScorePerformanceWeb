<?php
    session_start();
    // View para validar e ler o template
    try {
        // Verifica se foi feito o upload do arquivo Excel
        if (isset($_FILES['arquivo']['name'])) {
            
            // recebe informações do arquivo
            $typeFileAllowed = ['text/csv','text/plain','application/vnd.ms-excel','csv','txt'];
            $extension = pathinfo($_FILES['arquivo']['name'], PATHINFO_EXTENSION);
            $filesType = $_FILES['arquivo']['type'];
            $filesName = $_FILES['arquivo']['name'];
            $filesTemp = $_FILES['arquivo']['tmp_name'];

            if (in_array($filesType, $typeFileAllowed) || (in_array($extension, $typeFileAllowed))) {

                //Grava a carga de lançamento dos valores e processa a nota da faixa
                include 'controllerLoadDataTemplate.php';
                $loadDataTemplate = new controllerLoadDataTemplate();
                $viewSetData = $loadDataTemplate->lerDadosArquivos($filesTemp, $filesType, $filesName, $extension);

            }else{
                throw new Exception("Tipo de arquivo não permitido: ".$_FILES['arquivo']['name']);
            }
        }else {
            throw new Exception("Erro: Nenhum arquivo enviado.");
        }
    } catch (Exception $e) {
        $viewSetData = $e->getMessage();
    }

    $_SESSION['dataLoad'] = $viewSetData;
    header("Location: viewScorePerformance.php");

?>
