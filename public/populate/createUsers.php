<?php
$title = "Create new users";
require_once('../scripts/header.php');

//Turn off key checks
mysqli_query($GLOBALS['dbc'], 'SET foreign_key_checks = 0');

//Yeah lets just add data from today
$today = date("Y-m-d");
$monday_of_week = getMondayOfWeek($today);

//Create new users
if (isset($_POST['users'])) {
    //Remove previous users, note this will remove anything else in linked tables
    $query = "DELETE FROM tbl_user";
    mysqli_query($GLOBALS['dbc'], $query) or die('Couldn\'t remove all users: ' . mysqli_error($GLOBALS['dbc']));

////////// Create 11 users, one manager rest not //////////
    createUser("1", "admin", "password", "Admin", "Account", "1", "021000000", "", "admin@example.com");
    createUser("2", "lilly", "password", "Lilly", "", "0", "021000001", "", "lilly@example.com");
    createUser("3", "belen", "password", "Belen", "", "0", "021000002", "", "belen@example.com");
    createUser("4", "kyra", "password", "Kyra", "", "0", "021000003", "", "kyra@example.com");
    createUser("5", "gloria", "password", "Gloria", "", "0", "021000004", "", "gloria@example.com");
    createUser("6", "jazmine", "password", "Jazmine", "", "0", "021000005", "", "jazmine@example.com");
    createUser("7", "lexi", "password", "Lexi", "", "0", "021000006", "", "lexi@example.com");
    createUser("9", "natasha", "password", "Natasha", "", "0", "021000008", "", "natasha@example.com");
    createUser("10", "heather", "password", "Heather", "", "0", "021000009", "", "heather@example.com");
    createUser("11", "crystal", "password", "Crystal", "", "0", "021000010", "", "crystal@example.com");
}

//Add some schedule data
if (isset($_POST['schedule'])) {
    //Remove previous schedule data(may already be removed by create users)
    $query = "DELETE FROM tbl_schedule";
    mysqli_query($GLOBALS['dbc'], $query) or die('Couldn\'t remove all schedule data: ' . mysqli_error($GLOBALS['dbc']));

////////// Schedule data for first user
//Friday afternoon to saturday night all year
    createSchedule(2, "2017-01-06", "16:00", "24:00", 0, "Looking after kids");
    createSchedule(2, "2017-01-07", "00:00", "17:40", 0, "Looking after kids");

//Add a couple of classes on monday this week, placed out of order on purpose
    createSchedule(2, date("Y-m-d", $monday_of_week), "12:00", "14:00", 1, "Computer class");
    createSchedule(2, date("Y-m-d", $monday_of_week), "08:00", "10:00", 1, "Computer class");

//Add a sunday to check ordering...sigh...
    createSchedule(2, date("Y-m-d", strtotime("+6 day", $monday_of_week)), "5:00", "12:00", 2, "Going fishing");

//Add a exam next week on tuesday
    createSchedule(2, date("Y-m-d", strtotime("+8 day", $monday_of_week)), "10:00", "12:00", 1, "Computer exam");

//Add a one off item last week Thursday
    createSchedule(2, date("Y-m-d", strtotime("-4 day", $monday_of_week)), "17:30", "19:30", 1, "Dinner out with mates");

//Add a 3 week event starting last week Friday
    createSchedule(2, date("Y-m-d", strtotime("-3 day", $monday_of_week)), "8:00", "11:00", 3, "Attend gym");

//Add a 2 week event starting next week
    createSchedule(2, date("Y-m-d", strtotime("+9 day", $monday_of_week)), "9:00", "12:00", 2, "Attend gym(again)");

////////// Schedule data for user 2 //////////
//User works mon-fri 8-5
    createSchedule(3, "2017-01-02", "08:00", "17:00", 0, "Work");
    createSchedule(3, "2017-01-03", "08:00", "17:00", 0, "Work");
    createSchedule(3, "2017-01-04", "08:00", "17:00", 0, "Work");
    createSchedule(3, "2017-01-05", "08:00", "17:00", 0, "Work");
    createSchedule(3, "2017-01-06", "08:00", "17:00", 0, "Work");
}

