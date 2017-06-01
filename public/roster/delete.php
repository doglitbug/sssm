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
    $roster_id = mysqli_real_escape_string($dbc, isset($_POST['roster_id']) ? trim($_POST['roster_id']) : '');

    //Check required data is present
    //TODO Check it is a numeric value
    if ($roster_id == '') {
        throw new Exception("Required fields missing");
    }

    //Build insert query
    $query = "DELETE FROM tbl_roster WHERE roster_id='$roster_id' LIMIT 1";

    //Do query and build output data
    if (mysqli_query($dbc, $query)) {
        $data['success'] = true;
        $data['message'] = "Shift deleted.";
    } else {
        throw new Exception("Could not delete shift: " + mysqli_error($dbc));
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