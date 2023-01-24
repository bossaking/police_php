<?php
require_once 'header.php';
require_once 'DAL/TeamRepository.php';

$sessionHelper = new SessionHelper();
if (!$sessionHelper->isLoggedIn() || !$sessionHelper->getUser()->userInRole(Roles::ADMIN)) {
    header("Location: index.php");
}

$teamName = "";

if(isset($_POST['create'])){

    $teamName = $_POST['team_name'];

    $repo = new TeamRepository();

    if($repo->teamExists($teamName)){
        $error = "Zespół o podanej nazwie już istnieje";
    }else{
        $repo->createTeam($teamName);
        header("Location: teams.php");
    }

}

?>

<div class="centered-container">

    <form method="post" action="new_team.php" class="form card">

        <div class="form-header">
            <span>Nowy zespół</span>
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
            <button type="submit" class="btn btn-access w-100" name="create" id="create">Utwórz</button>
        </div>

    </form>

</div>

<?php
require_once 'footer.php';
?>
