<?php
include 'modelFaixasOrganizadas.php';
class controllerFaixasOrganizadas {

     // Objeto data model
    private $dataModel;

    public function __construct()
    {
        // Retorna objeto de dados
        $model = new modelFaixasOrganizadas();
        $this->dataModel = $model->buscaDadosFaixasOrg();
    }

    /**
     * @method Buscar dados do objeto View
     * @version 1.0.1
     * */ 
    public function DadosFaixasOrganizadas()
    {
        $resultSet = null;
        try {
            $resultSet = $this->dataModel;
            if (!is_array($resultSet)) {
                throw new Exception("Erro na processamento da pesquisa: ". $resultSet);
            }
        } catch (Exception $e) {
            $resultSet = 'Object setDataView: ' . $e->getMessage();
        }   
        return $resultSet;        
    }
}
?>