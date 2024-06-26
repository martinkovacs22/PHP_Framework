<?php

namespace model;
require_once __DIR__ . '\..\..\Autoloader.php';  
    class Db{
        public static function connectToDatabase($dbName, $dbUser, $dbPass, $dbHost = 'mysql.rackhost.hu') {
            $dsn = "mysql:host=".$dbHost.";dbname=".$dbName.";charset=utf8mb4";
            
            try {
                self::$db = new \PDO($dsn, $dbUser, $dbPass);
            } catch (\PDOException $e) {
                die("Hiba a kapcsolat létrehozása közben: " . $e->getMessage());
            }
            
            return self::$db;
        }
        
        
        public static function Call($name, $array, $db) {
            try {
                $paramKeys = array_keys($array);
                $paramPlaceholders = implode(', ', array_map(function($key) {
                    return ":$key";
                }, $paramKeys));

            $stmt = $db->prepare("CALL $name($paramPlaceholders)");

            foreach ($array as $key => $value) {
                $stmt->bindValue(":$key", $value, \PDO::PARAM_STR);
            }

            $stmt->execute();
            $resultSet = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return array("err" => false, "data" => $resultSet);

            } catch (\Throwable $th) {
                return array("err" => true, "data" => $th->getMessage());
            }
        }


        private static $db;

    }