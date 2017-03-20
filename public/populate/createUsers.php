<?php
$title = "Create new users";
require_once('../scripts/header.php');
//Connect to database
$GLOBALS['dbc'] = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

//Turn off key checks
mysqli_query($GLOBALS['dbc'], 'SET foreign_key_checks = 0');

//TODO Delete previous users

createUser("1","doglitbug","password","Arron","Dick","1","s_drac2@yahoo.com");
createUserContact("1","0273655228","","doglitbug","cellphone");

createUser("2","arthur","password","Arthur","Gumball","0","agumball@email.com");
createUserContact("2","02112345678","034132152","a.gumball","cellphone");

//Turn back on the key checks
mysqli_query($GLOBALS['dbc'], 'SET foreign_key_checks = 1');

function createUser($user_id, $username, $password, $firstname, $lastname, $manager, $email){
	//Encrpyt password
	$hashed_password = password_hash($password, PASSWORD_DEFAULT);
	
	//Build INSERT query
	$query = "INSERT INTO tbl_user (user_id, username, password, firstname, lastname, manager) VALUES ('$user_id', '$username', '$hashed_password', '$firstname', '$lastname', '$manager')";

	//Execute query
	mysqli_query($GLOBALS['dbc'], $query) or die('Couldn\'t add user: '.mysqli_error($GLOBALS['dbc']));

	//Build query to create user contact
	$query = "INSERT INTO tbl_contact (user_id, email) VALUES ('$user_id', '$email')";
	//Insert contact details
	mysqli_query($GLOBALS['dbc'], $query) or die('Couldn\'t add new user contact details: ' . mysqli_error($GLOBALS['dbc']));

	echo "Created user: ".$username."<br/>\n";
}

function createUserContact($user_id, $cellphone, $landline, $facebook, $preferred){
	//Build UPDATE query
	$query = "UPDATE tbl_contact SET cellphone='$cellphone', landline='$landline', facebook='$facebook', preferred='$preferred' WHERE user_id='$user_id'";

	//Execute query
	mysqli_query($GLOBALS['dbc'], $query) or die('Couldn\'t add user contact details: ' . mysqli_error($GLOBALS['dbc']));

	echo "Created contact details<br/>\n";
}

require_once('../scripts/footer.php');
?>