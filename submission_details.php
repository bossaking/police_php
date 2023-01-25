<?php

require_once 'header.php';
require_once 'DAL/SubmissionRepository.php';
require_once 'DAL/CommentRepository.php';
require_once 'DAL/UserRepository.php';
require_once 'DAL/TeamRepository.php';
require_once 'enums/SubmissionStatuses.php';

if (!isset($_GET['id'])) {
    header("Location:submissions.php");
}


$subRepo = new SubmissionRepository();
$comRepo = new CommentRepository();
$userRepo = new UserRepository();
$teamRepo = new TeamRepository();
$sessionHelper = new SessionHelper();


$subId = $_GET['id'];


$submission = $subRepo->getSubmissionById($subId);


if (!$sessionHelper->isLoggedIn()) {
    if (!isset($_GET['access_code']) || !isset($_GET['user_identity']) || $_GET['access_code'] != $submission->getAccessCode() || $_GET['user_identity'] != $submission->getUserIdentity()) {
        header("Location:submissions.php");
    }
} else if ($sessionHelper->getUser()->getMaxRole() == Roles::EMPLOYEE) {


    if ($submission->getEmployee() == null) {
        header("Location:submissions.php");
    }

    if ($sessionHelper->getUser()->getId() != $submission->getEmployee()->getId()) {
        header("Location:submissions.php");
    }

}


$comments = $comRepo->getSubmissionComments($subId);

$availableUsers = $userRepo->getUsersInMaxRole(Roles::EMPLOYEE->value);


$selectedEmployee = 0;
if ($submission->getEmployee() != null) {
    $selectedEmployee = $submission->getEmployee()->getId();
}


$teams = $teamRepo->getAllTeams();
$selectedTeam = $submission->getTeam()->getId();

$selectedStatus = $submission->getStatus();

if (isset($_POST['send']) && $sessionHelper->isLoggedIn()) {

    $commentText = $_POST['comment'];
    $employeeId = $sessionHelper->getUser()->getId();

    $comRepo->createComment($commentText, $subId, $employeeId);
    header("Location: submission_details.php?id=" . $subId);
}

if (isset($_POST['save']) && $sessionHelper->isLoggedIn() && $sessionHelper->getUser()->userInRole(Roles::SUPERIOR)) {

    $selectedEmployee = $_POST['employee'];
    $selectedTeam = $_POST['team'];

    $subRepo->updateSubmission($subId, $selectedTeam, $selectedEmployee);
    header("Location: submission_details.php?id=" . $subId);
}

if (isset($_POST['start']) && $sessionHelper->isLoggedIn() && $sessionHelper->getUser()->userInRole(Roles::EMPLOYEE)) {
    $subRepo->changeSubmissionStatus($subId, SubmissionStatuses::IN_PROGRESS->value);
    header("Location: submission_details.php?id=" . $subId);
}

if (isset($_POST['close']) && $sessionHelper->isLoggedIn() && $sessionHelper->getUser()->userInRole(Roles::EMPLOYEE)) {
    $subRepo->changeSubmissionStatus($subId, SubmissionStatuses::CLOSED->value);
    header("Location: submission_details.php?id=" . $subId);
}

if (isset($_POST['start_again']) && $sessionHelper->isLoggedIn() && $sessionHelper->getUser()->userInRole(Roles::EMPLOYEE)) {
    $subRepo->changeSubmissionStatus($subId, SubmissionStatuses::IN_PROGRESS->value);
    header("Location: submission_details.php?id=" . $subId);
}

