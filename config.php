<?php

	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "djnbank";

	$conn = mysqli_connect($servername, $username, $password, $dbname);

	if(!$conn){
		die("Connection failed due to error --> ".mysqli_connect_error());
	}

?>