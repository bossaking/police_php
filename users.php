<?php
require_once 'header.php';
require_once 'DAL/UserRepository.php';

$sessionHelper = new SessionHelper();

$users = (new UserRepository())->getUsers();

?>

<div class="users-container">

    <div class="section-header">
        <span>Użutkownicy</span>
        <hr>
    </div>

    <div>
        <a href="new_user.php" class="btn btn-outline-primary">+ Nowy użytkownik</a>
    </div>

    <div class="card users-table-container">
        <table class="users-table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Imię</th>
                <th>Nazwisko</th>
                <th>Login</th>
                <th>Adres e-mail</th>
                <th>Rola</th>
                <th>Akcje</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($users as $user) {
                ?>
                <tr>
                    <td><?= $user->getId() ?></td>
                    <td><?= $user->getName() ?></td>
                    <td><?= $user->getSurname() ?></td>
                    <td><?= $user->getLogin() ?></td>
                    <td><?= $user->getEmail() ?></td>
                    <td><?= $user->getMaxRoleTitle() ?></td>
                    <td>
                        <div class="action-images">
                            <?php
                            if ($user->getId() == $sessionHelper->getUser()->getId()) {
                                ?>
                                <a class="tooltip">
                                    <span class="tooltip-text">Nie można zedytować lub usunąć konta na które się zalogowałeś</span>
                                    <img alt="info_image" src="images/info.svg">
                                </a>
                                <?php
                            } else {
                                ?>

                                <a href="edit_user.php?id=<?= $user->getId() ?>">
                                    <img alt="edit_image" src="images/pencil.svg">
                                </a>
                                <span>|</span>
                                <a href="delete_user.php?id=<?= $user->getId() ?>">
                                    <img alt="delete_image" src="images/trash.svg">
                                </a>

                                <?php
                            }
                            ?>
                        </div>
                    </td>
                </tr>
                <?php
            } ?>
            </tbody>
        </table>
    </div>

</div>

<?php
require_once 'footer.php';
?>
