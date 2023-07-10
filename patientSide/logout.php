<?php
    include('../config.php');

    unset($_SESSION['emri']);
    unset($_SESSION['mbiemri']);
    unset($_SESSION['username']);
    unset($_SESSION['numri_personal']);

    header("Location: login.php");
?>