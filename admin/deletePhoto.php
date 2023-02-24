<?php
    include('../config.php');
    $id = $_GET['id'];

    $sql = "DELETE FROM galeria WHERE id=:id";
    $prep = $con->prepare($sql);
    $prep->bindParam(':id', $id);
    $prep->execute();
    header("Location: galeria.php");
?>