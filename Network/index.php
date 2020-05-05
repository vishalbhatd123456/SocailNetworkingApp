<?php

	$con = mysqli_connect("localhost","root","","social"); 
	if(mysqli_connect_errno())
	{
		echo "Failed to connect" . mysql_connect_errno();
	}

	$query = mysqli_query($con, "INSERT INTO test VALUES ('','Vishal')");



?>

<!DOCTYPE html>
<html>
<head>
	<title> Swirl Field</title>
</head>
<body>
	<p> Hello Vishal </p>

</body>
</html>