if (isset($_POST['remove']) && $sessionHelper->isLoggedIn() && $sessionHelper->getUser()->userInRole(Roles::SUPERIOR)) {
    $comRepo->deleteAllSubmissionComments($subId);
    $subRepo->deleteSubmission($subId);
    header("Location: submissions.php");
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

            <form class="form" method="post" action="submission_details.php?id=<?= $subId ?>">

                <div class="form-row">
                    <div class="sub-form-field form-field">
                        <label for="employee">Osoba przypisana: </label>
                        <?php
                        if ($sessionHelper->isLoggedIn() && $sessionHelper->getUser()->userInRole(Roles::SUPERIOR)) {
                            ?>
                            <select id="employee"
                                    name="employee" <?php if ($submission->getStatus() == SubmissionStatuses::CLOSED->value) echo 'disabled'; ?>>
                                <option value="0" <?php if ($selectedEmployee == 0) echo 'selected'; ?>>Brak</option>
                                <?php
                                foreach ($availableUsers as $availableUser) {
                                    ?>
                                    <option value="<?= $availableUser->getId() ?>" <?php if ($selectedEmployee == $availableUser->getId()) echo 'selected'; ?>><?= $availableUser->getName() ?> <?= $availableUser->getSurname() ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        <?php } else {

                            $employee = 'Brak';
                            if ($submission->getEmployee() != null) {
                                $employee = $submission->getEmployee()->getName() . " " . $submission->getEmployee()->getSurname();
                            }

                            ?>
                            <span class="submission-status-span"><?= $employee ?></span>
                        <?php } ?>
                    </div>
                </div>

                <div class="form-row">
                    <div class="sub-form-field form-field">
                        <label for="team">Zespół: </label>
                        <?php
                        if ($sessionHelper->isLoggedIn() && $sessionHelper->getUser()->userInRole(Roles::SUPERIOR)) {
                            ?>
                            <select id="team"
                                    name="team" <?php if ($submission->getStatus() == SubmissionStatuses::CLOSED->value) echo 'disabled'; ?>>
                                <?php
                                foreach ($teams as $team) {
                                    ?>
                                    <option value="<?= $team->getId() ?>" <?php if ($selectedTeam == $team->getId()) echo 'selected'; ?>><?= $team->getTeamName() ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        <?php } else { ?>
                            <span class="submission-status-span"><?= $submission->getTeam()->getTeamName() ?></span>
                        <?php } ?>
                    </div>
                </div>

                <div class="form-row">
                    <div class="sub-form-field form-field">
                        <label for="status">Status: </label>
                        <span class="submission-status-span <?= SubmissionStatuses::getClassName($submission->getStatus()) ?>"><?= SubmissionStatuses::getPolishTranslation($submission->getStatus()) ?></span>
                    </div>
                </div>

                <?php
                if ($sessionHelper->isLoggedIn() && $sessionHelper->getUser()->userInRole(Roles::EMPLOYEE)) {
                    ?>

                    <div class="form-row">
                        <?php
                        if ($submission->getStatus() == SubmissionStatuses::OPENED->value) {
                            ?>
                            <button type="submit" class="btn btn-access w-100" id="start"
                                    name="start" <?php if ($submission->getEmployee() == null) echo 'disabled'; ?>>
                                Rozpocznij pracę
                            </button>
                            <?php
                        }
                        ?>

                        <?php
                        if ($submission->getStatus() == SubmissionStatuses::IN_PROGRESS->value) {
                            ?>
                            <button type="submit" class="btn btn-outline-warn w-100" id="close" name="close">Zamknij
                                zgłoszenie
                            </button>
                            <?php
                        }
                        ?>

                        <?php
                        if ($submission->getStatus() == SubmissionStatuses::CLOSED->value) {
                            ?>
                            <button type="submit" class="btn btn-outline-access w-100" id="start_again"
                                    name="start_again">
                                Otwórz ponownie
                            </button>
                            <?php
                            if ($sessionHelper->isLoggedIn() && $sessionHelper->getUser()->userInRole(Roles::SUPERIOR)) {
                                ?>
                                <button type="submit" class="btn btn-outline-danger w-100" id="remove" name="remove">
                                    Usuń
                                    zgłoszenie
                                </button>
                            <?php } ?>
                            <?php
                        }
                        ?>

                        <?php
                        if ($submission->getStatus() != SubmissionStatuses::CLOSED->value && $sessionHelper->isLoggedIn() && $sessionHelper->getUser()->userInRole(Roles::SUPERIOR)) {
                            ?>
                            <button type="submit" class="btn btn-primary w-100" id="save" name="save" disabled>Zapisz
                                zmiany
                            </button>
                            <?php
                        }
                        ?>

                    </div>

                <?php } ?>

            </form>

        </div>

        <div class="submission-comments-container">

            <?php
            if (count($comments) == 0) {
                ?>
                <div class="submission-comments no-comments card <?php if ($submission->getStatus() == SubmissionStatuses::CLOSED->value || !$sessionHelper->isLoggedIn()) echo 'closed-submission-comments'; ?>">
                    <span>Brak komentarzy</span>
                </div>
                <?php
            } else {
                ?>
                <div class="submission-comments card <?php if ($submission->getStatus() == SubmissionStatuses::CLOSED->value || !$sessionHelper->isLoggedIn()) echo 'closed-submission-comments'; ?>">
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

            <?php if ($submission->getStatus() != SubmissionStatuses::CLOSED->value && $sessionHelper->isLoggedIn()) { ?>
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
                <?php
            }
            ?>

        </div>

    </div>
</div>


<script>

    let employeeSelect = document.getElementById('employee');
    let teamSelect = document.getElementById('team');
    let saveBtn = document.getElementById('save');

    employeeSelect?.addEventListener('change', setSaveBtnActive);
    teamSelect?.addEventListener('change', setSaveBtnActive);

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
