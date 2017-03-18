<?php
$title = "Log out";
require_once('../scripts/header.php');

unset($_SESSION);
session_destroy();
session_write_close();
?>
<h1>Logged out</h1>
<ul>
	<li><a href="login.php">Check here to log back in</a></li>
	<li><a href="../index.php">Click here to return to home page</a></li>
</ul>

<?php
require_once('../scripts/footer.php');
?>