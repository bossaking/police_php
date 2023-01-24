<?php
require_once 'header.php';
require_once 'DAL/RoleRepository.php';
require_once 'DAL/UserRepository.php';


$sessionHelper = new SessionHelper();
if (!$sessionHelper->isLoggedIn() || !$sessionHelper->getUser()->userInRole(Roles::ADMIN)) {
    header("Location: index.php");
}

if (!isset($_POST['save']) && !isset($_GET['id'])) {
    header("Location: users.php");
}

$roleRepo = new RoleRepository();
$userRepo = new UserRepository();
$roles = $roleRepo->getRoles();

$userId = 0;
$name = "";
$surname = "";
$login = "";
$email = "";
$password = "";
$selectedRole = 3;

if (isset($_GET['id']) && !isset($_POST['save'])) {
    $userId = $_GET['id'];
    $user = $userRepo->getUserById($userId);
    $name = $user->getName();
    $surname = $user->getSurname();
    $login = $user->getLogin();
    $email = $user->getEmail();
    $password = $user->getPassword();
    $selectedRole = $user->getMaxRole()->value;

} else if (isset($_POST['id']) && isset($_POST['save'])) {
    $userId = $_POST['id'];
    $user = $userRepo->getUserById($userId);
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $login = $_POST['login'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $selectedRole = $_POST['role'];

    if ($user->getEmail() != $email && !$userRepo->checkUserEmailFree($email)) {
        $error = "Użytkownik o podanym adresie e-mail już istnieje w systemie";
    } else {
        if ($user->getLogin() != $login && !$userRepo->checkUserLoginFree($login)) {
            $error = "Użytkownik o podanym loginie już istnieje w systemie";
        }
    }

    if (!isset($error)) {

        $userRepo->updateUser($userId, $name, $surname, $login, $email, $password, $user->getPassword());
        $roleRepo->unsetAllRolesFromUser($userId);

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

    }

} else {
    header("Location: users.php");
}


?>

    <div class="centered-container">

        <form method="post" action="edit_user.php" class="form card">

            <div class="form-header">
                <span>Edycja użytkownika</span>
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

            <input type="hidden" value="<?= $userId ?>" id="id" name="id">

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
                <button type="submit" class="btn btn-primary w-100" name="save" id="save">Zapisz</button>
                <button type="button" class="btn btn-outline-danger w-100" onclick="history.go(-1);">Wstecz</button>
            </div>

        </form>

    </div>
<?php
require_once 'footer.php';
?>