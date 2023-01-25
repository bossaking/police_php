<?php
require_once 'header.php';
require_once 'DAL/SubmissionRepository.php';
require_once 'enums/SubmissionStatuses.php';

$subRepo = new SubmissionRepository();

$statusId = 0;
if (isset($_GET['status'])) {
    $statusId = $_GET['status'];
}

$submissions = $subRepo->getSubmissionsByStatus($statusId);

?>
<div style="display: flex; flex-direction: column; gap: 2rem">

    <div class="section-header">
        <span>Zgłoszenia</span>
        <hr>
    </div>


    <div class="filters-container">
        <div class="submissions-filters-container card">
            <form class="form filters-form" method="get" action="submissions.php">
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
            </form>
        </div>
    </div>

    <div class="submissions-container">

        <?php
        foreach ($submissions as $submission) {

            $employeeCredentials = 'Brak';

            if ($submission->getEmployee() != null) {
                $employeeCredentials = $submission->getEmployee()->getName() . " " . $submission->getEmployee()->getSurname();
            }

            ?>
            <a href="submission_details.php?id=<?=$submission->getId()?>" class="submission card">
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
                    <span class="responded-by" style="color: #567356">Osoba przypisana: <?= $employeeCredentials ?></span>
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
