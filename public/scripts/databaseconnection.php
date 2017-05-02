<?php
//Set up vars for database connection
require_once('connectvars.php');
//Connect to database
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
?>