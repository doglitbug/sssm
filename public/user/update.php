<?php
$title = "Modify staff member";
$login = true;
require_once('../scripts/header.php');

//Set vars for sticky form
$user_id = $username = $manager = $firstname = $lastname = $email = $email1 = $cellphone = $landline = "";

//Set error vars
$username_error = $firstname_error = $lastname_error = $email_error = $email1_error = "";

if (isset($_GET['user_id'])) {
    //Get requested user_id
    $user_id = mysqli_real_escape_string($dbc, trim($_GET['user_id']));
    //Check if we are not viewing ourself, if so are we a manager
    if ($_SESSION['user_id'] != $user_id AND $_SESSION['manager'] != '1') {
        echo "<h1>Manager access required</h1>\n";
        echo "<ul>\n";
        echo "<li><a href='../index.php'>Click here to return to home page</a></li>\n";
        echo "</ul>\n";
        require_once('../scripts/footer.php');
        die();
    }
} else {
    //View schedule for ourselves
    $user_id = $_SESSION['user_id'];
}
//Go grab existing details from the database!
$query = "SELECT username, manager, firstname, lastname, email, cellphone, landline FROM tbl_user WHERE user_id='$user_id' LIMIT 1";

//Grab result
$result = mysqli_query($dbc, $query) or die('Couldn\'t search for existing user details: ') . mysqli_error($dbc);

//Check if username was found
if (mysqli_num_rows($result) != 1) {
    //TODO Um not sure how to deal with this...
    die('Could not find existing user');
}
//Get data on the selected user
$row = mysqli_fetch_array($result);

//Get existing details to populate form
$username = $row['username'];
$manager = $row['manager'];
$firstname = $row['firstname'];
$lastname = $row['lastname'];
$email = $email1 = $row['email'];
$cellphone = $row['cellphone'];
$landline = $row['landline'];


