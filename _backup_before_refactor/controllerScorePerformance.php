<?php
include 'modelScorePerformance.php';

class controllerScorePerformance
{

    // Objeto data model
    private $model;
    private $dataModel;
    private $dataModelSquads;
    private $dataModelJourney;
    private $dataModelMetrics;
    private $dataModelCategories;

    public function __construct()
    {
        // Retorna objeto de dados
        $this->model = new modelScorePerformance();
        $this->dataModel = $this->model->dataScorePerformance();
        $this->dataModelSquads = $this->model->dataScorePerformanceSquads();
        $this->dataModelJourney = $this->model->dataScorePerformanceJourney();
        $this->dataModelMetrics = $this->model->dataScorePerformanceMetrics();
        $this->dataModelCategories = $this->model->dataScorePerformanceCategories();
    }

    /**
     * @method Buscar dados do objeto View
     * @version 1.0.1
     * */
    public function controllerDataScore($paramater = null)
    {
        $resultSet = null;
        $searchQuery = null;
        try {
            if(is_array($paramater)){

                // Verificar tipo de pesquisa
                if($paramater["id_jornadas"] != '') {                    
                    if(isset($paramater['id_time']) && $paramater["ds_periodo"] != '' && $paramater["ds_sprint"] != ''){
                        // pesquisa para gráfico de time
                        $searchQuery = 'sql_completa';
                    }
                    elseif(isset($paramater['id_time']) && $paramater["ds_periodo"] != '' && $paramater["ds_sprint"] == ''){
                        // pesquisa para gráfico de time
                        $searchQuery = 'sql_jornada_time_periodo';
                    }
                    elseif(isset($paramater['id_time']) && $paramater["ds_periodo"] == '' && $paramater["ds_sprint"] != ''){
                        // pesquisa para gráfico de time
                        $searchQuery = 'sql_jornada_time_sprint';
                    }                                        
                    elseif(isset($paramater['id_time']) && $paramater["ds_periodo"] == '' && $paramater["ds_sprint"] == ''){
                        // pesquisa para gráfico de time
                        $searchQuery = 'sql_jornada_time';
                    }
                    elseif(!isset($paramater['id_time']) && $paramater["ds_periodo"] != '' && $paramater["ds_sprint"] == ''){
                        // pesquisa para gráfico de Jornada
                        $searchQuery = 'sql_jornada_periodo';
                    }
                    elseif(!isset($paramater['id_time']) && $paramater["ds_periodo"] == '' && $paramater["ds_sprint"] != ''){
                        // pesquisa para gráfico de Jornada
                        $searchQuery = 'sql_jornada_sprint';
                    }                                        
                    elseif(!isset($paramater['id_time']) && $paramater["ds_periodo"] == '' && $paramater["ds_sprint"] == ''){
                        // pesquisa para gráfico de Jornada
                        $searchQuery = 'sql_jornada';
                    }
                }
                
                // Processar pesquisa               
                $resultSet = $this->model->dataScorePerformance($paramater, $searchQuery);
                if ((!is_array($resultSet)) || (sizeof($resultSet) == 0)) {
                    throw new Exception("Dados não encontrados para a pesquisa informada.");
                }
            }else{
                throw new Exception("Erro nos parâmetros de pequisa informados.");
            }
        } catch (Exception $e) {
            $resultSet = 'Objeto controllerDataScore: ' . $e->getMessage();
        }
        return $resultSet;
    }

    /**
     * @method Buscar dados do objeto View
     * @version 1.0.1
     * */
    public function controllerDataJourney()
    {
        $resultSet = null;
        $viewContent = null;
        try {
            $resultSet = $this->dataModelJourney;
            if (!is_array($resultSet)) {
                throw new Exception("Erro na processamento da pesquisa: " . $resultSet);
            } else {
                $viewContent = "
                <select class='form-control' id='id_jornadas' name='id_jornadas' required>
                    <option value='' disabled selected>Escolha...</option>";
                foreach ($resultSet as $row) {
                    $viewContent .= "<option value='" . $row['id_jornadas'] . "'>" . $row['descricao'] . "</option>";
                }
                $viewContent .=
                    "</select>";
            }
        } catch (Exception $e) {
            $viewContent = 'Objeto controllerDataJourney: ' . $e->getMessage();
        }
        return $viewContent;
    }

