<?php
$title = "Tabs demo";
require_once('../scripts/header.php');
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
<style>
    #tabs > div {
        display: none;

    }

    #tabs > ul {
        display: none;
    }

    #tabs{
        border: 0px;
    }

    div {
        border: 0px solid;
    }
</style>
<div id="tabs">
    <ul>
        <li><a href="#tabs-view">View all shifts</a></li>
        <li><a href="#tabs-add">Add shift</a></li>
        <li><a href="#tabs-update">Update shift</a></li>
    </ul>

    <div id="tabs-view" class="content"><p>This is the view tab</p></div>

    <div id="tabs-add" class="content">
        <h1>Add new shift</h1>
        <form id="addShift">

            <div class="form-group-container">
                <div class="col-md-12">
                    <label for="user_id">Staff member:</label>
                    <select id="user_id" name="user_id" class="form-control">
                        <?php
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

                        while ($user = array_shift($users)) {
                            echo "<option value='" . $user['user_id'] . "'";

                            echo ">" . $user['username'] . "</option>\n";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="start_date">Date:</label>
                    <input type="text" id="start_date">
                </div>
                <div class="col-md-3">
                    <label for="start_time">at</label>
                    <select id = "start_time" name="start_time">
                        <?php
                        $tStart = strtotime("0:00");
                        $tEnd = strtotime("24:00");
                        $tNow = $tStart;

                        while ($tNow <= $tEnd) {
                            $databaseFormat = date("H:i:s", $tNow);
                            $prettyFormat = date("g:i a", $tNow);
                            echo "<option value='$databaseFormat'>$prettyFormat</option>\n";
                            $tNow = strtotime('+15 minutes', $tNow);
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="end_time">to</label>
                    <select id="end_time" name="end_time">
                        <?php
                        $tStart = strtotime("0:00");
                        $tEnd = strtotime("24:00");
                        $tNow = $tStart;

                        while ($tNow <= $tEnd) {
                            $databaseFormat = date("H:i:s", $tNow);
                            $prettyFormat = date("g:i a", $tNow);
                            echo "<option value='$databaseFormat'>$prettyFormat</option>\n";
                            $tNow = strtotime('+15 minutes', $tNow);
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <label for="description">Description of shift</label>
                <input id="description" type="text" class="form-control" name="description" value="Description of shift">
            </div>

            <div class="form-group-container">
                <div class="col-md-12">

                    <button type="submit" id="submit" class="btn btn-success">Add/update</button>
                    <button type="button" id="cancel" class="btn btn-danger">Cancel</button>

                </div>
            </div>

        </form>



    </div>


    <div id="tabs-update" class="content"><p>This is the update tab</p></div>
</div>
<div class="col-md-12"><button id="change">Change</button></div>
<div class="col-md-12" id="alerts"></div>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
    //Make tabs selectable? TODO Is this needed if tabs are not showen?
    $(function () {
        $("#tabs").tabs();
    });

    // Submitting the form
    $("#addShift").submit(function (event) {
        //Get form data
        var user_id = $("#user_id option:selected").val();
        var start_date = $("#start_date").val();
        var start_time = $("#start_time").val();
        var end_time = $("#end_time").val();
        var description = $("#description").val();
        
        console.log(user_id);
        console.log(start_date);
        console.log(start_time);        
        console.log(end_time);
        console.log(description);
        
        //Show the user an alert
        //Craft alert
        var alert = $("<div class='alert alert-success'>Something happened that was good</div>");
        setTimeout(function() {
            alert.remove();
        }, 5000);
        
        $("#alerts").append(alert);
        event.preventDefault();
    });
//TODO remove, this shows how to change tabs programatically
    $(document).on("click", "button", function (event) {
        $("#tabs").tabs("option", "active", 1);
    });

    //Add in a datepicker
    $("#start_date").datepicker({dateFormat: "dd/mm/yy"});
    //Pre populate the date field
    //TODO set this to shift info...
    $("#start_date").datepicker("setDate", new Date());

</script>
<?php
require_once('../scripts/footer.php');
?>