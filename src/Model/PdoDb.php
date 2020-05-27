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
     * Pdo Connection
     * @var Pdo
     */
    private $Pdo;

    /**
     * PdoDb constructor
     * Receive the Pdo Connection & store it
     * @param Pdo $Pdo
     */
    public function __construct(Pdo $Pdo)
    {
        $this->Pdo = $Pdo;
    }

    /**
     * Returns a unique result from the Database
     * @param string $query
     * @param array $params
     * @return mixed
     */
    public function getData(string $query, array $params = [])
    {
        $PDOStatement = $this->Pdo->prepare($query);
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
       $PDOStatement = $this->Pdo->prepare($query);
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
        $req = PdoFactory::getPdo()->prepare($query);
        return $req->execute($params);

    }
}