    /**
     * @method Buscar dados do objeto View
     * @version 1.0.1
     * */
    public function controllerDataSquads()
    {
        $resultSet = null;
        $viewContent = null;

        try {
            $resultSet = $this->dataModelSquads;
            if (!is_array($resultSet)) {
                throw new Exception("Erro na processamento da pesquisa: " . $resultSet);
            } else {
                $viewContent = "
                <select class='form-control' id='id_time' name='id_time' required>
                    <option value='' disabled selected>Escolha...</option>";
                foreach ($resultSet as $row) {
                    $viewContent .= "<option value='" . $row['id_time'] . "'>" . $row['descricao'] . "</option>";
                }
                $viewContent .=
                    "</select>";
            }
        } catch (Exception $e) {
            $viewContent = 'Objeto controllerDataSquads: ' . $e->getMessage();
        }
        return $viewContent;
    }

    /**
     * @method Buscar dados do objeto View
     * @version 1.0.1
     * */
    public function controllerDataMetrics()
    {
        $resultSet = null;
        $viewContent = null;
        try {
            $resultSet = $this->dataModelMetrics;
            if (!is_array($resultSet)) {
                throw new Exception("Erro na processamento da pesquisa: " . $resultSet);
            } else {
                $viewContent = "
                <select class='form-control' id='id_metrica' name='id_metrica' required>
                    <option value='' disabled selected>Escolha...</option>";
                foreach ($resultSet as $row) {
                    $viewContent .= "<option value='" . $row['id_metrica'] . "'>" . $row['descricao'] . "</option>";
                }
                $viewContent .=
                    "</select>";
            }
        } catch (Exception $e) {
            $viewContent = 'Objeto controllerDataMetrics: ' . $e->getMessage();
        }
        return $viewContent;
    }

    /**
     * @method Buscar dados do objeto View
     * @version 1.0.1
     * */
    public function controllerDataCategories()
    {
        $resultSet = null;
        $viewContent = null;
        try {
            $resultSet = $this->dataModelCategories;
            if (!is_array($resultSet)) {
                throw new Exception("Erro na processamento da pesquisa: " . $resultSet);
            } else {
                $viewContent = "
                <select class='form-control' id='id_categoria' name='id_categoria' required>
                    <option value='' disabled selected>Escolha...</option>";
                foreach ($resultSet as $row) {
                    $viewContent .= "<option value='" . $row['id_categoria'] . "'>" . $row['descricao'] . "</option>";
                }
                $viewContent .=
                    "</select>";
            }
        } catch (Exception $e) {
            $viewContent = 'Objeto controllerDataCategories: ' . $e->getMessage();
        }
        return $viewContent;
    }

    /**
     * @method Gravar dados do objeto Score Performance
     * @version 1.0.1
     * */
    public function controllerSetDataScorePerformance($values)
    {
        $Content = null;
        $dataScorePerformance = null;
        try {
            if (!is_array($values)) {
                throw new Exception("Erro nos dados recebidos para processamento.");
            } else {
                if((!isset($values['id_jornadas']))||($values['id_jornadas'] == '')||
                   (!isset($values['id_time']))||($values['id_time'] == '')){
                    throw new Exception("Time não informado.");

                }elseif(($values['id_categoria'] == '')||($values['id_metrica'] == '')){
                    throw new Exception("Métrica não informada.");

                }elseif(($values['ds_periodo'] == '')||($values['ds_sprint'] == '')){
                    throw new Exception("Sprint não informada.");

                }else{

                    $dataScorePerformance = [
                        'p_id_jornada'  => $values['id_jornadas'],
                        'p_id_time'     => $values['id_time'],
                        'p_id_categoria'=> $values['id_categoria'],
                        'p_id_metrica'  => $values['id_metrica'],
                        'p_ds_periodo'  => $values['ds_periodo'],
                        'p_ds_sprint'   => $values['ds_sprint'],
                        'p_vl_regra'    => $values['valor_regra']];
                    $Content = $this->model->setDataScorePerformance($dataScorePerformance);

                    if (is_array($Content)) {
                        foreach ($Content as $key) {
                            $viewContent = $key['dadosGravados'];
                        }
                    } else {
                        throw new Exception("Erro na gravação do Score Performance: ". $Content);
                    }
                }
            }
        } catch (Exception $e) {
            $viewContent = 'Objeto controllerSetDataScorePerformance: ' . $e->getMessage();
        }
        return $viewContent;
    }
    /**
     * @method Buscar dados do objeto View
     * @version 1.0.1
     * */
    public function controllerGetDataScorePerformance()
    {
        $resultSet = null;
        try {
            $resultSet = $this->dataModel;
            if ((!is_array($resultSet)) || (sizeof($resultSet) <= 0)) {
                throw new Exception("Dados não encontrados.");
            }
        } catch (Exception $e) {
            $resultSet = 'Objeto controllerGetDataScorePerformance: ' . $e->getMessage();
        }
        return $resultSet;
    }
}
