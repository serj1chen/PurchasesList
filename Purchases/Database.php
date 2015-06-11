<?php
/**
 * Created by PhpStorm.
 * User: Yarsoniy
 * Date: 10.06.2015
 * Time: 17:25
 */

namespace Purchases;


class Database {
    private static $instance;

    private $connection;
    
    private $dsn = "mysql:host=localhost;dbname=purchases;charset=utf8";
    private $user = 'purchase_app';
    private $pass = '1111';

    private function __construct(){
        $this->connection = new \PDO($this->dsn, $this->user, $this->pass);
        $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    private function init() {
        $query = "
        CREATE TABLE IF NOT  EXISTS `purchases` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `description` varchar(100) DEFAULT NULL,
          `price` decimal(10,2) DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8
        ";
        $this->execute($query);
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
            self::$instance->init();
        }
        return self::$instance;
    }

    public function fetchObjects($sql, $params = array()) {
        $result = array();
        try{
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetchAll(\PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            echo $e->getMessage() . "\n";
        }
        return $result;
    }

    public function execute($sql, $params = array()) {
        $result = array();
        try{
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
        } catch (\PDOException $e) {
            echo $e->getMessage() . "\n";
        }
        return $result;
    }

    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }

}