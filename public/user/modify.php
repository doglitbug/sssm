<?php
$title = "Modify staff member";
$login = true;
require_once('../scripts/header.php');

//TODO Check to see if we are displaying the default page or searching for a user
//Display a search box for all users
$query = "SELECT CONCAT(firstname,' ',lastname) AS name, username, user_id from tbl_user";
$result = mysqli_query($dbc, $query) or die('Error getting list of all staff: ' . mysqli_error($dbc));
//Check for result
if (mysqli_num_rows($result) == 0) {
    echo "Apperantly there are no staff";
    require_once('../scripts/footer.php');
    die();
}

//Output all users in cards
while ($row = mysqli_fetch_array($result)) {
    echo "<div class='col-sm-6 col-md-4'>\n";
    echo "<div class='panel panel-default'>\n";
    echo "<div class='panel-heading'>" . $row['name'] . "</div>\n";
    echo "<div class='panel-body'>\n";

    echo "Details about user<br/>\n";

    if (isManager()) {
        echo "<a class='btn btn-primary' href='#'>Delete</a>\n";
        echo "<a class='btn btn-primary' href='#'>Modify</a>\n";
    }
    
    
    echo "</div>\n";
    echo "</div>\n";
    echo "</div>\n";
}

require_once('../scripts/footer.php');
?>