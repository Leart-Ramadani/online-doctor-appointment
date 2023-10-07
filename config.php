<?php
	session_start();
	$host = 'localhost';
	$db_name = 'online_doctor_appointment';
	$user = "root";
	$pass = "";

	try{
		$con = new PDO("mysql:host=$host; dbname=$db_name;", $user, $pass);
	}catch(Exception $e){
		echo "Something went wrong";
	}

?>