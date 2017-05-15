<?php
$title = "View all staff members";
$login = true;
require_once('../scripts/header.php');

//TODO Check to see if we are displaying the default page or searching for a user
//Display a search box for all users
$query = "SELECT user_id, username, CONCAT(firstname,' ',lastname) AS name, manager, cellphone, landline, email from tbl_user";
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
    echo "<div class='panel-heading'>" . $row['username'] . "</div>\n";
    echo "<div class='panel-body'>\n";

    echo "<ul>\n";
    echo "<li class='list-group-item'>".$row['name']."</li>\n";
    //Check if this user is a manager
    if ($row['manager']==1){
        echo "<li class='list-group-item'>Manager</li>\n";
    } else {
        echo "<li class='list-group-item'>Staff</li>\n";
    }
    
    echo "<li class='list-group-item'>Cell: ".$row['cellphone']."</li>\n";
    echo "<li class='list-group-item'>Landline: ".$row['landline']."</li>\n";
    echo "<li class='list-group-item'>Email: ".$row['email']."</li>\n";

    //Actions
    echo "<li class='list-group-item'>\n";
    
    if (isManager()) {
        echo "<a class='btn btn-primary' href='update.php?user_id=".$row['user_id']."'>Update</a>\n";
        echo "<a class='btn btn-info' href='../schedule/view.php?user_id=".$row['user_id']."'>Schedule</a>\n";
        //Cannot delete yourself!
        if ($row['user_id']!=$_SESSION['user_id']){
            echo "<a class='btn btn-danger' href='remove.php?user_id=".$row['user_id']."'>Remove</a>\n";
        }
    }
    
    //insert links that non managers can view?
    
    echo "</li></ul>\n";
    echo "</div>\n";
    echo "</div>\n";
    echo "</div>\n";
}

require_once('../scripts/footer.php');
?>