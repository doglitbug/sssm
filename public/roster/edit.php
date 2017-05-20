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
                    echo "<td id='$id' ondrop=\"drop(event, '$id')\" ondragover='allowDrop(event)'>";
                    $output = false;
                    //Date format as advised by RFC 3339/ISO 8601 "wire format": YYYY-MM-DD
                    while ($shift['start_date'] == date("Y-m-d", $current_date) && $shift['user_id'] == $user['user_id']) {
                        //If we have already put a shift in this place, place in on another line
                        if ($output) {
                            //echo "<br/>";
                        }

                        //Build pretty card for shift
                        //echo "<a href='edit".$shift['roster_id']."'>";
                        //Enable draggable
                        echo "<div draggable='true' ondragstart='drag(event)' class='shift ";
                        //Randomly color a shift, yes lazy I know...
                        switch (rand(0, 3)) {
                            case 0:
                                echo "alert-success";
                                break;
                            case 1:
                                echo "alert-info";
                                break;
                            case 2:
                                echo "alert-warning";
                                break;
                            case 3:
                                echo "alert-danger";
                                break;
                        }
                        echo "' id='" . $shift['roster_id'] . "'>";

                        echo "<div class='start_time'>" . date("H:i", strtotime($shift['start_time'])) . "</div> - ";
                        echo "<div class='end_time'>" . date("H:i", strtotime($shift['end_time'])) . "</div>";
                        echo "<div class='description'>" . $shift['description'] . "</div>";
                        //Add other data such as location or total hours?
                        echo "</div>";

                        $output = true;
                        //Get next shift
                        $shift = mysqli_fetch_array($shifts);
                    }

                    if ($output == false) {

                        echo "<a href='#'><span class='glyphicon glyphicon-plus' aria-hidden='true' aria-label='Add shift'></span></a>";
                    }

                    echo "</td>";
                }
                echo "</tr>\n";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
    function allowDrop(ev) {
        ev.preventDefault();
    }

    function drag(ev) {
        ev.dataTransfer.setData("text", ev.target.id);
    }

    function drop(ev, ui) {
        ev.preventDefault();

        //Check it is not dropped on a shift
        if (ev.target.getAttribute('id') !== null) {
            //Get all the required data
            var source_shift = ev.dataTransfer.getData("text");
            var roster_id = source_shift;
            //Get point to split user_id from data
            var split = ui.indexOf('-');
            var user_id = ui.substring(0, split);
            var start_date = ui.substring(split + 1);
            var start_time = document.getElementById(source_shift).getElementsByClassName("start_time")[0].innerHTML;
            var end_time = document.getElementById(source_shift).getElementsByClassName("end_time")[0].innerHTML;
            var description = document.getElementById(source_shift).getElementsByClassName("description")[0].innerHTML;

            //Lets use some jQuery here to move shift in database
            //TODO use jQuery for everything...
            $.getJSON({
                type: 'post',
                url: 'update.php',
                data: $.param({'roster_id': roster_id, 'user_id': user_id, 'start_date': start_date, 'start_time': start_time, 'end_time': end_time, 'description': description}),
                success: function (data, status, jqXHR) {
                    if (data.success) {
                        console.log(data.message);
                        ev.target.appendChild(document.getElementById(source_shift));
                    } else {
                        //TODO Deal with error
                    }

                },
                error: function (data, status, headers, config) {
                    //TODO Deal with serious error
                }});
        }
    }
</script>
<?php
require_once('../scripts/footer.php');
?>