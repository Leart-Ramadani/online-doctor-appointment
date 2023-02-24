<?php
	
	include_once('../config.php');

	$id = $_GET['id'];

	$sql = "DELETE FROM orari WHERE id=:id";
	$prep = $con->prepare($sql);

	$prep->bindParam(':id', $id);


	$prep->execute();

	header("Location: orari.php");
	
?>