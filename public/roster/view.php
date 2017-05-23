<?php
$title = "View roster";
$login = true;
require_once('../scripts/header.php');

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

//Format displayed date TODO Use nice google calendar format?
$pretty_date = date("d-m-Y", $start_date);

echo "<h1>View roster for week starting $pretty_date</h1>\n";
?>
<div class="well well-sm">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
        <div class="form-group container">
            <button type="submit" name="date" value="<?php echo date("d-m-Y"); ?>">Today</button>
            <button type="submit" name="date" value="<?php echo date("d-m-Y", $prevWeekStart); ?>">
                <span class="glyphicon glyphicon-menu-left" aria-hidden="true" aria-label="Previous week"></span>
            </button>

            <button type="submit" name="date" value="<?php echo date("d-m-Y", $nextWeekStart); ?>">
                <span class="glyphicon glyphicon-menu-right" aria-hidden="true" aria-label="Next week"></span>
            </button>
        </div>
    </form>
</div>
<?php
//Build query for weeks roster
$query = "
SELECT roster_id, user_id, start_date, start_time, end_time, description FROM tbl_roster
WHERE start_date>='" . date("Y-m-d", $start_date) . "' AND start_date<='" . date("Y-m-d", $end_date) . "'
ORDER BY user_id, start_date, start_time";

$shifts = mysqli_query($dbc, $query) or die('Error getting roster data: ' . mysqli_error($dbc));

//Build query for user data
$query = "SELECT user_id, username FROM tbl_user ORDER BY user_id";
//Note, the order must match the roster query!
$userResults = mysqli_query($dbc, $query) or die('Error getting user data: ' . mysqli_error($dbc));
//Build false user for Open shifts, format must match the result above!
$users[0] = array("user_id" => "0", "username" => "Open shifts");
//Add both to list of users
while ($user = mysqli_fetch_assoc($userResults)) {
    array_push($users, $user);
}
?>
<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>&nbsp;</th>
                <?php
                for ($current_date = $start_date; $current_date <= $end_date; $current_date = strtotime("+1 days", $current_date)) {
                    echo "<th>" . date("d-m-Y", $current_date) . "</th>\n";
                }
                ?>

            </tr>
        </thead>
        <tbody>
            <?php
            //Get first shift
            $shift = mysqli_fetch_array($shifts);
            //Loop through all rows(users)
            while ($user = array_shift($users)) {

                echo "<tr><td><b>" . $user['username'] . "</b></td>";

                //Loop through all columns(days)
                for ($current_date = $start_date; $current_date <= $end_date; $current_date = strtotime("+1 days", $current_date)) {
                    //Create an id for this user/date combination
                    $id = $user['user_id'] . "-" . date("Y-m-d", $current_date);
                    echo "<td id='$id'>";
                    $output = false;
                    while ($shift['start_date'] == date("Y-m-d", $current_date) && $shift['user_id'] == $user['user_id']) {
                        //If we have already put a shift in this place, place in another
                        if ($output) {
                            echo "<br/>";
                        }

                        //Build pretty card for shift
                        echo "<div class='shift ";
                        if ($id==0){
                            echo "alert-danger";
                        } else {
                            echo "alert-success";
                        }
                        echo "'>";
                        echo "<div class='title'>" . date("H:i", strtotime($shift['start_time'])) . "-" . date("H:i", strtotime($shift['end_time']));

                        echo "</div>";
                        echo "<div class='body'>" . $shift['description'] . "</div>";
                        echo "</div>";

                        $output = true;
                        //Get next shift
                        $shift = mysqli_fetch_array($shifts);
                    }

                    if ($output == false) {
                        echo "&nbsp;";
                    }

                    echo "</td>";
                }
                echo "</tr>\n";
            }
            ?>
        </tbody>
    </table>
</div>

<?php
require_once('../scripts/footer.php');
?>