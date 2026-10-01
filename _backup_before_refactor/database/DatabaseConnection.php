<?php
/**
 *  @todo classe de conexão com banco de dados.
 *  @method getPdo -> Objeto de Conexão
 *  @method getData -> Objeto de Pesquisa
 *  @method insertData -> Objeto de Registro
 *  @user_bd user -> root || pass -> cogna_2025
 *  @user_bd user -> score_performance || pass -> Cogna@2025
 *  @version: 1.0.0
 *  @since: 07/2024
 */
class DatabaseConnection
{

    // Informações de conexão
    /*private $host = 'localhost';
    private $dbName = 'score_performance';
    private $username = 'score_performance';
    private $password = 'Cogna@2025';*/
    private $host = 'localhost';
    private $dbName = 'score_performance';
    private $username = 'root';
    private $password = '';
    
    private $pdo;

    // Efetivação de conexão
    public function __construct()
    {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            // Lidar com erros de conexão de forma segura
            print 'Erro de conexão: ' . $e->getMessage();
        }
    }

    /**
     * @method Objetos de Conexão
     * @version 1.0.1
     * */ 
    public function getPdo()
    {
        return $this->pdo;
    }
}
