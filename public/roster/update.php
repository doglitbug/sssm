<?php

require_once('../scripts/header.php');

try {
    //Grab all post data
    $data = array();
    $roster_id = mysqli_real_escape_string(isset($_POST['roster_id']) ? $_POST['roster_id'] : '');
    $user_id = mysqli_real_escape_string(isset($_POST['user_id']) ? $_POST['user_id'] : '0');
    $start_date = mysqli_real_escape_string(isset($_POST['start_date']) ? $_POST['start_date'] : '');
    $start_time = mysqli_real_escape_string(isset($_POST['start_time']) ? $_POST['start_time'] : '');
    $end_time = mysqli_real_escape_string(isset($_POST['end_time']) ? $_POST['end_time'] : '');
    $description = mysqli_real_escape_string(isset($_POST['description']) ? $_POST['description'] : '');

    //Check required data is present
    if ($roster_id == '' || $start_date == '' || $start_time == '' || $end_time == '') {
        throw new Exception("Required fields missing");
    }

    //Build update query
    $query = "UPDATE tbl_roster SET 'user_id'='$user_id', "
            . "'start_date'='$start_date', 'start_time'='$start_time', "
            . "'end_time'='$end_time', 'description'='$description' WHERE 'roster_id'='$roster_id'";

    //Do query and build output data
    if (mysqli_query($dbc, $query)) {
        $data['success'] = true;
        $data['message'] = "Shift updated successfully.";
    } else {
        throw new Exception("Could not update shift - " + mysqli_error($dbc));
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