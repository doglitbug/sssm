<?php

$title = "Remove staff member";
$manager = true;
require_once('../scripts/header.php');

//Check see if we have been passed a user_id to remove
if (isset($_GET['user_id'])){
    $user_idToRemove =mysqli_real_escape_string($dbc, trim($_GET['user_id']));
    echo "Do you wish to remove this user?<br/>";
    echo "Post form with remove button";
} else if (isset($_POST['submit'])) {
    echo "Delete actual user";
} else {
    echo "No user provided, display selector with get form";
}

require_once("../scripts/footer.php");
?>