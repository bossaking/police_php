<?php
require_once 'header.php';
require_once 'DAL/TeamRepository.php';
require_once 'DAL/SubmissionRepository.php';

$sessionHelper = new SessionHelper();
if (!$sessionHelper->isLoggedIn() || !$sessionHelper->getUser()->userInRole(Roles::ADMIN)) {
    header("Location: index.php");
}

$teamRepo = new TeamRepository();
$subRepo = new SubmissionRepository();

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
                        <th>Aktywne zgłoszenia</th>
                        <th>Akcje</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($teams as $team) {
                        $subCount = $subRepo->getSubmissionsCountInTeam($team->getId());
                        ?>
                        <tr>
                            <td><?= $team->getId() ?></td>
                            <td><?= $team->getTeamName() ?></td>
                            <td><?= $subCount ?></td>
                            <td>
                                <div class="action-images">
                                    <?php
                                    if ($subCount == 0) {
                                        ?>

                                        <a href="edit_team.php?id=<?= $team->getId() ?>">
                                            <img alt="edit_image" src="images/pencil.svg">
                                        </a>
                                        <span>|</span>
                                        <a href="delete_team.php?id=<?= $team->getId() ?>">
                                            <img alt="delete_image" src="images/trash.svg">
                                        </a>

                                        <?php
                                    } else {
                                        ?>
                                        <a class="tooltip">
                                            <span class="tooltip-text">Nie można zedytować lub usunąć zespołu z przypisanymi zgłoszeniami</span>
                                            <img alt="info_image" src="images/info.svg">
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


    </div>

</div>

<?php
require_once 'footer.php';
?>
