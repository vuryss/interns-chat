<?php


class DB
{
    private static $instance = null;

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new DB();
        }

        return self::$instance;
    }

    private $pdo;

    public function __construct()
    {
        $this->pdo = new PDO(
            'mysql:host=' . DB_HOST . ';port=3306;dbname=' . DB_NAME,
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );

    }

    public function execute($query, $parameters = [])
    {
        $statement = $this->pdo->prepare($query);
        $statement->execute($parameters);
    }

    public function fetchOne($query, $parameters = [])
    {
        $statement = $this->pdo->prepare($query);
        $statement->execute($parameters);

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function getLastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}
