<?php


class Db {

    private $dbhost = "localhost";
    private $dbuser = "user";
    private $dbpass = "password";
    private $dbname = "databasename";

    public function connect(){

        $mysql_connection = "mysql:host=$this->dbhost;dbname=$this->dbname;charset=utf8";
        $connection = new PDO($mysql_connection,$this->dbuser,$this->dbpass);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $connection;
        
    }

}


?>
