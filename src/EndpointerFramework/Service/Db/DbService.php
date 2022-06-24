<?php

namespace EndpointerFramework\Service\Db;

use EndpointerFramework\Service\BasicService;

use EndpointerFramework\Exception\InternalServerErrorException;
use EndpointerFramework\Exception\Db\NoRecordFoundException;
use EndpointerFramework\Exception\Db\MultipleRowsException;

class DbService extends BasicService
{
    private $dbConnection;
    private $row;
    private $rowSet;
    private $lastInsertId;
    private $rowCount;

    public function queryRow(

        $sql,
        $data = [],
        $isFile = true

    ) {
        $t = $this;

        try {

            $sth = $t

                ->getDbConnection()
                ->prepare(

                    $isFile ?
                        \file_get_contents($sql) :
                        $sql

                );

            foreach ($data as $key => &$value) {

                $sth->bindParam(

                    ':' . $key,
                    $value

                );
            }

            //$sth->debugDumpParams();

            $sth->setFetchMode(\PDO::FETCH_ASSOC);

            $sth->execute();

            $row = $sth->fetchAll();

            if (count($row) == 0) {
                throw new NoRecordFoundException();
            }

            if (count($row) > 1) {
                throw new MultipleRowsException();
            }

            $t->setRow($row[0]);
        } catch (\PDOException $ex) {

            throw new InternalServerErrorException($ex->getMessage());
        }

        return $this;
    }

    public function queryRowSet(

        $sql,
        $data = [],
        $isFile = true

    ) {
        $t = $this;

        try {

            $sth = $t

                ->getDbConnection()
                ->prepare(

                    $isFile ?
                        \file_get_contents($sql) :
                        $sql

                );

            foreach ($data as $key => &$value) {

                $sth->bindParam(

                    ':' . $key,
                    $value

                );
            }

            //$sth->debugDumpParams();

            $sth->setFetchMode(\PDO::FETCH_ASSOC);

            $sth->execute();

            $t->setRowSet($sth->fetchAll());

            return $this;
        } catch (\PDOException $ex) {

            throw new InternalServerErrorException($ex->getMessage());
        }
    }

    public function insert(

        $sql,
        $data = [],
        $isFile = true

    ) {
        $t = $this;

        try {

            $sth = $t

                ->getDbConnection()
                ->prepare(

                    $isFile ?
                        \file_get_contents($sql) :
                        $sql

                );

            foreach ($data as $key => &$value) {

                $sth->bindParam(

                    ':' . $key,
                    $value

                );
            }

            //$sth->debugDumpParams();

            $sth->execute();

            $t->setLastInsertId($t->getDbConnection()->lastInsertId());

            return $this;
        } catch (\PDOException $ex) {

            throw new InternalServerErrorException($ex->getMessage());
        }
    }

    public function update(

        $sql,
        $data = [],
        $isFile = true

    ) {

        $t = $this;

        try {

            $sth = $t

                ->getDbConnection()
                ->prepare(

                    $isFile ?
                        \file_get_contents($sql) :
                        $sql

                );

            foreach ($data as $key => &$value) {

                $sth->bindParam(

                    ':' . $key,
                    $value

                );
            }

            // $sth->debugDumpParams();

            $sth->execute();

            $this->setRowCount($sth->rowCount());

            return $this;
        } catch (\PDOException $ex) {

            throw new InternalServerErrorException($ex->getMessage());
        }
    }

    public function delete(

        $sql,
        $data

    ) {

        return $this->update($sql, $data);
    }

    public function begin()
    {
        try {

            $this

                ->getDbConnection()
                ->beginTransaction();

            return $this;
        } catch (\PDOException $ex) {

            throw new InternalServerErrorException($ex->getMessage());
        }
    }

    public function commit()
    {
        try {

            $this

                ->getDbConnection()
                ->commit();

            return $this;
        } catch (\PDOException $ex) {

            throw new InternalServerErrorException($ex->getMessage());
        }
    }

    public function rollBack()
    {
        try {

            $this

                ->getDbConnection()
                ->rollBack();

            return $this;
        } catch (\PDOException $ex) {

            throw new InternalServerErrorException($ex->getMessage());
        }
    }

    public function getRow()
    {
        return $this->row;
    }

    public function setRow($row)
    {
        $this->row = $row;

        return $this;
    }

    public function getRowSet()
    {
        return $this->rowSet;
    }

    public function setRowSet($rowSet)
    {
        $this->rowSet = $rowSet;

        return $this;
    }

    public function getLastInsertId()
    {
        return $this->lastInsertId;
    }

    public function setLastInsertId($lastInsertId)
    {
        $this->lastInsertId = $lastInsertId;

        return $this;
    }

    public function getRowCount()
    {
        return $this->rowCount;
    }

    public function setRowCount($rowCount)
    {
        $this->rowCount = $rowCount;

        return $this;
    }

    public function setDbConnection($dbConnection)
    {
        $this->dbConnection = $dbConnection;

        return $this;
    }

    public function getDbConnection()
    {
        return $this->dbConnection;
    }
}
