<?php
include('../config.php');
if(isset($_POST['popEdit']) && $_POST['popEdit'] == true){
    unset($_SESSION['popEdit']);
    $id = $_POST['idEdit'];
    $_SESSION['popEdit'] = $id;


    echo $_SESSION['popEdit'];
}