<?php
	
	include_once('../config.php');

	$id = $_GET['id'];

	$sql = "DELETE FROM departamentet WHERE id=:id";
	$prep = $con->prepare($sql);

	$prep->bindParam(':id', $id);


	$prep->execute();

	header("Location: departamentet.php");
	
?>