<?php
require_once 'header.php';
require_once 'DAL/RoleRepository.php';
require_once 'DAL/UserRepository.php';
require_once 'footer.php';

$sessionHelper = new SessionHelper();
if (!$sessionHelper->isLoggedIn() || !$sessionHelper->getUser()->userInRole(Roles::ADMIN)) {
    header("Location: index.php");
}

if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    (new RoleRepository())->unsetAllRolesFromUser($userId);
    (new UserRepository())->deleteUser($userId);
}

header("Location: users.php");