<?php
include_once 'database/DatabaseConnection.php';
class modelFaixasOrganizadas {

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
    public function buscaDadosFaixasOrg()
    {
        $recordSet = null;
        try {
            $querySelect = "select faixa,
                                flag_inicial,
                                flag_final,
                                nota,
                                percentual,
                                categoria,
                                metrica
                            from painel_faixas_org";
            $stmt = $this->pdo->query($querySelect);
            $recordSet =  $stmt->fetchAll();
            if (!is_array($recordSet)) {
                throw new Exception("Erro na pesquisa das faixas organizadas.");
            }
        } catch (Exception $e) {
            $recordSet = 'Object buscaDadosFaixasOrg: ' . $e->getMessage();
        }   
        return $recordSet;
    }
}
?>