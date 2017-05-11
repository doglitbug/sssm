<?php
$title = "Create new users";
require_once('../scripts/header.php');

//Turn off key checks
mysqli_query($GLOBALS['dbc'], 'SET foreign_key_checks = 0');

//TODO Delete previous users
////////// Create two users, one manager one not //////////
//createUser("1", "doglitbug", "password", "Arron", "Dick", "1", "s_drac2@yahoo.com");
//createUserContact("1", "0273655228", "", "doglitbug", "cellphone");
//
//createUser("2", "arthur", "password", "Arthur", "Gumball", "0", "agumball@email.com");
//createUserContact("2", "02112345678", "034132152", "a.gumball", "cellphone");
////////// Create some schedule data for users //////////
$today = date("Y-m-d");
$monday_of_week = getMondayOfWeek($today);
echo "Monday of this week is: " . date("Y-m-d", $monday_of_week);
echo "<br/>";

////////// Schedule data for first user
//Friday afternoon to saturday night all year
createSchedule(1, "2017-01-06", "16:00", "24:00", 0, "Looking after Samuel");
createSchedule(1, "2017-01-07", "00:00", "17:40", 0, "Looking after Samuel");

//Add a couple of classes on monday this week, placed out of order on purpose
createSchedule(1, date("Y-m-d", $monday_of_week), "12:00", "14:00", 1, "Computer class");
createSchedule(1, date("Y-m-d", $monday_of_week), "08:00", "10:00", 1, "Computer class");

//Add a sunday to check ordering...sigh...
createSchedule(1, date("Y-m-d", strtotime("+6 day", $monday_of_week)), "5:00", "12:00", 2, "Going fishing");

//Add a exam next week on tuesday
createSchedule(1, date("Y-m-d", strtotime("+8 day", $monday_of_week)), "10:00", "12:00", 1, "Computer exam");

//Add a one off item last week Thursday
createSchedule(1, date("Y-m-d", strtotime("-4 day", $monday_of_week)), "17:30", "19:30", 1, "Dinner out with mates");

//Add a 3 week event starting last week Friday
createSchedule(1, date("Y-m-d", strtotime("-3 day", $monday_of_week)), "8:00", "11:00", 3, "Attend gym");

//Add a 2 week event starting next week
createSchedule(1, date("Y-m-d", strtotime("+9 day", $monday_of_week)), "9:00", "12:00", 2, "Attend gym(again)");

////////// Schedule data for user 2 //////////
//User works mon-fri 8-5
createSchedule(2, "2017-01-02", "08:00", "17:00", 0, "Work");
createSchedule(2, "2017-01-03", "08:00", "17:00", 0, "Work");
createSchedule(2, "2017-01-04", "08:00", "17:00", 0, "Work");
createSchedule(2, "2017-01-05", "08:00", "17:00", 0, "Work");
createSchedule(2, "2017-01-06", "08:00", "17:00", 0, "Work");




//Turn back on the key checks
mysqli_query($GLOBALS['dbc'], 'SET foreign_key_checks = 1');

function createUser($user_id, $username, $password, $firstname, $lastname, $manager, $email) {
    //Encrpyt password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    //Build INSERT query
    $query = "INSERT INTO tbl_user (user_id, username, password, firstname, lastname, manager) VALUES ('$user_id', '$username', '$hashed_password', '$firstname', '$lastname', '$manager')";

    //Execute query
    mysqli_query($GLOBALS['dbc'], $query) or die('Couldn\'t add user: ' . mysqli_error($GLOBALS['dbc']));

    //Build query to create user contact
    $query = "INSERT INTO tbl_contact (user_id, email) VALUES ('$user_id', '$email')";
    //Insert contact details
    mysqli_query($GLOBALS['dbc'], $query) or die('Couldn\'t add new user contact details: ' . mysqli_error($GLOBALS['dbc']));

    echo "Created user: " . $username . "<br/>\n";
}

function createUserContact($user_id, $cellphone, $landline, $facebook, $preferred) {
    //Build UPDATE query
    $query = "UPDATE tbl_contact SET cellphone='$cellphone', landline='$landline', facebook='$facebook', preferred='$preferred' WHERE user_id='$user_id'";

    //Execute query
    mysqli_query($GLOBALS['dbc'], $query) or die('Couldn\'t add user contact details: ' . mysqli_error($GLOBALS['dbc']));

    echo "Created contact details<br/>\n";
}

function createSchedule($user_id, $start_date, $start_time, $end_time, $occurrences, $description) {
    //Reformat times
    $start_time .= ":00";
    $end_time .= ":00";
    //Dates can be provided as yyyy-mm-dd
    //Build query	
    $query = "INSERT INTO tbl_schedule (user_id, start_date, start_time, end_time, occurrences, description) VALUES ('$user_id', '$start_date', '$start_time', '$end_time', '$occurrences', '$description')";

    //Insert new schedule
    mysqli_query($GLOBALS['dbc'], $query) or die('Couldn\'t add new schedule: ' . mysqli_error($GLOBALS['dbc']));

    echo "Created schedule:<br/>\n";
}
?>
<form method="get" action ="#">
    <div class="form-group container">

            <label for="users">Create users</label>
            <input type="checkbox" class="form-check-input" id="users" name="users"/><br/>

            <label for="schedule">Create new schedule</label>
            <input type="checkbox" class="form-check-input" id="schedule" name="schedule"/><br/>

            <label for="roster">Create new roster</label>
            <input type="checkbox" class="form-check-input" id="roster" name="roster"/><br/>
    </div>
    
    <div class="form-group container">
        <div class="container">
            <button type="submit" name="submit" class="btn btn-default">Create selected sample data</button>
        </div>
    </div>

</form>


<?php
require_once('../scripts/footer.php');
?>