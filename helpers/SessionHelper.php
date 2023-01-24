<?php

ob_start();
session_start();

require_once 'models/User.php';
require_once 'models/Role.php';
require_once 'enums/Roles.php';

class SessionHelper
{

    public function signIn(User $user): void
    {
        if ($this->isLoggedIn()) {
            $this->logOut();
        }

        $_SESSION['user'] = serialize($user);
    }

    public function getUser(): User|null
    {
        if ($this->isLoggedIn()) {
            return unserialize($_SESSION['user']);
        }
        return null;
    }

    public function isLoggedIn(): bool
    {
        return isset($_SESSION['user']);
    }


    public function logOut(): void
    {
        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
        }
    }

}