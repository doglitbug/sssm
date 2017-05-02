<?php
$title = "Log in";
require_once('../scripts/startsession.php');
require_once('../scripts/databaseconnection.php');
require_once('../scripts/header.php');

//Check not already logged in!
if (isset($_SESSION['username'])){
	echo "<p>Already logged in as ".$_SESSION['username']."!</p>\n";
	echo "<a href='../index.php'>Click here to return to main page</a>\n";
	require_once('../scripts/footer.php');
	//TODO redirect user
	die();

}

//Set vars for sticky form
$username = "";

//Set error vars
$username_error = $password_error = $login_error = "";

//Check to see if form has been submitted
if (isset($_POST['submit'])){
	//Grab previous data
	$username  = mysqli_real_escape_string($dbc, trim($_POST['username']));
	$password = mysqli_real_escape_string($dbc, trim($_POST['password']));

	//Check username has been enetered
	if (empty($username)){
		$username_error="Please enter a username";
	} elseif (empty($password)){
		$password_error = "Please enter your password";
	} else {
		//Username and password have been entered
		//Build query
		$query = "SELECT * FROM tbl_user WHERE username='$username' LIMIT 1";
		$result = mysqli_query($dbc, $query) or die('Couldn\'t search for user: ') . mysqli_error($dbc);
		//Grab rows
		$row = mysqli_fetch_array($result);

		//Check if username was found, then that the password is correct
		if (mysqli_num_rows($result)!=0 && (password_verify($password, $row['password']))){
			//Log in user
			$_SESSION['user_id']=$row['user_id'];
			$_SESSION['username']=$row['username'];
			$_SESSION['manager']=$row['manager'];

			//Redirect user to homepage
			header("Location: /../index.php");
			die();
		} else {
			$login_error="Username and/or password invalid, please try again";
		}
	}
}
?>
<h1>Log in</h1>
<form method="post" action="#">
	<div class="form-group">
		<label for="username">Username</label>
		<input type="text" class="form-control" id="username" placeholder="Username" name="username" value="<?php echo $username;?>"/>
		<div class="error"><?php echo $username_error;?></div>		
	</div>

	<div class="form-group">
		<label for="password">Password</label>
		<input type="password" class="form-control" id="password" placeholder="Password" name="password"/>
		<div class="error"><?php echo $password_error;?></div>	
	</div>

	<div class="error"><?php echo $login_error;?></div>

	<button type="submit" name="submit" class="btn btn-default">Log in</button>
</form>

<?php
require_once('../scripts/footer.php');
?>