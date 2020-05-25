<?php

namespace App\Model;

use App\Model\Factory\PdoFactory;
use Pdo;

/**
 * Class PdoDb
 * Prepares Queries before execution & return
 * @package App\Model
 */
class PdoDb
{
    /**
     * PDO Connection
     * @var Pdo
     */
    private $pdo;

    /**
     * PdoDb constructor
     * Receive the Pdo Connection & store it
     * @param Pdo $pdo
     */
    public function __construct(Pdo $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Returns a unique result from the Database
     * @param string $query
     * @param array $params
     * @return mixed
     */
    public function getData(string $query, array $params = [])
    {
        $PDOStatement = $this->pdo->prepare($query);
        $PDOStatement->execute($params);

        return $PDOStatement->fetch();
    }

    /**
     * Returns many results from the Database
     * @param string $query
     * @param array $params
     * @return array|mixed
     */
    public function getAllData(string $query, array $params = [])
    {
        $PDOStatement = $this->pdo->prepare($query);
        $PDOStatement->execute($params);

        return $PDOStatement->fetchAll();
    }

    /**
     * Executes an action to the Database
     * @param string $query
     * @param array $params
     * @return bool|mixed
     */
    public function setData(string $query, array $params = [])
    {
        $req = PdoFactory::getPDO()->prepare($query);
        return $req->execute($params);

       // $PDOStatement = $this->pdo->prepare($query);
       // return $PDOStatement->execute($params);
    }
}