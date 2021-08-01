<?php

class Connection
{
    private function __construct() {}
    
    public static function open($name)
    {
        $dbuser = $_ENV['MYSQL_USER'];
        $dbpass = $_ENV['MYSQL_PASS']; 
        $dbname = $name;

        $dboptions = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8');
        $conn = new PDO("mysql:host=mysql;dbname=$dbname", $dbuser, $dbpass, $dboptions);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }
}
