<?php
session_start();
session_unset();
session_destroy();
header("Location: ../main/login.php"); // Redirect to login page
exit();
?>
