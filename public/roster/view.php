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
$query = "SELECT user_id, username, start_date, start_time, end_time, description FROM tbl_roster LEFT JOIN tbl_user USING (user_id) 
WHERE start_date>='" . date("Y-m-d", $start_date) . "' AND start_date<='" . date("Y-m-d", $end_date) . "'
UNION
select user_id, username, NULL, NULL, NULL, NULL FROM tbl_user
ORDER BY user_id, start_date, start_time";

$result = mysqli_query($dbc, $query) or die('Error getting roster data: ' . mysqli_error($dbc));
echo "<pre>\n";
while ($row = mysqli_fetch_array($result)) {
    print_r($row);
}
echo "</pre>\n";

$currentUser_id = 0;
$currentDate=$start_date;
//TODO Table header

while ($row = mysqli_fetch_array($result)){
if ($row['user_id']!=$currentUser_id){
    //TODO Finish row
    //TODO Start new row
    $currentUser_id=$row['user_id'];
}
    
}
require_once('../scripts/footer.php');
?>