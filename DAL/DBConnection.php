<?php


class DBConnection{

    private $ip = "31.172.70.199";
    private $port = 3306;
    private $username = "root";
    private $password = "root";
    private $database = "police";


    public function getConnection(){
        $conn = new mysqli($this->ip, $this->username, $this->password, $this->database);
        if ($conn -> connect_errno) {
            echo "Failed to connect to MySQL: " . $conn -> connect_error;
            exit();
        }

        return $conn;
    }

}