<?php
$title = "New staff member registration";
$manager = true;
require_once('../scripts/header.php');

//Set vars for sticky form
$username = $manager = $firstname = $lastname = $email = $email1 = $cellphone = $landline = "";

//Set error vars
$username_error = $firstname_error = $lastname_error = $email_error = $email1_error = $password_error = $password1_error = "";

//Check to see if form has been submitted
if (isset($_POST['submit'])) {
    //Grab previous data
    $username = mysqli_real_escape_string($dbc, trim($_POST['username']));
    $manager = mysqli_real_escape_string($dbc, trim(isset($_POST['manager']) ? $_POST['manager'] : '0'));
    $firstname = mysqli_real_escape_string($dbc, trim($_POST['firstname']));
    $lastname = mysqli_real_escape_string($dbc, trim($_POST['lastname']));
    $email = mysqli_real_escape_string($dbc, trim($_POST['email']));
    $email1 = mysqli_real_escape_string($dbc, trim($_POST['email1']));
    $password = mysqli_real_escape_string($dbc, trim($_POST['password']));
    $password1 = mysqli_real_escape_string($dbc, trim($_POST['password1']));
    $cellphone = mysqli_real_escape_string($dbc, trim($_POST['cellphone']));
    $landline = mysqli_real_escape_string($dbc, trim($_POST['landline']));

    //Check username is valid
    if (empty($username)) {
        $username_error = "Please enter a username";
    } else {
        //Check if username is taken
        $query = "SELECT username FROM tbl_user WHERE username='$username' LIMIT 1";

        //Grab result
        $result = mysqli_query($dbc, $query) or die('Couldn\'t search for username: ') . mysqli_error($dbc);
        ;

        //Grab rows
        $row = mysqli_fetch_array($result);

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

    //Check email hasn't already been used
    $query = "SELECT email FROM tbl_user WHERE email='$email' LIMIT 1";

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

    ////////// Password checks //////////
    //Check password has been entered
    if (empty($password)) {
        $password_error = "Please enter a password";
    } else {
        //Check length
        if (strlen($password) < 8) {
            $password_error = "Please choose a longer password";
        } else {
            //Check password1 has been entered
            if (empty($password1)) {
                $password1_error = "Please repeat password";
            } else {
                //Check passwords match
                if ($password != $password1) {
                    $password1_error = "Passwords do not match";
                }
            }
        }
    }
    ////////// Check to see if all data is valid and if so, make a new user //////////
    if (($username_error . $firstname_error . $email_error . $email1_error . $password_error . $password1_error) == "") {

        //Encrpyt password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        //Build query to create user
        $query = "INSERT INTO tbl_user (username, password, firstname, lastname, manager, cellphone, landline, email) VALUES"
            . "                    ('$username', '$hashed_password', '$firstname', '$lastname', '$manager', '$cellphone', '$landline', '$email')";

        //Insert user
        mysqli_query($dbc, $query) or die('Couldn\'t add new user: ') . mysqli_error($dbc);

        ?>
        <h1><?php echo $title; ?></h1>
        <div class="container">
            <p>New user has been added to the system. Click <a href="../index.php">here</a> to go back.</p>
        </div>
        <?php
        require_once('../scripts/footer.php');
        die();
    }
}
?>
<h1><?php echo $title; ?></h1>
<form method="post" action="#">
    <h3>Personal details:</h3>
    <div class="form-group container">
        <div class="col-md-6">
            <label for="username">Username*</label>
            <input type="text" class="form-control" id="username" placeholder="Username" name="username" value="<?php echo $username; ?>"/>
            <div class="error"><?php echo $username_error; ?></div>
        </div>

        <div class="col-md-6">
            <label for="manager">Manager</label>
            <input type="checkbox" class="form-check-input" id="manager" name="manager" value="1"<?php if ($manager == '1') { echo "checked";} ?>/>
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

    <div class="form-group container">
        <div class="col-md-6">
            <label for="password">Password*</label>
            <input type="password" class="form-control" id="password" placeholder="Password" name="password"/>
            <div class="error"><?php echo $password_error; ?></div>
        </div>

        <div class="col-md-6">
            <label for="password1">Confirm Password*</label>
            <input type="password" class="form-control" id="password1" placeholder="Reenter password" name="password1"/>
            <div class="error"><?php echo $password1_error; ?></div>
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
            <button type="submit" name="submit" class="btn btn-success">Register</button>
            <button type="reset" value="Reset" class="btn btn-warning">Reset</button>
        </div>
    </div>
</form>

<?php
require_once('../scripts/footer.php');
?>