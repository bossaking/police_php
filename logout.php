<?php
require_once "header.php";

$sessionHelper = new SessionHelper();
$sessionHelper->logOut();

header("Location:index.php");

?>

<?php
require_once 'footer.php';
?>
