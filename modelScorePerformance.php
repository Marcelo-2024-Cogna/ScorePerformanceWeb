<?php
include_once 'database/DatabaseConnection.php';
class modelScorePerformance {

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
    public function dataScorePerformance($dataSet = null, $dataSearch = null)
    {
        $recordSet = null;
        try {

            $id_jornada = (isset($dataSet['id_jornadas']) ? $dataSet['id_jornadas'] : '');
            $id_time = (isset($dataSet['id_time']) ? $dataSet['id_time'] : '');
            $periodo = (isset($dataSet['ds_periodo']) ? $dataSet['ds_periodo'] : '');
            $sprint = (isset($dataSet['ds_sprint']) ? $dataSet['ds_sprint'] : '');
            
            // Avaliar tipo de SQL a ser executado
            switch ($dataSearch) {
                case 'sql_completa':
                    // *** dataProcessReportTeam *** ==> score_performance
                    // Pesquisar por todos os campos do formulário: SCORE PERFORMACE 
                    $querySelect = "select periodo, sprint, valor, jornada, time, categoria, metrica, faixa, nota, pontuacao
                                    from score_performance
                                    where id_jornadas = :idJornada
                                    and id_time = :idTime
                                    and periodo = :dsPeriodo
                                    and sprint = :dsSprint";
                    $stmt = $this->pdo->prepare($querySelect);
                    $stmt->bindParam(':idJornada', $id_jornada, PDO::PARAM_STR);
                    $stmt->bindParam(':idTime', $id_time, PDO::PARAM_STR);
                    $stmt->bindParam(':dsPeriodo', $periodo, PDO::PARAM_STR);
                    $stmt->bindParam(':dsSprint', $sprint, PDO::PARAM_STR);                
                    $stmt->execute();
                    $recordSet = $stmt->fetchAll(PDO::FETCH_ASSOC);
                break;
                case 'sql_jornada_time':
                    // *** dataProcessReportTeam *** ==> score_performance
                    // Pesquisar somente por jornada e time
                    $querySelect = "select periodo, sprint, valor, jornada, time, categoria, metrica, faixa, nota, pontuacao
                                    from score_performance
                                    where id_jornadas = :idJornada
                                    and id_time = :idTime";
                    $stmt = $this->pdo->prepare($querySelect);
                    $stmt->bindParam(':idJornada', $id_jornada, PDO::PARAM_STR);
                    $stmt->bindParam(':idTime', $id_time, PDO::PARAM_STR);
                    $stmt->execute();
                    $recordSet = $stmt->fetchAll(PDO::FETCH_ASSOC);
                break;
                case 'sql_jornada_time_periodo':
                    // *** dataProcessReportTeam *** ==> score_performance
                    // Pesquisar somente por time período, trazendo somente o time
                    $querySelect = "select periodo, sprint, valor, jornada, time, categoria, metrica, faixa, nota, pontuacao
                                    from score_performance
                                    where id_jornadas = :idJornada
                                    and id_time = :idTime
                                    and periodo = :dsPeriodo";
                    $stmt = $this->pdo->prepare($querySelect);
                    $stmt->bindParam(':idJornada', $id_jornada, PDO::PARAM_STR);
                    $stmt->bindParam(':idTime', $id_time, PDO::PARAM_STR);
                    $stmt->bindParam(':dsPeriodo', $periodo, PDO::PARAM_STR);                    
                    $stmt->execute();
                    $recordSet = $stmt->fetchAll(PDO::FETCH_ASSOC);
                break;
                case 'sql_jornada_time_sprint':
                    // *** dataProcessReportTeam *** ==> score_performance
                    // Pesquisar somente por time e sprint, trazendo somente o time
                    $querySelect = "select periodo, sprint, valor, jornada, time, categoria, metrica, faixa, nota, pontuacao
                                    from score_performance
                                    where id_jornadas = :idJornada
                                    and id_time = :idTime
                                    and sprint = :dsSprint";
                    $stmt = $this->pdo->prepare($querySelect);
                    $stmt->bindParam(':idJornada', $id_jornada, PDO::PARAM_STR);
                    $stmt->bindParam(':idTime', $id_time, PDO::PARAM_STR);
                    $stmt->bindParam(':dsSprint', $sprint, PDO::PARAM_STR);
                    $stmt->execute();
                    $recordSet = $stmt->fetchAll(PDO::FETCH_ASSOC);
                break;
                case 'sql_jornada_sprint':
                    // *** dataProcessReportJourney *** ==> score_performance_ranking
                    // Pesquisar somente por Jornada e sprint, trazendo somente o time
                    $querySelect = "select r.jornada, r.time, r.periodo, r.sprint, r.score_total, r.ranking
                                    from score_performance_ranking r
                                    where r.id_jornadas = :idJornada
                                    and r.sprint = :dsSprint";
                    $stmt = $this->pdo->prepare($querySelect);
                    $stmt->bindParam(':idJornada', $id_jornada, PDO::PARAM_STR);
                    $stmt->bindParam(':dsSprint', $sprint, PDO::PARAM_STR);
                    $stmt->execute();
                    $recordSet = $stmt->fetchAll(PDO::FETCH_ASSOC);                
                break;
                case 'sql_jornada_periodo':
                    // *** dataProcessReportJourney *** ==> score_performance_ranking
                    // Pesquisar somente por time período, trazendo somente o time
                    $querySelect = "select r.jornada, r.time, r.periodo, r.sprint, r.score_total, r.ranking
                                    from score_performance_ranking r
                                    where r.id_jornadas = :idJornada
                                    and r.periodo = :dsPeriodo";
                    $stmt = $this->pdo->prepare($querySelect);
                    $stmt->bindParam(':idJornada', $id_jornada, PDO::PARAM_STR);
                    $stmt->bindParam(':dsPeriodo', $periodo, PDO::PARAM_STR);                    
                    $stmt->execute();
                    $recordSet = $stmt->fetchAll(PDO::FETCH_ASSOC);
                break;
                case 'sql_jornada':
                    // *** dataProcessReportJourney *** ==> score_performance_ranking
                    // Pesquisar somente por time período, trazendo somente o time
                    $querySelect = "select r.jornada, r.time, r.periodo, r.sprint, r.score_total, r.ranking
                                    from score_performance_ranking r
                                    where r.id_jornadas = :idJornada";
                    $stmt = $this->pdo->prepare($querySelect);
                    $stmt->bindParam(':idJornada', $id_jornada, PDO::PARAM_STR);
                    $stmt->execute();
                    $recordSet = $stmt->fetchAll(PDO::FETCH_ASSOC);                
                break;
                default:
                    // Pesquisar todos os dados, independente de parâmetros
                    $querySelect = "select periodo, sprint, valor, jornada, time, categoria, metrica, faixa,nota
                                    from score_performance;";
                    $stmt = $this->pdo->query($querySelect);
                    $recordSet =  $stmt->fetchAll();
                break;
            }
            

            if (!is_array($recordSet)) {
                throw new Exception("Erro na pesquisa dos dados gravados.");
            }
        } catch (Exception $e) {
            $recordSet = 'Objeto dataScorePerformance: ' . $e->getMessage();
        }   
        return $recordSet;
    }

