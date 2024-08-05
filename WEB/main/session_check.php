<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../main/login.php");
    exit();
} else {
    $session_valid = true;
}