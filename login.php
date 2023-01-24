<?php

require_once "header.php";
require_once 'DAL/UserRepository.php';

$login = "";
$password = "";

if (isset($_POST['signIn'])) {

    $userRepository = new UserRepository();

    $login = $_POST['login'];
    $password = $_POST['password'];

    $user = $userRepository->signIn($login, $password);

    if ($user != null) {

        (new SessionHelper())->signIn($user);
        header("Location:index.php");
    } else {
        $error = "Nieprawidłowy login lub hasło";
    }

}


?>

<div class="centered-container">

    <form method="post" action="login.php" class="form card">

        <div class="form-header">
            <span>Zaloguj się</span>
            <hr>
        </div>

        <?php
        if (isset($error)) {
            ?>

            <div class="form-error">
                <ul>
                    <li><?= $error ?></li>
                </ul>
            </div>

            <?php
        }
        ?>


        <div class="form-field">
            <label for="login">Login</label>
            <input type="text" name="login" id="login" required value="<?=$login?>">
        </div>

        <div class="form-field">
            <label for="password">Hasło</label>
            <input type="password" name="password" id="password" required value="<?=$password?>">
        </div>

        <button type="submit" class="btn btn-access" name="signIn" id="signIn">Logowanie</button>

    </form>

</div>

<?php

require_once "footer.php";

?>
