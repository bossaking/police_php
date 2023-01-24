<?php
require_once 'header.php';
require_once 'DAL/TeamRepository.php';

$sessionHelper = new SessionHelper();
if (!$sessionHelper->isLoggedIn() || !$sessionHelper->getUser()->userInRole(Roles::ADMIN)) {
    header("Location: index.php");
}

$teamRepo = new TeamRepository();

$teams = $teamRepo->getAllTeams();

?>

<div class="teams-container">

    <div class="section-header">
        <span>Zespoły</span>
        <hr>
    </div>


    <div class="centered-container">

        <div class="teams-container-body">

            <div>
                <a href="new_team.php" class="btn btn-outline-primary">+ Nowy zespół</a>
            </div>

            <div class="card teams-table-container">
                <table class="users-table">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nazwa zespołu</th>
                        <th>Liczba kategorii</th>
                        <th>Akcje</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($teams as $team) {
                        ?>
                        <tr>
                            <td><?= $team->getId() ?></td>
                            <td><?= $team->getTeamName() ?></td>
                            <td>0</td>
                            <td>
                                <div class="action-images">
                                    <a href="edit_team.php?id=<?= $team->getId() ?>">
                                        <img alt="edit_image" src="images/pencil.svg">
                                    </a>
                                    <span>|</span>
                                    <a href="delete_team.php?id=<?= $team->getId() ?>">
                                        <img alt="delete_image" src="images/trash.svg">
                                    </a>
                                </div>

                            </td>
                        </tr>
                        <?php
                    } ?>
                    </tbody>
                </table>
            </div>

        </div>


    </div>

</div>

<?php
require_once 'footer.php';
?>
