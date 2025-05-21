<?php

class Database {

    private static $dbName = 'soe_pro_services';
    private static $dbHost = 'localhost';
    private static $dbUsername = 'root';
    private static $dbUserPassword = '';
    private static $con = null;

    //Eliminar el acceso al constructor
    private function __construct() {
        
    }

    public static function connect() {
        if (self::$con == null) {
            try {
                self::$con = new PDO("mysql:host=" . self::$dbHost . ";" . "dbname=" . self::$dbName,
                        self::$dbUsername, self::$dbUserPassword);
                /* echo 'Conexión creada.'; */
            } catch (PDOException $e) {
                echo 'ERROR: ' . $e->getMessage();
                echo 'ERROR NÚMERO:' . $e->getCode();
            }
        }
        return self::$con;
    }

    public static function disconnect() {
        self::$con = null;
    }
}