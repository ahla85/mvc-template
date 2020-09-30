<?php

class Database
{

    private $dbh;
    private $stmt;

    public function __construct()
    {
        $dsn = "mysql:host=" . DATABASE_HOST . ";dbname=" . DATABASE_NAME;

        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];

        try {
            $this->dbh = new PDO($dsn, DATABASE_USER, DATABASE_PASSWORD, $options);
        } catch (PDOException $e) {
            try {
                $this->dbh = new PDO("mysql:host=" . DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, $options);
                $this->dbh->exec("CREATE DATABASE " . DATABASE_NAME);
            } catch (PDOException $er) {
                die("<h3>" . $er->getMessage() . "</h3>");
            }
        }
    }

    public function exec($exec)
    {
        return $this->dbh->exec($exec);
    }

    public function query($query)
    {
        $this->stmt = $this->dbh->prepare($query);
    }

    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($type):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($type):
                    $type = PDO::PARAM_BOOL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }

        if ($value == null)
            $value = "";

        return $this->stmt->bindValue($param, $value, $type);
    }

    public function execute()
    {
        return $this->stmt->execute();
    }

    public function resultSet()
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function single()
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function rowCount()
    {
        return $this->stmt->rowCount();
    }
}
