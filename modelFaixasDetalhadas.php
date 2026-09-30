<?php
include_once 'database/DatabaseConnection.php';
class modelFaixasDetalhadas {

    // Objeto de conexão
    private $pdo;

    public function __construct()
    {
        // Retorna objeto de conexão
        $db = new DatabaseConnection();
        $this->pdo = $db->getPdo();
    }

    /**
     * @method Buscar dados das views
     * @version 1.0.1
     * */ 
    public function buscaDadosFaixasDet()
    {
        $recordSet = null;
        try {
            $querySelect = "select faixa,
                                   valores_referencia,
                                   pontuacao_maxima,
                                   percentual_inicial,
                                   percentual_final,
                                   nota_inicial,
                                   nota_final,
                                   categoria,
                                   metrica
                            from painel_faixas_det";
            $stmt = $this->pdo->query($querySelect);
            $recordSet =  $stmt->fetchAll();
            if (!is_array($recordSet)) {
                throw new Exception("Erro na pesquisa das faixas detalhadas.");
            }
        } catch (Exception $e) {
            $recordSet = 'Object buscaDadosFaixasDet: ' . $e->getMessage();
        }   
        return $recordSet;
    }

    /**
     * @method Buscar dados das views: somente Atendeu Totalmente
     * @version 1.0.1
     * */ 
    public function buscaDadosFaixasIdeal()
    {
        $recordSet = null;
        try {
            $querySelect = "SELECT fx.metrica, fx.nota_inicial
                            FROM painel_faixas_det fx
                            WHERE lower(fx.faixa) = lower('atendeu totalmente')";
            $stmt = $this->pdo->query($querySelect);
            $recordSet =  $stmt->fetchAll();
            if (!is_array($recordSet)) {
                throw new Exception("Erro na pesquisa das Faixas Atendidas Totalmente.");
            }
        } catch (Exception $e) {
            $recordSet = 'Object buscaDadosFaixasIdeal: ' . $e->getMessage();
        }   
        return $recordSet;
    }   
}
?>