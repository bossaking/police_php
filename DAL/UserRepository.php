<?php

require_once 'DBConnection.php';


class UserRepository{

    private $conn;

    function __construct(){
        $dbConnection = new DBConnection();
        $this->conn = $dbConnection->getConnection();
    }


    public function signIn($login, $password){

        $sql = "SELECT * FROM user WHERE login = '$login'";
        $result = $this->conn->query($sql);

        if($result->num_rows == 0){
            return false;
        }

        $user = $result->fetch_assoc();
        $hashedPassword = $user['password'];
        return password_verify($password, $hashedPassword);

    }


}