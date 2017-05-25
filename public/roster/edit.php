<?php
$title = "Edit roster";
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

echo "<h1>Edit roster for week starting $pretty_date</h1>\n";
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
    <table class="table table-bordered table-striped table-hover" id="shifttable">
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
                    echo "<td id='$id' class='shifts'>";
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
                        echo "<div class='shift ";
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

                        echo "<div class='time'>" . date("H:i", strtotime($shift['start_time'])) . " - " . date("H:i", strtotime($shift['end_time'])) . "</div>";
                        echo "<div class='description'>" . $shift['description'] . "</div>";
                        //Add other data such as location or total hours?
                        echo "</div>";

                        $output = true;
                        //Get next shift
                        $shift = mysqli_fetch_array($shifts);
                    }

                    echo "</td>";
                }
                echo "</tr>\n";
            }
            ?>
        </tbody>
    </table>
</div>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    ////// Functions for adding new shifts
    //Attach click for adding a new shift
    $(document).on("click", "#shifttable td.shifts", function (e) {
        var data = $(this).attr('id');
        //Split up user_id and date from destination
        var split = data.indexOf('-');
        var user_id = data.substring(0, split);
        var start_date = data.substring(split + 1);
        alert("Add new shift for: " + user_id + " on " + start_date);
    });

    //Attach click for editing a shift
    $(document).on("click", ".shift", function (event) {
        //Stop the add new shift part
        event.stopPropagation();

        var roster_id = $(this).attr('id');
        alert("Edit shift :" + roster_id);
    });

    //Enable dragging for all shifts
    $(function () {
        $(".shift").draggable({
            containment: '#shifttable',
            cursor: 'move',
            zIndex: 100,
            revert: "invalid"
        });
    });

    //Enable dropping for all shift locations
    $(function () {
        $(".shifts").droppable({
            drop: doDrop
        });
    });


    //Action functions
    function doDrop(event, ui) {
        //Get id of moved shift(same as in database)
        var roster_id = ui.draggable.attr('id');

        //Get all the required data
        var target_location = event.target.id;

        //Split up user_id and date of target
        var split = target_location.indexOf('-');
        var user_id = target_location.substring(0, split);
        var start_date = target_location.substring(split + 1);

        //Split up time and get start_time and end_time
        var time = ui.draggable.children(".time").text();
        var split = time.indexOf("-");
        var start_time = time.substring(0, split - 1);//Adjust because divider is " - "
        var end_time = time.substring(split + 2);//Adjust because divider is " - ";

        var description = ui.draggable.children(".description").text();
        //Lets use some jQuery here to move shift in database
        //TODO use jQuery for everything...
        $.getJSON({
            type: 'post',
            url: 'update.php',
            data: $.param({'roster_id': roster_id, 'user_id': user_id, 'start_date': start_date, 'start_time': start_time, 'end_time': end_time, 'description': description}),
            success: function (data, status, jqXHR) {
                if (data.success) {
                    console.log(data.message);
                    //Move shift into dropped position
                    ui.draggable.appendTo(event.target).css({top: '0px', left: '0px'});
                } else {
                    //TODO Deal with error
                    console.log("Error: " + data.message);
                    //Move dragged shift back to the original position
                    ui.draggable.animate({top: 0, left: 0}, 'slow');
                }

            },
            error: function (data, status, headers, config) {
                //TODO Deal with serious error
                console.log("Serious error: " + data.message);
                //Move dragged shift back to the original position
                ui.draggable.animate({top: 0, left: 0}, 'slow');
            }});

    }
</script>
<?php
require_once('../scripts/footer.php');
?>