//Check to see if form has been submitted
if (isset($_POST['submit'])) {
    //Grab previous data
    $user_id = mysqli_real_escape_string($dbc, trim($_POST['user_id']));
    $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
    $manager = mysqli_real_escape_string($dbc, trim(isset($_POST['manager']) ? $_POST['manager'] : '0'));
    $firstname = mysqli_real_escape_string($dbc, trim($_POST['firstname']));
    $lastname = mysqli_real_escape_string($dbc, trim($_POST['lastname']));
    $email = mysqli_real_escape_string($dbc, trim($_POST['email']));
    $email1 = mysqli_real_escape_string($dbc, trim($_POST['email1']));
    $cellphone = mysqli_real_escape_string($dbc, trim($_POST['cellphone']));
    $landline = mysqli_real_escape_string($dbc, trim($_POST['landline']));

    //Check username is valid
    if (empty($username)) {
        $username_error = "Please enter a username";
    } else {
        //Check username hasn't already been used by a different user
        $query = "SELECT username FROM tbl_user WHERE username='$username' AND user_id!='$user_id' LIMIT 1";

        //Grab result
        $result = mysqli_query($dbc, $query) or die('Couldn\'t search for username: ') . mysqli_error($dbc);

        //Check if username was found
        if (mysqli_num_rows($result) != 0) {
            $username_error = "Username already taken, please choose another";
        }
    }

    //Check firstname has been entered
    if (empty($firstname)) {
        $firstname_error = "Please enter a first name";
    }

    ////////// Email validation and checks //////////
    //Check email address has been entered
    if (empty($email)) {
        $email_error = "Please enter an email address";
    } else {
        //Check valid(form should take care of this but yeah, checks...)
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_error = "Please enter a valid email address";
        }
    }

    //Check email1 address has been entered
    if (empty($email1)) {
        $email1_error = "Please repeat email address";
    }

    //Check email hasn't already been used by a different user
    $query = "SELECT email FROM tbl_user WHERE email='$email' AND user_id!='$user_id' LIMIT 1";

    //Grab result
    $result = mysqli_query($dbc, $query) or die('Couldn\'t search for email: ') . mysqli_error($dbc);

    //Grab rows
    $row = mysqli_fetch_array($result);

    //Check if email was found
    if (mysqli_num_rows($result) != 0) {
        $email_error = "Email already taken, please choose another";
    }

    //Check email address have been entered and that they match!
    if (!empty($email) && !empty($email1) && ($email != $email1)) {
        $email1_error = "Please make sure email addresses match";
    }

    ////////// Check to see if all data is valid and if so, make a new user //////////
    if (($username_error . $firstname_error . $email_error . $email1_error) == "") {

        //Build query to update user
        $query = "UPDATE tbl_user SET username='$username', firstname='$firstname', lastname='$lastname', manager='$manager', cellphone='$cellphone', landline='$landline', email='$email' WHERE user_id='$user_id'";

        //Update user
        mysqli_query($dbc, $query) or die('Couldn\'t update user details: ') . mysqli_error($dbc);
        //If this is the current user, update SESSION varibles
        if ($_SESSION['user_id'] == $user_id) {
            $_SESSION['username'] = $username;
            $_SESSION['manager'] = $manager;
        }
        ?>
        <h1><?php echo $title; ?></h1>
        <div class="container">
            <p>Staff members details have been updated. Click <a href="../index.php">here</a> to go back.</p>
        </div>
        <?php
        require_once('../scripts/footer.php');
        die();
    }
}
?>
<h1><?php echo $title; ?></h1>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <input type="hidden" name="user_id" value ="<?php echo $user_id; ?>">
    <h3>Personal details:</h3>
    <div class="form-group container">
        <div class="col-md-6">
            <label for="username">Username*</label>
            <input type="text" class="form-control" id="username" placeholder="Username" name="username" value="<?php echo $username; ?>"/>
            <div class="error"><?php echo $username_error; ?></div>
        </div>

        <div class="col-md-6">
            <label for="manager">Manager</label>
            <input type="checkbox" class="form-check-input" id="manager" name="manager" value="1"<?php
            if ($manager == '1') {
                echo "checked";
            }
            ?>/>
        </div>
    </div>

    <div class="form-group container">
        <div class="col-md-6">
            <label for="firstname">First name*</label>
            <input type="text" class="form-control" id="firstname" placeholder="First name" name="firstname" value="<?php echo $firstname; ?>"/>
            <div class="error"><?php echo $firstname_error; ?></div>
        </div>

        <div class="form-group col-md-6">
            <label for="lastname">Last name</label>
            <input type="text" class="form-control" id="lastname" placeholder="Last name" name="lastname" value="<?php echo $lastname; ?>"/>
            <div class="error"><?php echo $lastname_error; ?></div>
        </div>
    </div>

    <div class="form-group container">
        <div class="col-md-6">
            <label for="email">Email address*</label>
            <input type="email" class="form-control" id="email" placeholder="Email address" name="email" value="<?php echo $email; ?>"/>
            <div class="error"><?php echo $email_error; ?></div>
        </div>

        <div class="col-md-6">
            <label for="email1">Confirm email address*</label>
            <input type="email" class="form-control" id="email1" placeholder="Email address" name="email1" value="<?php echo $email1; ?>"/>
            <div class="error"><?php echo $email1_error; ?></div>
        </div>
    </div>

    <h3>Contact details:</h3>

    <div class="form-group container">
        <div class="col-md-6">
            <label for="cellphone">Cellphone</label>
            <input type="text" class="form-control" id="cellphone" placeholder="Cellphone" name="cellphone" value="<?php echo $cellphone; ?>"/>
        </div>

        <div class="col-md-6">
            <label for="landline">Landline</label>
            <input type="text" class="form-control" id="landline" placeholder="Landline" name="landline" value="<?php echo $landline; ?>"/>
        </div>
    </div>

    <div class="form-group container">
        <div class="container">
            <button type="submit" name="submit" class="btn btn-success">Modify staff members details</button>
            <button type="reset" value="Reset" class="btn btn-info">Reset</button>
            <a href="../index.php" class="btn btn-danger">Cancel</a>
        </div>
    </div>
</form>

<?php
require_once('../scripts/footer.php');
?>