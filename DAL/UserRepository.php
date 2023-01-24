<?php

require_once 'Repository.php';


class UserRepository extends Repository
{

    private ?mysqli $conn;

    function __construct()
    {
        $dbConnection = new DBConnection();
        $this->conn = $dbConnection->getConnection();
    }


    public function signIn($login, $password): User|null
    {

        $user = $this->getUserByLogin($login);

        if ($user == null) {
            return null;
        }

        if (password_verify($password, $user->getPassword())) {
            return $user;
        }

        return null;
    }

    public function getUserByLogin($login): User|null
    {

        $sql = "SELECT * FROM user WHERE login = '$login'";
        $result = $this->conn->query($sql);

        if ($result->num_rows == 0) {
            return null;
        }

        $userRow = $result->fetch_assoc();
        $userRoles = (new RoleRepository())->getUserRoles($userRow['id']);

        return new User($userRow['id'], $userRow['name'], $userRow['surname'], $userRow['login'], $userRow['password'], $userRow['email'], $userRoles);
    }

    public function getUsers(): array
    {
        $sql = "SELECT * FROM user";
        $result = $this->conn->query($sql);

        $roleRepo = new RoleRepository();

        $users = array();
        while ($userRow = $result->fetch_assoc()) {
            $userRoles = $roleRepo->getUserRoles($userRow['id']);
            $user = new User($userRow['id'], $userRow['name'], $userRow['surname'], $userRow['login'], $userRow['password'], $userRow['email'], $userRoles);
            $users[] = $user;
        }

        return $users;
    }

    public function checkUserLoginFree($login): bool
    {
        $sql = "SELECT id FROM user where login = '$login'";
        return $this->conn->query($sql)->num_rows == 0;
    }

    public function checkUserEmailFree($email): bool
    {
        $sql = "SELECT id FROM user where email = '$email'";
        return $this->conn->query($sql)->num_rows == 0;
    }

    public function createUser($name, $surname, $login, $email, $password): int | null
    {
        $sql = "INSERT INTO user(name, surname, login, email, password) VALUES ('$name', '$surname', '$login', '$email', '$password')";
        $this->conn->query($sql);
        return $this->conn->insert_id;
    }

}