    /**
     * @method Buscar dados das Jorndas
     * @version 1.0.1
     * */ 
    public function dataScorePerformanceJourney()
    {
        $recordSet = null;
        try {
            $querySelect = "select id_jornadas,
	                               descricao
                            from jornadas
                            where ativo = 'S'
							order by descricao asc";
            $stmt = $this->pdo->query($querySelect);
            $recordSet =  $stmt->fetchAll();
            if (!is_array($recordSet)) {
                throw new Exception("Erro na pesquisa das Jornadas.");
            }
        } catch (Exception $e) {
            $recordSet = 'Objeto dataScorePerformanceJourney: ' . $e->getMessage();
        }   
        return $recordSet;
    }

    /**
     * @method Buscar dados das views
     * @version 1.0.1
     * */ 
    public function dataScorePerformanceSquads()
    {
        $recordSet = null;
        try {
            $querySelect = "select id_time,
	                               jornadas_id,
                                   descricao
                            from times
                            where ativo  = 'S'
							order by descricao asc";
            $stmt = $this->pdo->query($querySelect);
            $recordSet =  $stmt->fetchAll();
            if (!is_array($recordSet)) {
                throw new Exception("Erro na pesquisa dos Times.");
            }
        } catch (Exception $e) {
            $recordSet = 'Objeto dataScorePerformanceSquads: ' . $e->getMessage();
        }   
        return $recordSet;
    } 

    /**
     * @method Buscar dados das views
     * @version 1.0.1
     * */ 
    public function dataScorePerformanceMetrics()
    {
        $recordSet = null;
        try {
            $querySelect = "select id_metrica,
                                   descricao
                            from metricas
                            where ativo  = 'S'";
            $stmt = $this->pdo->query($querySelect);
            $recordSet =  $stmt->fetchAll();
            if (!is_array($recordSet)) {
                throw new Exception("Erro na pesquisa dos métricas.");
            }
        } catch (Exception $e) {
            $recordSet = 'Objeto dataScorePerformanceMetrics: ' . $e->getMessage();
        }   
        return $recordSet;
    }

     /**
     * @method Buscar dados das views
     * @version 1.0.1
     * */ 
    public function dataScorePerformanceCategories()
    {
        $recordSet = null;
        try {
            $querySelect = "select id_categoria,
                                   descricao
                            from categoria
                            where ativo  = 'S'";
            $stmt = $this->pdo->query($querySelect);
            $recordSet =  $stmt->fetchAll();
            if (!is_array($recordSet)) {
                throw new Exception("Erro na pesquisa dos categoria.");
            }
        } catch (Exception $e) {
            $recordSet = 'Objeto dataScorePerformanceCategories: ' . $e->getMessage();
        }   
        return $recordSet;
    }

        /**
     * @method Buscar dados das views
     * @version 1.0.1
     * */ 
    public function setDataScorePerformance($parameters)
    {
        $resultSet = null;
        $recordSet = null;
        try {
            if ($parameters != null) {
                
                $queryProcedure = "CALL ScorePerformance(".$parameters['p_id_jornada'].",
                                                         ".$parameters['p_id_time'].", 
                                                         ".$parameters['p_id_categoria'].",
                                                         ".$parameters['p_id_metrica'].",
                                                        '".$parameters['p_ds_periodo']."',
                                                        '".$parameters['p_ds_sprint']."',
                                                         ".$parameters['p_vl_regra'].",
                                                           @p_return);";
                $resultSet = $this->pdo->query($queryProcedure);

                if ($resultSet != null) {

                    $querySelectResult = "select @p_return as dadosGravados;";
                    $stmt = $this->pdo->query($querySelectResult);
                    $recordSet =  $stmt->fetchAll();

                }else{
                    throw new Exception("Erro na execução da Procedure: ScorePerformance.");
                }
            }else{
                throw new Exception("Erro nos dados informados: ".$parameters);
            }    
        } catch (Exception $e) {
            $recordSet = 'Objeto setDataScorePerformance: ' . $e->getMessage();
        }

        return $recordSet;
    }
}
?>