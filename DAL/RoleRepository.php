<?php

require_once 'Repository.php';

class RoleRepository extends Repository
{

    function __construct()
    {
        parent::__construct();
    }

    public function getUserRoles($userId): array|null
    {
        $sql = "SELECT * FROM role r, user_role ur WHERE ur.id_user = '$userId' AND r.id = ur.id_role";
        $result = $this->conn->query($sql);

        if ($result->num_rows == 0) return null;

        $roles = array();
        while ($row = $result->fetch_assoc()) {
            $role = new Role($row['id'], $row['title']);
            $roles[] = $role;
        }

        return $roles;
    }

    public function getRoles(): array
    {
        $sql = "SELECT * FROM role";
        $result = $this->conn->query($sql);

        $roles = array();
        while ($roleRow = $result->fetch_assoc()) {
            $role = new Role($roleRow['id'], $roleRow['title']);
            $roles[] = $role;
        }

        return $roles;
    }

    public function assignUserToRole($userId, $roleId): bool
    {
        $sql = "INSERT INTO user_role(id_user, id_role) VALUES ('$userId', '$roleId')";
        return $this->conn->query($sql);
    }

    public function unsetAllRolesFromUser($userId){
        $sql = "DELETE FROM user_role WHERE id_user = '$userId'";
        $this->conn->query($sql);
    }

}