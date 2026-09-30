<?php
include_once 'database/DatabaseConnection.php';
class modelRegras {

    // Objeto de conexão
    private $pdo;

    public function __construct()
    {
        // Retorna objeto de conexão
        $db = new DatabaseConnection();
        $this->pdo = $db->getPdo();
    }

    /**
     * @method Buscar dados das regras
     * @version 1.0.1
     * */ 
    public function buscaDadosRegras()
    {
        $recordSet = null;
        try {
            $querySelect = "select categoria, 
                                   metricas,
                                   entendimento,
                                   regras,
                                   percentual,
                                   pontuacao,
                                   atualizacao
                              FROM painel_regras";
            $stmt = $this->pdo->query($querySelect);
            $recordSet =  $stmt->fetchAll();
            if (!is_array($recordSet)) {
                throw new Exception("Erro na pesquisa das regras.");
            }
        } catch (Exception $e) {
            $recordSet = 'Object buscaDadosRegras: ' . $e->getMessage();
        }   
        return $recordSet;
    }
}
?>