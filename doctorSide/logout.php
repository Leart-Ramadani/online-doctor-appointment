<?php
    include('../config.php');

    unset($_SESSION['doctor']);

    header("Location: login.php");
?>