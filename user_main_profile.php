<?php
require_once 'header.php';
require_once 'DAL/UserRepository.php';
require_once 'helpers/SessionHelper.php';


$actualPassword = "";
$newPassword = "";
$repeatPassword = "";

if (isset($_POST['change'])) {

    $sessionHelper = new SessionHelper();

    $actualPassword = $_POST['actual_password'];
    $newPassword = $_POST['new_password'];
    $repeatPassword = $_POST['repeat_password'];

    if ($newPassword != $repeatPassword) {
        $error = "Hasła się nie zgadzają";
    } else {
        if (!password_verify($actualPassword, $sessionHelper->getUser()->getPassword())) {
            $error = "Nieprawidłowe aktualne hasło";
        } else {
            (new UserRepository())->updateUserPassword($sessionHelper->getUser()->getId(), password_hash($newPassword, PASSWORD_BCRYPT));
            header("Location: logout.php");
        }
    }

}

?>

<div class="centered-container">

    <form method="post" action="user_main_profile.php" class="form card">

        <div class="form-header">
            <span>Zmiana hasła</span>
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
            <label for="actual_password">Aktualne hasło</label>
            <input type="password" name="actual_password" id="actual_password" required value="<?= $actualPassword ?>">
        </div>

        <div class="form-field">
            <label for="new_password">Nowe hasło</label>
            <input type="password" name="new_password" id="new_password" required value="<?= $newPassword ?>">
        </div>

        <div class="form-field">
            <label for="repeat_password">Powtórz hasło</label>
            <input type="password" name="repeat_password" id="repeat_password" required value="<?= $repeatPassword ?>">
        </div>

        <button type="submit" class="btn btn-access" name="change" id="change">Zmień hasło</button>

    </form>

</div>

<?php
require_once 'footer.php';
?>
