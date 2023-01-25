<?php
require_once 'header.php';
require_once 'DAL/SubmissionRepository.php';
require_once 'enums/SubmissionStatuses.php';

$subRepo = new SubmissionRepository();
$sessionHelper = new SessionHelper();

$statusId = 0;
if (isset($_GET['status'])) {
    $statusId = $_GET['status'];
}

$submissions = array();

if ($sessionHelper->isLoggedIn()) {

    if ($sessionHelper->getUser()->getMaxRole() == Roles::EMPLOYEE) {
        $submissions = $subRepo->getSubmissionsByStatusAndEmployee($statusId, $sessionHelper->getUser()->getId());
    } else if ($sessionHelper->getUser()->userInRole(Roles::SUPERIOR)) {
        $submissions = $subRepo->getSubmissionsByStatus($statusId);
    }

}


if (isset($_GET['search'])) {

    $accessCode = $_GET['access_code'];
    $userIdentity = $_GET['user_identity'];
    $submission = $subRepo->getSubmissionByAccessCode($accessCode, $userIdentity);
    if ($submission != null) {
        $submissions[] = $submission;
    }

}

?>
<div style="display: flex; flex-direction: column; gap: 2rem">

    <div class="section-header">
        <span>Zgłoszenia</span>
        <hr>
    </div>


    <div class="filters-container">
        <div class="submissions-filters-container card">
            <form class="form filters-form" method="get" action="submissions.php">

                <?php
                if ($sessionHelper->isLoggedIn()) {
                    ?>

                    <div class="form-row">
                        <div class="form-field">
                            <label for="status" style="display: none"></label>
                            <select name="status" id="status">
                                <option value="0">Wszystkie</option>
                                <?php
                                foreach (SubmissionStatuses::cases() as $case) {
                                    ?>
                                    <option <?php if ($statusId == $case->value) echo 'selected'; ?>
                                            value="<?= $case->value ?>"><?= SubmissionStatuses::getPolishTranslation($case->value) ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-outline-access">Zastosuj</button>
                    <?php
                } else {
                    ?>

                    <div class="form-row">
                        <div class="form-field">
                            <label for="access_code" style="display: none"></label>
                            <input type="text" name="access_code" id="access_code" placeholder="Kod zgłoszenia">
                        </div>
                        <div class="form-field">
                            <label for="user_identity" style="display: none"></label>
                            <input type="text" name="user_identity" id="user_identity" placeholder="Numer kadrowy">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-outline-access" name="search" id="search">Szukaj</button>
                <?php } ?>

            </form>
        </div>
    </div>

    <div class="submissions-container">
        <?php
        if (count($submissions) == 0 && $sessionHelper->isLoggedIn()) {
            ?>
            <span class="no-comments">Brak zgłoszeń</span>
            <?php
        }
        ?>

        <?php
        if (count($submissions) == 0 && !$sessionHelper->isLoggedIn() && isset($_GET['access_code'])) {
            ?>
            <span class="no-comments" style="color: #e01e1e">Zgłoszenie nie zostało znalezione</span>
            <?php
        }
        ?>

        <?php
        foreach ($submissions as $submission) {

            $employeeCredentials = 'Brak';

            if ($submission->getEmployee() != null) {
                $employeeCredentials = $submission->getEmployee()->getName() . " " . $submission->getEmployee()->getSurname();
            }

            ?>
            <a href="submission_details.php?id=<?= $submission->getId() ?><?php if (!$sessionHelper->isLoggedIn()) echo '&access_code=' . $accessCode . '&user_identity=' . $userIdentity; ?>"
               class="submission card">
                <div class="submission-header">
                    <div class="submission-sub-header">
                        <span class="title"><?= $submission->getTopic() ?></span>
                        <span class="team"><?= $submission->getTeam()->getTeamName() ?></span>
                    </div>
                    <span class="submission-status <?= SubmissionStatuses::getClassName($submission->getStatus()) ?>"><?= SubmissionStatuses::getPolishTranslation($submission->getStatus()) ?></span>
                </div>

                <hr>

                <div class="submission-body">
                    <span class="responded-by">Osoba zgłaszająca: <?= $submission->getUserName() ?> <?= $submission->getUserSurname() ?> (<?= $submission->getUserIdentity() ?>)</span>
                    <span class="responded-by">Komisariat: <?= $submission->getUserStation() ?></span>
                    <hr>
                    <span class="responded-by"
                          style="color: #567356">Osoba przypisana: <?= $employeeCredentials ?></span>
                </div>

            </a>

            <?php
        }
        ?>

    </div>

</div>

<?php
require_once 'footer.php';
?>
