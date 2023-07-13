<?php
	include('../config.php');

	$id = $_GET['id'];

	$sql = "DELETE FROM terminet WHERE id=:id AND statusi='Completed'";
	$prep = $con->prepare($sql);

	$prep->bindParam(':id', $id);


	$prep->execute();



	header("Location: historiaTerminit.php")



?>