<?php
//Start the session
require_once('startsession.php');

//Set up vars for database connection
require_once('connectvars.php');

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
		<meta name="description" content="Simple Staff Schedule Management - Provides online scheduling to replace onsite paper systems" />
		<meta name="author" content="Arron Dick" />

		<title>
		<?php
		//Check the calling page has provided a page title to be used, otherwise default to Application name
		if (isset($title)){
			echo $title;
		} else {
			echo "SSSM";
		}
		?>
		</title>

		<link rel="stylesheet" type="text/css" href="scripts/styles.css" />

		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
	</head>
	<body>
		<div class="container">

<?php
//Check if login is required and not logged in
if (isset($login) && $login == true && !isset($_SESSION['username'])){
	echo "<h1>Login required</h1>\n";
	echo "<ul>\n";
	echo "<li><a href='../user/login.php'>Click here to log in</a></li>\n";
	echo "<li><a href='../index.php'>Click here to return to home page</a></li>\n";
	echo "</ul>\n";
	require_once('footer.php');
	die();
}
?>