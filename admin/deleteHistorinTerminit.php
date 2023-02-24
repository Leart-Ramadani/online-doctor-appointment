<?php
	include('../config.php');

	$id = $_GET['id'];

	$sql = "DELETE FROM historia_e_termineve WHERE id=:id";
	$prep = $con->prepare($sql);

	$prep->bindParam(':id', $id);


	$prep->execute();



	header("Location: historiaTerminit.php")



?>