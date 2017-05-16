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
DROP table IF EXISTS thisWeeksShifts;
DROP table IF EXISTS thisWeeksShifts2;

CREATE temporary TABLE IF NOT EXISTS thisWeeksShifts AS (
SELECT roster_id, user_id, username, start_date, start_time, end_time, description FROM tbl_roster LEFT JOIN tbl_user USING (user_id)
WHERE start_date>='" . date("Y-m-d", $start_date) . "' AND start_date<='" . date("Y-m-d", $end_date) . "'
);

CREATE temporary TABLE IF NOT EXISTS thisWeeksShifts2 AS (
SELECT roster_id, user_id, start_date, start_time, end_time, description FROM tbl_roster
WHERE start_date>='" . date("Y-m-d", $start_date) . "' AND start_date<='" . date("Y-m-d", $end_date) . "'
);

SELECT * FROM thisWeeksShifts
UNION
SELECT roster_id, user_id, tbl_user.username, start_date, start_time, end_time, description FROM thisWeeksShifts2 RIGHT JOIN tbl_user USING (user_id)
WHERE thisWeeksShifts2.user_id IS NULL

ORDER BY user_id, start_date, start_time";

mysqli_multi_query($dbc, $query) or die('Error getting roster data: ' . mysqli_error($dbc));

$result = mysqli_last_result($dbc);

//echo "<pre>\n";
//while ($shift = mysqli_fetch_row($result)) {
//    print_r($shift);
//}
//echo "</pre>\n";
?>
<div class="table-responsive">
    <table class="table table-bordered">
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
            $shift = mysqli_fetch_array($result);
            //Loop through all rows(users)
            while ($shift) {
                //Get current user
                $current_user = $shift['user_id'];
                $empty=false;
                echo "<tr><td>" . $shift['username'] . "</td>";

                //Loop through all columns(days)
                for ($current_date = $start_date; $current_date <= $end_date; $current_date = strtotime("+1 days", $current_date)) {
                    $output = false;
                    echo "<td>";
                    //Check for empty row(user has no shifts in the week
                    if ($shift['roster_id'] == NULL) {
                        //Get next shift
                        $empty=true;
                    }


                    while ($shift['start_date'] == date("Y-m-d", $current_date) && $current_user == $shift['user_id']) {
                        if ($output) {
                            echo "<br/>";
                        }
                        echo date("H:i", strtotime($shift['start_time']))."-".date("H:i", strtotime($shift['end_time']));
                        $output = true;
                        //Get next shift
                        $shift = mysqli_fetch_array($result);
                    }



                    if ($output == false) {
                        echo "&nbsp;";
                    }

                    echo "</td>";
                }
                if ($empty){
                    $shift = mysqli_fetch_array($result);
                }
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php
require_once('../scripts/footer.php');
?>