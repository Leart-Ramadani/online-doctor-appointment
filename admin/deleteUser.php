<?php
	
	include_once('../config.php');

	$id = $_GET['id'];

	$sql = "DELETE FROM doctor_personal_info WHERE id=:id";
	$prep = $con->prepare($sql);

	$prep->bindParam(':id', $id);


	$prep->execute();

	header("Location: doctors.php");
	
?>