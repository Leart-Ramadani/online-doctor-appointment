<?php
    include('../config.php');

    $id = $_GET['id'];

    $sql = "DELETE FROM prices WHERE id='$id'";
    $prep = $con->prepare($sql);
    $prep->execute();

    header("Location: prices.php");
?>