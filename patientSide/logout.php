<?php
    include('../config.php');

    unset($_SESSION['emri']);
    unset($_SESSION['mbiemri']);

    header("Location: login.php");
?>