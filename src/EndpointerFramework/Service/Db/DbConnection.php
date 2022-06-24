<?php

namespace EndpointerFramework\Service\Db;

use EndpointerFramework\Exception\Db\TransactionException;

class DbConnection
{
    private $dbConnection;

    public function getConnection()
    {
        return $this->dbConnection;
    }

    public function connect($dbConfig)
    {
        try {
            $this->dbConnection = new \PDO(

                $dbConfig['dataSource'],
                $dbConfig['userName'],
                $dbConfig['password'],
                $dbConfig['options']

            );

            return $this;
        } catch (\PDOException $ex) {
            throw new TransactionException($ex->getMessage());
        }
    }
}
