<?php namespace core;

use PDO;

require __DIR__.'/../config.php';

class DbManager
{
    /**
     * @var PDO
     */
    private $connection = null;

    public function __construct()
    {
        global $dbConfig;
        list($host, $user, $password, $db) = array_values($dbConfig);
        # Do the connection stuff here
        try {
            $this->connection = new PDO("mysql:host=$host;", $user, $password);
            // set the PDO error mode to exception
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->exec("CREATE DATABASE IF NOT EXISTS $db");
            $this->connection->exec("use $db");
            return $this;
        } catch (PDOException $e) {
            echo "Error while connecting ". $e->getMessage();
        }
        return $this;
    }

    public function executeQuery($query, $params = false)
    {
        if($params) {
            $q = $this->connection->prepare($query);
            $q->execute($params);
        }else{
            $q = $this->connection->exec($query);
        }
        # Do some security checks before executing if necesary
        return $q;
    }

    public function findOne($query, $id)
    {
        $q = $this->connection->prepare($query);
        $q->bindParam('user_id', $id);
        $q->execute();
        return $q->fetch();
    }
}