<?php

require_once 'DBConnection.php';
require_once 'RoleRepository.php';
require_once 'UserRepository.php';
require_once 'models/Role.php';
require_once 'models/User.php';
require_once 'models/Team.php';

class Repository{

    protected ?mysqli $conn;

    function __construct()
    {
        $dbConnection = new DBConnection();
        $this->conn = $dbConnection->getConnection();
    }

}