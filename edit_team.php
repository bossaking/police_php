<?php
require_once 'header.php';
require_once 'DAL/TeamRepository.php';

$sessionHelper = new SessionHelper();
if (!$sessionHelper->isLoggedIn() || !$sessionHelper->getUser()->userInRole(Roles::ADMIN)) {
    header("Location: index.php");
}

$teamName = "";
$teamId = 0;
$teamRepo = new TeamRepository();

if(!isset($_GET['id']) && !isset($_POST['save'])){
    header("Location: teams.php");
}

if(isset($_GET['id']) && !isset($_POST['save'])){

    $teamId = $_GET['id'];
    $teamName = $teamRepo->getTeamById($teamId)->getTeamName();
}

if(isset($_POST['save'])){

    $teamName = $_POST['team_name'];
    $teamId = $_POST['team_id'];


    if($teamName != $teamRepo->getTeamById($teamId)->getTeamName() && $teamRepo->teamExists($teamName)){
        $error = "Zespół o podanej nazwie już istnieje";
    }else{
        $teamRepo->updateTeam($teamId, $teamName);
        header("Location: teams.php");
    }

}

?>

<div class="centered-container">

    <form method="post" action="edit_team.php" class="form card">

        <input type="hidden" value="<?=$teamId?>" name="team_id" id="team_id">

        <div class="form-header">
            <span>Edycja zespołu</span>
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
                <label for="team_name">Nazwa zespołu</label>
                <input type="text" name="team_name" id="team_name" placeholder="Np. zespół informatyczny" required value="<?= $teamName ?>">
            </div>
        </div>

        <div class="form-row">
            <button type="submit" class="btn btn-access w-100" name="save" id="save">Zapisz</button>
            <button type="button" class="btn btn-outline-danger w-100" onclick="history.go(-1);">Wstecz</button>
        </div>

    </form>

</div>

<?php
require_once 'footer.php';
?>
