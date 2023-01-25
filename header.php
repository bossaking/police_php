<?php

require_once 'helpers/SessionHelper.php';

$sessionHelper = new SessionHelper();


?>

<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>E-zgłoszenia</title>

    <link rel="icon" href="images/icon.ico">

    <link rel="stylesheet" href="styles/style.css">


    <link href='https://fonts.googleapis.com/css?family=Alatsi&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Arimo&subset=latin,latin-ext' rel='stylesheet' type='text/css'>


</head>
<body>

<header class="main-header">
    <a href="index.php" class="image-link">
        <img alt="logo" src="images/police_logo.png">
        <span>E-zgłoszenia</span>
    </a>

    <div class="right-side-nav">

        <?php

        if ($sessionHelper->isLoggedIn()) {
            $user = $sessionHelper->getUser();
            ?>

            <span class="user-info">Jesteś zalogowany jako <?= $user->getName() ?> <?= $user->getSurname() ?> (<?= $user->getMaxRoleTitle() ?>)</span>
            <nav class="header-links">

                <a href="submissions.php" class="btn btn-outline-warn">Zgłoszenia</a>

                <?php
                if ($user->getMaxRole() != Roles::EMPLOYEE) {
                    ?>
                    <span>|</span>
                    <a href="submissions.php" class="btn btn-outline-access">Pracownicy</a>
                <?php } ?>

                <?php
                if ($user->userInRole(Roles::ADMIN)) {
                    ?>
                    <span>|</span>
                    <a href="teams.php" class="btn btn-primary">Zespoły</a>
                    <a href="users.php" class="btn btn-primary">Użytkownicy</a>
                    <?php
                }
                ?>
                <span>|</span>
                <a href="logout.php" class="btn btn-outline-danger">Wyloguj</a>
            </nav>
            <?php
        } else {
            ?>
            <nav class="header-links">
                <a href="login.php" class="btn btn-outline-primary">Zaloguj się</a>
            </nav>
            <?php
        }
        ?>
    </div>


</header>

<div class="main-container">
