<?php

require_once('../scripts/databaseconnection.php');
require_once('../scripts/startsession.php');
require_once('../scripts/sharedFunctions.php');

try {
    //Check that a manager is doing this(also checks log in)
    if(!isLoggedIn()){
        throw new Exception("Not logged in");
    }
    if(!isManager()){
        throw new Exception("Manager access required");
    }
    //Grab all post data
    $data = array();
    $user_id = mysqli_real_escape_string($dbc, isset($_POST['user_id']) ? trim($_POST['user_id']) : '');
    $start_date = mysqli_real_escape_string($dbc, isset($_POST['start_date']) ? trim($_POST['start_date']) : '');
    $start_time = mysqli_real_escape_string($dbc, isset($_POST['start_time']) ? trim($_POST['start_time']) : '');
    $end_time = mysqli_real_escape_string($dbc, isset($_POST['end_time']) ? trim($_POST['end_time']) : '');
    $description = mysqli_real_escape_string($dbc, isset($_POST['description']) ? trim($_POST['description']) : '');

    //Check required data is present
    if ($user_id == '' || $start_date == '' || $start_time == '' || $end_time == '') {
        throw new Exception("Required fields missing");
    }

    //Build insert query
    //INSERT INTO `sssm`.`tbl_roster` (`user_id`, `start_date`, `start_time`, `end_time`, `description`) VALUES ('0', '2017-06-04', '8:00:00', '10:00:00', 'Eat cheese');


    
    $query = "INSERT INTO tbl_roster (user_id, start_date, start_time, end_time, description) "
            . "VALUES ("
            . "'$user_id', '$start_date', '$start_time', '$end_time', '$description')";

    //Do query and build output data
    if (mysqli_query($dbc, $query)) {
        $data['success'] = true;
        $data['message'] = "Shift created successfully.";
    } else {
        throw new Exception("Could not create shift: " + mysqli_error($dbc));
    }
    mysqli_close($dbc);
    echo json_encode($data);
    exit;
} catch (Exception $ex) {
    $data = array();
    $data['success'] = false;
    $data['message'] = $ex->getMessage();
    echo json_encode($data);
    exit;
}
?>