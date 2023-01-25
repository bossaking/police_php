<?php

require_once "header.php";
require_once "DAL/TeamRepository.php";
require_once "DAL/SubmissionRepository.php";

$name = '';
$surname = '';
$identity = '';
$station = '';
$topic = '';
$description = '';
$selectedTeam = 1;

$teamRepo = new TeamRepository();
$teams = $teamRepo->getAllTeams();

if(isset($_POST['create'])){

    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $identity = $_POST['identity'];
    $station = $_POST['station'];
    $topic = $_POST['topic'];
    $description = $_POST['description'];
    $selectedTeam = $_POST['team'];

    $newSubmissionId = (new SubmissionRepository())->createSubmission($name, $surname, $identity, $station, $selectedTeam, $topic, $description);
    header("Location: submission_accepted.php?id=".$newSubmissionId);
}

if(isset($_POST['reset'])){
    header("Location: index.php");
}

?>

<div class="centered-container">

    <form method="post" action="index.php" class="form card">

        <div class="form-header">
            <span>Nowe zgłoszenie</span>
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
                <input type="text" name="name" id="name" required value="<?= $name ?>" placeholder="Jan">
            </div>

            <div class="form-field">
                <label for="surname">Nazwisko</label>
                <input type="text" name="surname" id="surname" required value="<?= $surname ?>" placeholder="Kowalski">
            </div>
        </div>

        <div class="form-row">
            <div class="form-field">
                <label for="identity">Identyfikator kadrowy</label>
                <input type="text" name="identity" id="identity" required value="<?= $identity ?>" placeholder="Np. 1E2345">
            </div>

            <div class="form-field">
                <label for="station">Komisariat</label>
                <input type="text" name="station" id="station" required value="<?= $station ?>" placeholder="Np. Wrocław-Rakowiec">
            </div>
        </div>

        <div class="form-row">
            <div class="form-field">
                <label for="team">Zespół</label>
                <select id="team" name="team">
                    <?php
                    foreach ($teams as $team) {
                        ?>
                        <option <?php if ($selectedTeam == $team->getId()) echo 'selected'; ?>
                            value="<?= $team->getId() ?>"><?= $team->getTeamName() ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-field">
                <label for="topic">Temat</label>
                <input type="text" name="topic" id="topic" required value="<?= $topic ?>" placeholder="Np. brak wymaganego dostępu do systemu">
            </div>
        </div>

        <div class="form-row">
            <div class="form-field">
                <label for="description">Opis problemu</label>
                <textarea name="description" id="description" required rows="5"><?= $description ?></textarea>
            </div>
        </div>

        <div class="form-row">
            <button type="submit" class="btn btn-access w-100" name="create" id="create">Utwórz</button>
            <button type="submit" class="btn btn-outline-danger w-100" name="reset" id="reset">Zresetuj</button>
        </div>

    </form>

</div>

<?php

require_once "footer.php";

?>


