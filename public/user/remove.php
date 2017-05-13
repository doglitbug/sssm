<?php
$title = "Remove staff member";
$manager = true;
require_once('../scripts/header.php');

//TODO Check deleted user is not logged in user!
//This ensures we always have at least one manager in place(hopefully)

if (isset($_POST['submit'])) {
    //TODO Check the check box
    $user_id = mysqli_real_escape_string($dbc, trim($_POST['user_id']));
    //TODO Check it is in the table(should validate as well...or will deleting a user_id that isn't in the table not really matter?
    $query = "DELETE FROM tbl_user WHERE user_id='$user_id'";

    //TODO Delete linked tables or will cascade take care of this?
    $result = mysqli_query($dbc, $query) or die('Error deleting user: ' . mysqli_error($dbc));
    echo "User successfully deleted";

    //Check see if we have been passed a user_id to remove
} else if (isset($_GET['user_id'])) {
    $user_id = mysqli_real_escape_string($dbc, trim($_GET['user_id']));
    ?>
    <form method="post" action="#">
        <input type="hidden" name ="user_id" value="<?php echo $user_id; ?>">
        <div class="form-group container">
            <label for="confirm">Confirm removing this staff member</label>
            <input type="checkbox" class="form-check-input" id="confirm" name="confirm"/>
        </div>
        <div class="form-group container">
            <button type="submit" name="submit" class="btn btn-danger">Remove staff member</button>
            <a href="../index.php" class="btn btn-default">Cancel</a>
        </div>
    </form>
    <?php
} else {
    echo "No user provided, display selector with get form";
}

require_once("../scripts/footer.php");
?>