<?php
require_once 'header.php';
require_once 'DAL/UserRepository.php';
require_once 'DAL/RoleRepository.php';

$name = "";
$surname = "";
$login = "";
$email = "";
$password = "";
$selectedRole = 3;

$roleRepo = new RoleRepository();
$userRepo = new UserRepository();

$roles = $roleRepo->getRoles();

if (isset($_POST['create'])) {

    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $login = $_POST['login'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $selectedRole = $_POST['role'];

    if (!$userRepo->checkUserEmailFree($email)) {
        $error = "Użytkownik o podanym adresie e-mail już istnieje w systemie";
    } else {
        if (!$userRepo->checkUserLoginFree($login)) {
            $error = "Użytkownik o podanym loginie już istnieje w systemie";
        }
    }

    if (!isset($error)) {

        $userId = $userRepo->createUser($name, $surname, $login, $email, password_hash($password, PASSWORD_BCRYPT));
        if ($userId != null) {

            switch ($selectedRole) {
                case Roles::ADMIN->value:
                    $roleRepo->assignUserToRole($userId, Roles::ADMIN->value);
                    $roleRepo->assignUserToRole($userId, Roles::SUPERIOR->value);
                    $roleRepo->assignUserToRole($userId, Roles::EMPLOYEE->value);
                    break;
                case Roles::SUPERIOR->value:
                    $roleRepo->assignUserToRole($userId, Roles::SUPERIOR->value);
                    $roleRepo->assignUserToRole($userId, Roles::EMPLOYEE->value);
                    break;
                case Roles::EMPLOYEE->value:
                    $roleRepo->assignUserToRole($userId, Roles::EMPLOYEE->value);
                    break;
            }

            header("Location: users.php");

        } else {
            $error = "Coś poszło nie tak...spróbuj ponownie";
        }
    }


}

?>

<div class="centered-container">

    <form method="post" action="new_user.php" class="form card">

        <div class="form-header">
            <span>Nowy użytkownik</span>
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

        <div class="form-row">
            <div class="form-field">
                <label for="name">Imię</label>
                <input type="text" name="name" id="name" required value="<?= $name ?>">
            </div>

            <div class="form-field">
                <label for="surname">Nazwisko</label>
                <input type="text" name="surname" id="surname" required value="<?= $surname ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-field">
                <label for="login">Login</label>
                <input type="text" name="login" id="login" required value="<?= $login ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-field">
                <label for="email">Adres e-mail</label>
                <input type="email" name="email" id="email" required value="<?= $email ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-field">
                <label for="password">Hasło</label>
                <input type="text" name="password" id="password" required value="<?= $password ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-field">
                <label for="role">Rola</label>
                <select id="role" name="role">
                    <?php
                    foreach ($roles as $role) {
                        ?>
                        <option <?php if ($selectedRole == $role->getId()) echo 'selected'; ?>
                                value="<?= $role->getId() ?>"><?= Roles::getPolishTranslation($role->getId()) ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <button type="submit" class="btn btn-access w-100" name="create" id="create">Utwórz</button>
            <button type="reset" class="btn btn-outline-danger w-100" name="signIn" id="signIn">Zresetuj</button>
        </div>

    </form>

</div>

<?php
require_once 'footer.php';
?>
