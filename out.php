<?php
session_start();
session_destroy();
// unset($_SESSION['auth']);
header("Location: logowanie.php");
exit();
?>