//Add some roster data
if (isset($_POST['roster'])) {
    //Remove previous roster data(may already be removed by create users)
    $query = "DELETE FROM tbl_roster";
    mysqli_query($GLOBALS['dbc'], $query) or die('Couldn\'t remove all roster data: ' . mysqli_error($GLOBALS['dbc']));

    //Open shifts
    createRoster(NULL, date("Y-m-d", strtotime("+2 day", $monday_of_week)), "8:00", "10:00", "Help open store");
    createRoster(NULL, date("Y-m-d", strtotime("+2 day", $monday_of_week)), "18:00", "20:00", "Help Close store");
    createRoster(NULL, date("Y-m-d", strtotime("+3 day", $monday_of_week)), "8:00", "10:00", "Help open store");
    createRoster(NULL, date("Y-m-d", strtotime("+3 day", $monday_of_week)), "18:00", "20:00", "Help Close store");

    //User 2
    createRoster(2, date("Y-m-d", $monday_of_week), "8:00", "16:00", "Open store");
    createRoster(2, date("Y-m-d", strtotime("+1 day", $monday_of_week)), "8:00", "16:00", "Open store");
    createRoster(2, date("Y-m-d", strtotime("+2 day", $monday_of_week)), "8:00", "16:00", "Open store");
    createRoster(2, date("Y-m-d", strtotime("+3 day", $monday_of_week)), "8:00", "16:00", "Open store");
    createRoster(2, date("Y-m-d", strtotime("+4 day", $monday_of_week)), "8:00", "16:00", "Open store");

    //User 3
    createRoster(3, date("Y-m-d", $monday_of_week), "12:00", "20:00", "Lunch to close");
    createRoster(3, date("Y-m-d", strtotime("+1 day", $monday_of_week)), "12:00", "20:00", "Lunch to close");
    createRoster(3, date("Y-m-d", strtotime("+2 day", $monday_of_week)), "12:00", "20:00", "Lunch to close");
    createRoster(3, date("Y-m-d", strtotime("+5 day", $monday_of_week)), "12:00", "20:00", "Lunch to close");
    createRoster(3, date("Y-m-d", strtotime("+6 day", $monday_of_week)), "12:00", "20:00", "Lunch to close");

    //User 6
    createRoster(6, date("Y-m-d", strtotime("+3 day", $monday_of_week)), "12:00", "20:00", "Lunch to close");
    createRoster(6, date("Y-m-d", strtotime("+4 day", $monday_of_week)), "12:00", "20:00", "Lunch to close");
    createRoster(6, date("Y-m-d", strtotime("+5 day", $monday_of_week)), "8:00", "16:00", "Open store");
    createRoster(6, date("Y-m-d", strtotime("+6 day", $monday_of_week)), "8:00", "16:00", "Open store");

    //User 7(Has double shifts)
    createRoster(7, date("Y-m-d", $monday_of_week), "8:00", "10:00", "Help open store");
    createRoster(7, date("Y-m-d", $monday_of_week), "18:00", "20:00", "Help close store");
    createRoster(7, date("Y-m-d", strtotime("+1 day", $monday_of_week)), "8:00", "10:00", "Help open store");
    createRoster(7, date("Y-m-d", strtotime("+1 day", $monday_of_week)), "18:00", "20:00", "Help close store");
    createRoster(7, date("Y-m-d", strtotime("+4 day", $monday_of_week)), "8:00", "10:00", "Help open store");
    createRoster(7, date("Y-m-d", strtotime("+4 day", $monday_of_week)), "18:00", "20:00", "Help close store");
    createRoster(7, date("Y-m-d", strtotime("+5 day", $monday_of_week)), "8:00", "10:00", "Help open store");
    createRoster(7, date("Y-m-d", strtotime("+5 day", $monday_of_week)), "18:00", "20:00", "Help close store");
    createRoster(7, date("Y-m-d", strtotime("+6 day", $monday_of_week)), "8:00", "10:00", "Help open store");
    createRoster(7, date("Y-m-d", strtotime("+6 day", $monday_of_week)), "18:00", "20:00", "Help close store");
}

//Turn back on the key checks
mysqli_query($GLOBALS['dbc'], 'SET foreign_key_checks = 1');

function createUser($user_id, $username, $password, $firstname, $lastname, $manager, $cellphone, $landline, $email) {
    //Encrpyt password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    //Build INSERT query
    $query = "INSERT INTO tbl_user (user_id, username, password, firstname, lastname, manager, cellphone, landline, email) VALUES"
            . "                    ('$user_id', '$username', '$hashed_password', '$firstname', '$lastname', '$manager', '$cellphone', '$landline', '$email')";

    //Execute query
    mysqli_query($GLOBALS['dbc'], $query) or die('Couldn\'t add user: ' . mysqli_error($GLOBALS['dbc']));

    echo "Created user: " . $username . "<br/>\n";
}

function createRoster($user_id, $start_date, $start_time, $end_time, $description) {
    //Reformat times
    $start_time .= ":00";
    $end_time .= ":00";
    //Build insert query
    $query = "INSERT INTO tbl_roster (user_id, start_date, start_time, end_time, description) VALUES "
            . "('$user_id', '$start_date', '$start_time', '$end_time', '$description')";
    //Execute query
    mysqli_query($GLOBALS['dbc'], $query) or die('Couldn\'t add roster data: ' . mysqli_error($GLOBALS['dbc']));

    echo "Created roster data.<br/>\n";
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
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
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