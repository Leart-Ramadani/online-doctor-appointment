<?php
    include('../config.php');

    unset($_SESSION['admin']);

    header("Location: login.php");
?>