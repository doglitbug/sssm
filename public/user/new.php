<?php
$title = "New user registration";
require_once('../scripts/header.php');
?>
<h1><?php echo $title;?></h1>
	<form method="post" action="">
		<div class="form-group">
			<label for="username">Username</label>
			<input type="text" class="form-control" id="username" placeholder="Username"/>
		</div>

		<div class="form-group">
			<label for="firstname">First name</label>
			<input type="text" class="form-control" id="firstname" placeholder="First name"/>
		</div>

		<div class="form-group">
			<label for="lastname">Last name</label>
			<input type="text" class="form-control" id="lastname" placeholder="Last name"/>
		</div>

		<div class="form-group">
			<label for="email">Email address</label>
			<input type="email" class="form-control" id="email" placeholder="Email address"/>
		</div>

		<div class="form-group">
			<label for="email1">Repeat email address</label>
			<input type="email" class="form-control" id="email1" placeholder="Email address"/>
		</div>

		<div class="form-group">
			<label for="password">Password</label>
			<input type="password" class="form-control" id="password"/>
		</div>

		<div class="form-group">
			<label for="password1">Reenter Password</label>
			<input type="password" class="form-control" id="password1"/>
		</div>

		<button type="submit" class="btn btn-default">Sumbit</button>
	</form>

<?php
require_once('../scripts/footer.php');
?>