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
                    <select name="user_id" class="form-control">
                        <option value="0">Open shift</option>
                        <option value="1">TODO Add all users here!</option>
                    </select>
                </div>

                <div class="col-sm-3">
                    <label for="start_date">Date:</label>
                    <div id="datepicker"></div>
                </div>
                <div class="col-sm-3">
                    <label for="start_time">at</label>
                    <select name="start_time">
                        <?php
                        $tStart = strtotime("0:00");
                        $tEnd = strtotime("24:00");
                        $tNow = $tStart;

                        while ($tNow <= $tEnd) {
                            $prettyFormat = date("H:i", $tNow);
                            echo "<option value='$prettyFormat'>$prettyFormat</option>\n";
                            $tNow = strtotime('+15 minutes', $tNow);
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-3">
                    <label for="end_time">to</label>
                    <select name="end_time">
                        <?php
                        $tStart = strtotime("0:00");
                        $tEnd = strtotime("24:00");
                        $tNow = $tStart;

                        while ($tNow <= $tEnd) {
                            $prettyFormat = date("H:i", $tNow);
                            echo "<option value='$prettyFormat'>$prettyFormat</option>\n";
                            $tNow = strtotime('+15 minutes', $tNow);
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <label for="description">Description of shift</label>
                <input type="text" class="form-control" name="description" value="Open shift">
            </div>

            <div class="form-group-container">
                <div class="col-md-12">

                    <button type="submit" class="btn btn-success">Submit</button>
                    <button type="reset" value="Reset" class="btn btn-info">Reset</button>
                    <button type="button" id="cancel" class="btn btn-danger">Cancel</button>

                </div>
            </div>

        </form>



    </div>


    <div id="tabs-update" class="content"><p>This is the update tab</p></div>
</div>
<div class="col-md-12"><button id="change">Change</button></div>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
    //Make tabs selectable? TODO Is this needed if tabs are not showen?
    $(function () {
        $("#tabs").tabs();
    });

    $("#addShift").submit(function (event) {
        alert("Go add shift and report back!")
        event.preventDefault();
    });
//TODO remove, this shows how to change tabs programatically
    $(document).on("click", "button", function (event) {
        $("#tabs").tabs("option", "active", 1);
        console.log("loaded");
    });

    $("#datepicker").datepicker();
</script>
<?php
require_once('../scripts/footer.php');
?>