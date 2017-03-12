<?php
$title = "New user registration";
require_once('../scripts/header.php');

//Connect to database
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

//Set vars for sticky form
$username = $firstname = $lastname = $email = $email1 = "";

//Set error vars
$username_error = $firstname_error = $lastname_error = $email_error = $email1_error = "";

//Check to see if form has been submitted
if (isset($_POST['submit'])){
	//Grab previous data
	$username  = mysqli_real_escape_string($dbc, trim($_POST['username']));
	$firstname = mysqli_real_escape_string($dbc, trim($_POST['firstname']));
	$lastname  = mysqli_real_escape_string($dbc, trim($_POST['lastname']));
	$email     = mysqli_real_escape_string($dbc, trim($_POST['email']));
	$email1    = mysqli_real_escape_string($dbc, trim($_POST['email1']));

	//Check username is valid
	if (empty($username)){
		$username_error="Please enter a username";
	} else {
		//Check if username is taken
		$query = "SELECT username FROM tbl_user WHERE username='$username' LIMIT 1";

		//Grab result
		$result = mysqli_query($dbc, $query);

		//Grab rows
		$row = mysqli_fetch_array($result);

		//Check if username was found
		if (mysqli_num_rows($result)!=0){
			$username_error="Username already taken, please choose another";
		}
	}

	//Check firstname has been entered
	if (empty($firstname)){
		$firstname_error="Please enter a first name";
	}

	//Check lastname has been enetered
	if(empty($lastname)){
		$lastname_error="Please enter a last name";
	}


}
?>

<h1><?php echo $title;?></h1>
	<form method="post" action="#">
		<div class="form-group">
			<label for="username">Username</label>
			<input type="text" class="form-control" id="username" placeholder="Username" name="username" value="<?php echo $username;?>"/>
			<div class="error"><?php echo $username_error;?></div>
		</div>

		<div class="form-group">
			<label for="firstname">First name</label>
			<input type="text" class="form-control" id="firstname" placeholder="First name" name="firstname" value="<?php echo $firstname;?>"/>
			<div class="error"><?php echo $firstname_error;?></div>
		</div>

		<div class="form-group">
			<label for="lastname">Last name</label>
			<input type="text" class="form-control" id="lastname" placeholder="Last name" name="lastname" value="<?php echo $lastname;?>"/>
			<div class="error"><?php echo $lastname_error;?></div>
		</div>

		<div class="form-group">
			<label for="email">Email address</label>
			<input type="email" class="form-control" id="email" placeholder="Email address" name="email" value="<?php echo $email;?>"/>
		</div>

		<div class="form-group">
			<label for="email1">Repeat email address</label>
			<input type="email" class="form-control" id="email1" placeholder="Email address" name="email1" value="<?php echo $email1;?>"/>
		</div>

		<div class="form-group">
			<label for="password">Password</label>
			<input type="password" class="form-control" id="password"/>
		</div>

		<div class="form-group">
			<label for="password1">Reenter Password</label>
			<input type="password" class="form-control" id="password1"/>
		</div>

		<button type="submit" name="submit" class="btn btn-default">Register</button>
	</form>

<?php
require_once('../scripts/footer.php');
?>