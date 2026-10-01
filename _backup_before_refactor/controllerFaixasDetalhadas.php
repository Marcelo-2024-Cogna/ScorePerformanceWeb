<?php
include 'modelFaixasDetalhadas.php';
class controllerFaixasDetalhadas {

    // Objeto data model
    private $dataModelFxDet;
    private $dataModelFxIde;

    public function __construct()
    {
        // Retorna objeto de dados
        $model = new modelFaixasDetalhadas();
        $this->dataModelFxDet = $model->buscaDadosFaixasDet();
        $this->dataModelFxIde = $model->buscaDadosFaixasIdeal();
    }

    /**
     * @method Buscar dados do objeto consulta
     * @version 1.0.1
     * */ 
    public function DadosFaixasDetalhadas()
    {
        $resultSet = null;
        try {
            $resultSet = $this->dataModelFxDet;
            if (!is_array($resultSet)) {
                throw new Exception("Erro na processamento da pesquisa: ". $resultSet);  
            }
        } catch (Exception $e) {
            $resultSet = 'Object setDataView: ' . $e->getMessage();
        }   
        return $resultSet;        
    }

    /**
     * @method Buscar dados do objeto consulta
     * @version 1.0.1
     * */ 
    public function DadosFaixasDetalhadasIdeais()
    {
        $resultSet = null;
        try {
            $resultSet = $this->dataModelFxIde;
            if (!is_array($resultSet)) {
                throw new Exception("Erro na processamento da pesquisa das Faixas Ideais: ". $resultSet);  
            }
        } catch (Exception $e) {
            $resultSet = 'Object setDataView: ' . $e->getMessage();
        }   
        return $resultSet;        
    }    
}
?>