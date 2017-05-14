<?php
$title = "View schedule";
$login = true;
require_once('../scripts/header.php');

//TODO Check for form data:
//TODO user_id, default current
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

//Check for date
//All dates stored in strtotime format and converted to Y-m-d when displayed/used in query
if (isset($_GET['date'])) {
    //TODO Check date is valid...
    $start_date = strtotime(mysqli_real_escape_string($dbc, trim($_GET['date'])));
} else {
    $start_date = strtotime("now");
}
//Mondayise whatever date it is
$start_date = getmondayOfWeek($start_date);
//End of week, TODO change depening on span(see below)
$end_date = strtotime("+6 days", $start_date);
//Dates for prev/next week
$prevWeekStart = strtotime("-1 week", $start_date);
$nextWeekStart = strtotime("+1 week", $start_date);
//TODO span: day, week, month, 7 day. Default is week
//TODO The ordering of the events is gonna really screw up when other than 7 days...
$span = 7;

//Get details on user we are viewing
$query = "SELECT CONCAT(firstname,' ',lastname) AS name from tbl_user WHERE user_id='$user_id' LIMIT 1";
$result = mysqli_query($dbc, $query) or die('Error getting users name: ' . mysqli_error($dbc));
//Check for result
if (mysqli_num_rows($result) == 0) {
    echo "<h1>Invalid user_id</h1><br/>";
    die();
}
//Get name
$user = mysqli_fetch_array($result)['name'];
//Format displayed date TODO Use nice google calendar format?
$pretty_date = date("d-m-Y", $start_date);

echo "<h1>View schedule for $user, week starting $pretty_date</h1>\n";
?>

<div class="well well-sm">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
        <div class="form-group container">
            <input type="hidden" name="date" value="<?php echo date("d-m-Y", $start_date); ?>">
            <button type="submit" name="date" value="<?php echo date("d-m-Y"); ?>">Today</button>
            <button type="submit" name="date" value="<?php echo date("d-m-Y", $prevWeekStart); ?>">
                <span class="glyphicon glyphicon-menu-left" aria-hidden="true" aria-label="Previous week"></span>
            </button>

            <button type="submit" name="date" value="<?php echo date("d-m-Y", $nextWeekStart); ?>">
                <span class="glyphicon glyphicon-menu-right" aria-hidden="true" aria-label="Next week"></span>
            </button>



            <?php
//TODO test on monday/sunday and remove
//echo date("Y-m-d", $start_date)."<br>";
//echo date("Y-m-d", $end_date)."<br>";
//echo date("Y-m-d", $prevWeekStart)."<br>";
//echo date("Y-m-d", $nextWeekStart)."<br>";
//Display staff selector if manager
            if (isManager()) {
                //Display a search box for all users
                $query = "SELECT CONCAT(firstname,' ',lastname) AS name, username, user_id from tbl_user";
                $result = mysqli_query($dbc, $query) or die('Error getting list of all staff: ' . mysqli_error($dbc));
                ?>    

                <?php
                echo "<select name='user_id'>\n";
                while ($row = mysqli_fetch_array($result)) {
                    echo "<option value='" . $row['user_id'] . "'";
                    //Default to currently viewed user
                    if ($user_id == $row['user_id']) {
                        echo " selected='selected'";
                    }
                    echo ">" . $row['name'] . " (" . $row['username'] . ")</option>\n";
                }
                echo "</select>\n";
                ?>
                <button type="submit" name="select" class="btn btn-default">Change user</button>
            </div>
        </form>
    </div>
    <?php
}

//Build query for weeks data
$query = "SELECT * FROM tbl_schedule 
WHERE user_id='$user_id' AND start_date<='" . date("Y-m-d", $end_date) . "' AND 
(occurrences=0 OR (DATE_ADD(start_date, INTERVAL ((occurrences-1)*7) DAY)>='" . date("Y-m-d", $start_date) . "'))
ORDER BY (case DAYOFWEEK(start_date) WHEN 1 THEN 8 else DAYOFWEEK(start_date) END), start_time";

$result = mysqli_query($dbc, $query) or die('Error getting schedule data: ' . mysqli_error($dbc));

if (mysqli_num_rows($result) == 0) {
    echo "<div>User has no schedule events for this time</div><br/>";
} else {
    $last_day_of_week = -1;
    while ($row = mysqli_fetch_array($result)) {
        //TODO Calculate this events actual date
        //date("Y-m-d",strtotime("+8 day",$monday_of_week))
        //Find out if we need to print a new weekday header
        $day_of_week = date('w', strtotime($row['start_date']));
        //If it has changed from the last one printed...
        if ($day_of_week != $last_day_of_week) {
            $last_day_of_week = $day_of_week;

            echo "<h2>" . date('l', strtotime($row['start_date'])) . "</h2>\n";
        }

        echo "Time: " . $row['start_time'] . " - " . $row['end_time'] . "<br/>\n";
        echo $row['description'] . "<br/>\n";
    }
}
?>

<?php
require_once('../scripts/footer.php');
?>