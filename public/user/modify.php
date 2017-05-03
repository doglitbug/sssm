<?php
$title = "Modify staff member";
$login = true;
require_once('../scripts/header.php');

//TODO Check to see if we are displaying the default page or searching for a user

//Display a search box for all users
$query = "SELECT CONCAT(firstname,' ',lastname) AS name, username, user_id from tbl_user";
$result = mysqli_query($dbc, $query) or die('Error getting list of all staff: ' . mysqli_error($dbc));
//Check for result
if (mysqli_num_rows($result) == 0){
	echo "Apperantly there are no staff";
	require_once('../scripts/footer.php');
	die();
}

?>
<h2>Select user to modify</h2>
<form method="post" action="#">
	<div class="form-group container">

<?php
echo "<select>\n";
while ($row = mysqli_fetch_array($result)) {
		echo "<option value='".$row['user_id']."'>".$row['name']." (".$row['username'].")</option>\n";
	}
echo "</select>\n";

?>
	</div>

	<div class="form-group container">
		<button type="submit" name="modify" class="btn btn-default">Modify</button>
		<button type="submit" name="delete" class="btn btn-default">Delete</button>
	</div>

</form>

<?php
require_once('../scripts/footer.php');
?>