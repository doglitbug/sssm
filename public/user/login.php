<?php
$title = "Log in";
require_once('../scripts/header.php');

//Connect to database
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

//Set vars for sticky form
$username = "";

//Set error vars
$login_error = "";

//Check to see if form has been submitted
if (isset($_POST['submit'])){
	//Grab previous data
	$username  = mysqli_real_escape_string($dbc, trim($_POST['username']));
	$password = mysqli_real_escape_string($dbc, trim($_POST['password']));

	//TODO Log in user

}

?>

<h1>Log in</h1>
<form method="post" action="#">
	<div class="form-group">
		<label for="username">Username</label>
		<input type="text" class="form-control" id="username" placeholder="Username" name="username"/>
	</div>

	<div class="form-group">
		<label for="password">Password</label>
		<input type="password" class="form-control" id="password" placeholder="Password" name="password"/>
	</div>

	<div class="error"><?php echo $login_error;?></div>

	<button type="submit" name="submit" class="btn btn-default">Log in</button>
</form>

<?php
require_once('../scripts/footer.php');
?>