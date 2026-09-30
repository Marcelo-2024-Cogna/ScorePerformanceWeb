<?php
 include 'controllerScorePerformance.php';
 

class controllerLoadDataTemplate extends controllerScorePerformance{   
    
    private $dataLoad;

    public function __construct()
    {
        $this->dataLoad = new controllerScorePerformance();
    }

    // Função para validar o arquivo CSV
    public function validarTemplate($arquivo, $colunasEsperadas) {
        
        // Armazena os erros encontrados
        $erros = [];

        // Verificar se o arquivo existe
        if (!file_exists($arquivo) || !is_readable($arquivo)) {
            $erros[] = "Arquivo não encontrado ou não pode ser lido.";

        }else{

            // Abrir o arquivo para leitura
            if (($handle = fopen($arquivo, 'r')) !== FALSE) {
                
                // Ler a primeira linha (cabeçalho)
                $cabecalho = fgetcsv($handle);

                // Verificar se o cabeçalho corresponde ao esperado
                if ($cabecalho !== $colunasEsperadas) {
                    $erros[] = "Template inválido. Colunas fora do padrão esperado.";

                } else {
                    
                    // Iterar sobre as linhas de dados
                    $linhaNumero = 1;
                    while (($dados = fgetcsv($handle)) !== FALSE) {
                        $linhaNumero++;

                        // Verificar o número de colunas em cada linha
                        if (count($dados) != count($colunasEsperadas)) {
                            $erros[] = "Erro na linha $linhaNumero: número de colunas incorreto.";
                            continue;
                        }

                        // Validar o conteúdo de cada coluna
                        foreach ($dados as $indice => $valor) {
                            $coluna = $colunasEsperadas[$indice];

                            // validações para as colunas do template
                            if ($coluna == 'ID_JORNADA' && !is_numeric($valor)) {
                                $erros[] = "Erro na linha $linhaNumero: valor inválido na coluna 'ID_JORNADA [não é um número]'.";
                            }
                            if ($coluna == 'ID_TIME' && !is_numeric($valor)) {
                                $erros[] = "Erro na linha $linhaNumero: valor inválido na coluna 'ID_TIME [não é um número]'.";
                            }
                            if ($coluna == 'ID_CATEGORIA' && !is_numeric($valor)) {
                                $erros[] = "Erro na linha $linhaNumero: valor inválido na coluna 'ID_CATEGORIA [não é um número]'.";
                            }
                            if ($coluna == 'ID_METRICA' && !is_numeric($valor)) {
                                $erros[] = "Erro na linha $linhaNumero: valor inválido na coluna 'ID_METRICA [não é um número]'.";
                            }
                            if ($coluna == 'PERIODO' && !is_string($valor)) {
                                $erros[] = "Erro na linha $linhaNumero: valor inválido na coluna 'PERIODO [não é um texto]' .";
                            }
                            if ($coluna == 'SPRINT' && !is_string($valor)) {
                                $erros[] = "Erro na linha $linhaNumero: valor inválido na coluna 'SPRINT [não é um texto]'.";
                            }
                            if ($coluna == 'VALOR' && !is_float($valor)) {
                                $erros[] = "Erro na linha $linhaNumero: valor inválido na coluna 'SPRINT [não é um número]'.";
                            }                            
                            /* Exemplo de validação para a coluna "email" (deve ser um e-mail válido)
                            if ($coluna == 'ID_TIME' && !filter_var($valor, FILTER_VALIDATE_EMAIL)) {
                                $this->erros[] = "Erro na linha $linhaNumero: e-mail inválido na coluna 'ID_TIME'.";
                            }
                            // Exemplo de validação para campos obrigatórios
                            if (empty($valor) && in_array($coluna, ['nome', 'email'])) {
                                $this->erros[] = "Erro na linha $linhaNumero: o campo '$coluna' é obrigatório.";
                            }*/
                        }
                    }
                }
                fclose($handle);
            } else {
                $erros[] = "Erro ao abrir o arquivo.";
            }
        }
        return $erros;
    }
    

    public function lerDadosArquivos($fileTemp, $filesType, $filesName, $extension){
       
        try{
            $viewDataLoad = [];

            //verificar o tipo do arquivo
            $typeFileAllowed = ['text/csv','text/plain','application/vnd.ms-excel','csv','txt'];

            if (!in_array($filesType, $typeFileAllowed) && (!in_array($extension, $typeFileAllowed)))  {
                throw new Exception("Tipo de arquivo não permitido: ".$filesName);

            }else{
                // Validar o arquivo CSV            
                $erros = $this->validarTemplate($fileTemp, ['ID_JORNADA;ID_TIME;ID_CATEGORIA;ID_METRICA;PERIODO;SPRINT;VALOR']);
                
                // Exibir os erros, se houver
                if (!empty($erros)) {
                    $message = "Foram encontrados os seguintes erros:\n";
                    foreach ($erros as $erro) {
                        $message .= " $erro\n";
                    }
                    throw new Exception($message);
                } else {
                    $count = 0;
                    $paramenter = [];
                    set_time_limit(0);

                    //Abre o arquivo para ler e pular a primeira linha do template
                    $file = fopen($fileTemp, "r");
                    if(!$file){
                        throw new Exception("Erro ao abrir o arquivo: ".$filesName);
                    }else{
                        fgets($file);
                        while (!feof($file)) {
                            $linha = fgets($file);
                            $itens[$count] = explode(';', $linha);
                            $count++;
                        }
                        fclose($file);    
                    
                        //Grava a carga de lançamento dos valores e processa a nota da faixa
                        if ($count > 0) {
                            
                            // Prepara as variáveis conforme retorno do arquivo
                            $count = 0;
                            foreach($itens as $key){
                                if(isset($key[0])){
                                    @$paramenter[$count] = [
                                        'id_jornadas' => $key[0],
                                        'id_time'     => $key[1],
                                        'id_categoria'=> $key[2],
                                        'id_metrica'  => $key[3],
                                        'ds_periodo'  => $key[4],
                                        'ds_sprint'   => $key[5],
                                        'valor_regra' => $key[6]];
                                    $count++;
                                }
                            }
                            // Faz o loop de registro de dados conforme array montado
                            foreach($paramenter as $value){
                                if(($value['id_time'] !== '' )&&( $value['valor_regra'] !== '')){
                                    // Chama a função para gravar no banco
                                    $viewDataLoad[] = $this->dataLoad->controllerSetDataScorePerformance($value);
                                }
                            }
                            // removendo mensagens repetidas
                            $viewDataLoad = array_unique($viewDataLoad);
                        }
                    }                    
                }
            }
        }catch(Exception $e){
            return $e->getMessage();
        }
        // removendo valores repetidos
        return $viewDataLoad;
    }
}
?>
