<?php
$servername = "localhost";
$username = "root";
$password = "";
$dboper = "dbsroar";


$con = new mysqli($servername, $username, $password, $dboper);
if ($con->connect_error) 
{
	die("Connect Error: " . $conn->connect_error);
}
else
{
   
}

?>