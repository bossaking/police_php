<?php
require_once 'header.php';
require_once 'DAL/SubmissionRepository.php';


if (!isset($_GET['id'])) {
    header("Location: index.php");
}

$submissionRepo = new SubmissionRepository();
$submission = $submissionRepo->getSubmissionById($_GET['id']);

if ($submission == null || $submission->isAccessCodeShowed()) {
    header("Location: index.php");
}

$submissionRepo->setAccessCodeShowed($submission->getId(), true);

?>

<div class="centered-container">

    <div class="card accepted-submission">

        <span class="header">Dziękujemy, zgłoszenie zostało przyjęte!</span>
        <span class="description">Identyfikator twojego zgłoszenia:</span>
        <hr>
        <span class="identity"><?= $submission->getAccessCode() ?></span>
        <hr>
        <div class="warning">
            <span>Uwaga!</span>
            <p>Zapisz identyfikator twojego zgłoszenia, ponieważ jest on wyświetlany tylko raz!</p>
        </div>

    </div>

</div>

<?php
require_once 'footer.php';
?>
