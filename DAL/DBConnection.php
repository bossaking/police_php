<?php


class DBConnection{

    private string $ip = "31.172.70.199";
    private int $port = 3306;
    private string $username = "root";
    private string $password = "2f9J17AKqNm1s^%^";
    private string $database = "police";


    public function getConnection(){
        $conn = new mysqli($this->ip, $this->username, $this->password, $this->database);
        if ($conn -> connect_errno) {
            echo "Failed to connect to MySQL: " . $conn -> connect_error;
            exit();
        }

        return $conn;
    }

}