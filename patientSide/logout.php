<?php
    include('../config.php');

    unset($_SERVER['fullName']);
    unset($_SESSION['username']);
    unset($_SESSION['numri_personal']);

    header("Location: login.php");
?>