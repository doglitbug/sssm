<?php
$title = "Remove staff member";
$manager = true;
require_once('../scripts/header.php');

//TODO Check deleted user is not logged in user!
//This ensures we always have at least one manager in place(hopefully)


$confirmerror = $user_id = "";

if (isset($_GET['user_id'])) {
    //Check see if we have been passed a user_id to remove
    $user_id = mysqli_real_escape_string($dbc, trim($_GET['user_id']));
}

if (isset($_POST['submit'])) {
    $user_id = mysqli_real_escape_string($dbc, trim($_POST['user_id']));
    //Check for the checkbox
    if (isset($_POST['confirm']) && $_POST['confirm'] == "confirm") {
        
        //TODO Check it is in the table(should validate as well...or will deleting a user_id that isn't in the table not really matter?
        //Check its not us!
        $query = "DELETE FROM tbl_user WHERE user_id='$user_id'";

        $result = mysqli_query($dbc, $query) or die('Error deleting user: ' . mysqli_error($dbc));
        echo "User successfully deleted";
        require_once("../scripts/footer.php");
        die();
    } else {
        $confirmerror = "Please confirm deletion";
    }
}


//Check when know who to delete and display form
if ($user_id!=""){
?>
<div class="container-fluid">
    <p>Please note that this action is irreversible and will result in all the users details, schedule and availability being completed removed</p>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <input type="hidden" name ="user_id" value="<?php echo $user_id; ?>">
        <div class="form-group container">
            <label for="confirm">Confirm removing this staff member</label>
            <input type="checkbox" class="form-check-input" id="confirm" name="confirm" value="confirm"/>
            <div class="error"><?php echo $confirmerror; ?></div>
        </div>
        <div class="form-group container">
            <button type="submit" name="submit" class="btn btn-danger">Remove staff member</button>
            <a href="../index.php" class="btn btn-default">Cancel</a>
        </div>
    </form>
</div>
<?php
} else {
    echo "Please remove users from the view all section <a href='view.php'>here</a>";
}

require_once("../scripts/footer.php");
?>