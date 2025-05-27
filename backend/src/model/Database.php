<?php

namespace App\model;

use PDO;
use PDOException;
use App\model\ErrorLog;
use Dotenv\Dotenv;


class Database {
    private PDO $pdo; 

    public function __construct() {
        
        try {
        $dotenv = Dotenv::createImmutable(__DIR__ . "/../../../");
        $dotenv->load();
        $db_host = $_ENV["DB_HOST"];
        $db_name = $_ENV["DB_NAME"];
        $db_user = $_ENV["DB_USER"];
        $db_pass = $_ENV["DB_PASSWORD"];
        
        $conn = "mysql:host=" . $db_host . ";dbname=" . $db_name . ";charset=utf8";
        
            $this->pdo =  new PDO($conn, $db_user, $db_pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    
        }
        catch(PDOException $e) {
            ErrorLog::logError($e);
        }
}

    public function getConn(): PDO {
        return $this->pdo;
    }
}

?>