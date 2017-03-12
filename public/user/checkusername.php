<?php

//Start the session
require_once('../scripts/startsession.php');

//Set up vars for database connection
require_once('../scripts/connectvars.php');

//Connect to database
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

//Get username parameter from URL
//TODO Check parameter is supplied
$username = mysqli_real_escape_string($dbc, trim($_GET['username']));

//See if entry already exists in database
$query = "SELECT username FROM tbl_user WHERE username='$username' LIMIT 1";

//Grab result
$result = mysqli_query($dbc, $query);

//Grab rows
$row = mysqli_fetch_array($result);

//Check if username was found
if (mysqli_num_rows($result)==0){
	echo "Available";
} else {
	echo "Taken";
}
?>