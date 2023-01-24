<?php

require_once 'enums/Roles.php';

class User
{

    private int $id;
    private string $name, $surname, $email, $login, $password;
    private array $roles;

    public function __construct($id, $name, $surname, $login, $password, $email, $roles)
    {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->login = $login;
        $this->password = $password;
        $this->email = $email;
        $this->roles = $roles;
    }

    public function userInRole(Roles $roleId): bool
    {
        if ($this->roles == null || count($this->roles) == 0) return false;
        foreach ($this->roles as $role) {
            if ($role->getId() == $roleId->value)
                return true;
        }
        return false;
    }

    public function getMaxRoleTitle(): string|null
    {
        $role = $this->getMaxRole();
        return $role?->title();
    }

    public function getMaxRole(): Roles|null
    {
        if ($this->userInRole(Roles::ADMIN)) return Roles::ADMIN;
        if ($this->userInRole(Roles::SUPERIOR)) return Roles::SUPERIOR;
        if ($this->userInRole(Roles::EMPLOYEE)) return Roles::EMPLOYEE;

        return null;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname($surname): void
    {
        $this->surname = $surname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles($roles): void
    {
        $this->roles = $roles;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }


}