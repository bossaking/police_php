<?php
require_once 'header.php';
require_once 'DAL/TeamRepository.php';

$sessionHelper = new SessionHelper();
if (!$sessionHelper->isLoggedIn() || !$sessionHelper->getUser()->userInRole(Roles::ADMIN)) {
    header("Location: index.php");
}

if (!isset($_GET['id'])) {
    header("Location: teams.php");
}

(new TeamRepository())->deleteTeam($_GET['id']);
header("Location: teams.php");

?>

<?php
require_once 'footer.php';
?>
