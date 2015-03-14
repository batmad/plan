<?php
session_start();
if (!(isset($_SESSION['is_personal']))) {
    header("Location:auth.php");
    die();
}
if (!($_SESSION['is_personal'])) {
    header("Location:auth.php");
    die();
}
?>