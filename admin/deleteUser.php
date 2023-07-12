<?php
	
	include_once('../config.php');

	$id = $_GET['id'];

	$sql = "DELETE FROM users WHERE userType=2 AND id=:id";
	$prep = $con->prepare($sql);

	$prep->bindParam(':id', $id);


	$prep->execute();

	header("Location: doktoret.php");
	
?>