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
echo "<h2>Select user to modify</h2>\n";
echo "<select>\n";
while ($row = mysqli_fetch_array($result)) {
		echo "<option value='".$row['user_id']."'>".$row['name']." (".$row['username'].")</option>\n";
	}
echo "</select>\n";
require_once('../scripts/footer.php');
?>