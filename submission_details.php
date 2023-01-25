<?php

require_once 'header.php';
require_once 'DAL/SubmissionRepository.php';
require_once 'DAL/CommentRepository.php';
require_once 'DAL/UserRepository.php';
require_once 'DAL/TeamRepository.php';
require_once 'enums/SubmissionStatuses.php';

//if(!isset($_GET['id'])){
//    header("Location:submissions.php");
//}

$subRepo = new SubmissionRepository();
$comRepo = new CommentRepository();
$userRepo = new UserRepository();
$teamRepo = new TeamRepository();
$sessionHelper = new SessionHelper();

$loggedUserId = $sessionHelper->getUser()->getId();

$subId = $_GET['id'];

$submission = $subRepo->getSubmissionById($subId);
$comments = $comRepo->getSubmissionComments($subId);

$availableUsers = array();
if ($sessionHelper->getUser()->getMaxRole() == Roles::ADMIN) {
    $availableUsers = $userRepo->getUsersInMaxRole(Roles::ADMIN->value);
} else if ($sessionHelper->getUser()->getMaxRole() == Roles::SUPERIOR) {
    $availableUsers = $userRepo->getUsersInMaxRole(Roles::EMPLOYEE->value);
}

$selectedEmployee = 0;
if ($submission->getEmployee() != null) {
    $selectedEmployee = $submission->getEmployee()->getId();
}


$teams = $teamRepo->getAllTeams();
$selectedTeam = $submission->getTeam()->getId();

$selectedStatus = $submission->getStatus();

if (isset($_POST['send'])) {

    $commentText = $_POST['comment'];
    $employeeId = $loggedUserId;

    $comRepo->createComment($commentText, $subId, $employeeId);
    header("Location: submission_details.php?id=" . $subId);
}

?>

<div style="display: flex; flex-direction: column; gap: 2rem; flex: 1">

    <div class="section-header">
        <span>Zgłoszenie nr. <?= $subId ?></span>
        <hr>
    </div>

    <div class="submission-details-container">

        <div class="submission-details card">

            <span class="submission-author">
                Zgłoszono przez <?= $submission->getUserName() ?> <?= $submission->getUserSurname() ?>, nr. kadrowy: <?= $submission->getUserIdentity() ?>, komenda: <?= $submission->getUserStation() ?>
            </span>
            <span class="submission-title">
                <?= $submission->getTopic() ?>
            </span>
            <span class="submission-description">
                <?= $submission->getDescription() ?>
            </span>
            <hr>

            <form class="form" method="post" action="submission_details.php">

                <div class="form-row">
                    <div class="sub-form-field form-field">
                        <label for="employee">Osoba przypisana: </label>
                        <select id="employee" name="employee">
                            <option value="0" <?php if ($selectedEmployee == 0) echo 'selected'; ?>>Brak</option>
                            <?php
                            foreach ($availableUsers as $availableUser) {
                                ?>
                                <option value="<?= $availableUser->getId() ?>" <?php if ($selectedEmployee == $availableUser->getId()) echo 'selected'; ?>><?= $availableUser->getName() ?> <?= $availableUser->getSurname() ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="sub-form-field form-field">
                        <label for="team">Zespół: </label>
                        <select id="team" name="team">
                            <?php
                            foreach ($teams as $team) {
                                ?>
                                <option value="<?= $team->getId() ?>" <?php if ($selectedTeam == $team->getId()) echo 'selected'; ?>><?= $team->getTeamName() ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="sub-form-field form-field">
                        <label for="status">Status: </label>
                        <select id="status" name="status">
                            <?php
                            foreach (SubmissionStatuses::cases() as $case) {
                                ?>
                                <option value="<?= $case->value ?>" <?php if ($selectedStatus == $case->value) echo 'selected'; ?>><?= SubmissionStatuses::getPolishTranslation($case->value) ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <button type="submit" class="btn btn-primary w-100" id="save" name="save" disabled>Zapisz zmiany
                    </button>
                    <button type="submit" class="btn btn-outline-danger w-100" id="remove" name="remove">Usuń
                        zgłoszenie
                    </button>
                </div>

            </form>

        </div>

        <div class="submission-comments-container">


            <?php
            if (count($comments) == 0) {
                ?>
                <div class="submission-comments no-comments card">
                    <span>Brak komentarzy</span>
                </div>
                <?php
            } else {
                ?>
                <div class="submission-comments card">
                    <?php
                    foreach ($comments as $comment) { ?>
                        <div class="submission-comment">
                            <span class="author"><?= $comment->getEmployee()->getName() ?> <?= $comment->getEmployee()->getSurname() ?> pisze:</span>
                            <hr>
                            <span class="text"><?= $comment->getText() ?></span>
                        </div>
                        <?php
                    } ?>
                </div>
                <?php
            }
            ?>


            <div class="submission-comment-form card">
                <form method="post" action="submission_details.php?id=<?= $subId ?>"
                      style="display: flex; flex-direction: column; gap: 1rem">
                    <div class="form-row">
                        <div class="form-field">
                            <label for="comment" hidden></label>
                            <textarea placeholder="Wpisz coś..." rows="3" name="comment" id="comment"></textarea>
                        </div>
                    </div>
                    <div style="display: flex; flex-direction: row; justify-content: end">
                        <button type="submit" class="btn btn-outline-access" style="width: 20%;" disabled id="send"
                                name="send">Wyślij
                        </button>
                    </div>
                </form>
            </div>

        </div>

    </div>
</div>


<script>

    let employeeSelect = document.getElementById('employee');
    let statusSelect = document.getElementById('status');
    let teamSelect = document.getElementById('team');
    let saveBtn = document.getElementById('save');

    employeeSelect.addEventListener('change', setSaveBtnActive);
    statusSelect.addEventListener('change', setSaveBtnActive);
    teamSelect.addEventListener('change', setSaveBtnActive);

    function setSaveBtnActive() {
        saveBtn.disabled = false;
    }

    let comment = document.getElementById('comment');
    let sendBtn = document.getElementById('send');

    comment.addEventListener('input', () => {
        sendBtn.disabled = comment.value.trim() === "";
    });


</script>

<?php
require_once 'footer.php';
?>
