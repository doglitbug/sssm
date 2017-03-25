<?php
$title = "Create new users";
require_once('../scripts/header.php');
//Connect to database
$GLOBALS['dbc'] = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

//Turn off key checks
mysqli_query($GLOBALS['dbc'], 'SET foreign_key_checks = 0');

//TODO Delete previous users

////////// Create two users, one manager one not //////////
//createUser("1","doglitbug","password","Arron","Dick","1","s_drac2@yahoo.com");
//createUserContact("1","0273655228","","doglitbug","cellphone");

//createUser("2","arthur","password","Arthur","Gumball","0","agumball@email.com");
//createUserContact("2","02112345678","034132152","a.gumball","cellphone");

////////// Create some schedule data for users //////////
$today = date("Y-m-d");
$monday_of_week = getMondayOfWeek($today);
//$tuesday_of_week = strtotime($monday_of_week
echo $today;
echo "<br/>";
echo date("Y-m-d", $monday_of_week);
echo "<br/>";

//Friday afternoon to saturday night all year
//createSchedule(1, "2016-01-06","16:00","24:00",0,"Looking after Samuel");
//createSchedule(1, "2016-01-07","00:00","17:40",0,"Looking after Samuel");

//Add a class on monday this week
//createSchedule(1,date("Y-m-d",$monday_of_week),"08:00","10:00",1,"Computer class");
//createSchedule(1,date("Y-m-d",$monday_of_week),"12:00","14:00",1,"Computer class");

//Add a exam next week on tuesday
createSchedule(1,date("Y-m-d",strtotime("+8 day",$monday_of_week)),"10:00","12:00",1,"Computer exam");

//Turn back on the key checks
mysqli_query($GLOBALS['dbc'], 'SET foreign_key_checks = 1');

function createUser($user_id, $username, $password, $firstname, $lastname, $manager, $email){
	//Encrpyt password
	$hashed_password = password_hash($password, PASSWORD_DEFAULT);
	
	//Build INSERT query
	$query = "INSERT INTO tbl_user (user_id, username, password, firstname, lastname, manager) VALUES ('$user_id', '$username', '$hashed_password', '$firstname', '$lastname', '$manager')";

	//Execute query
	mysqli_query($GLOBALS['dbc'], $query) or die('Couldn\'t add user: '.mysqli_error($GLOBALS['dbc']));

	//Build query to create user contact
	$query = "INSERT INTO tbl_contact (user_id, email) VALUES ('$user_id', '$email')";
	//Insert contact details
	mysqli_query($GLOBALS['dbc'], $query) or die('Couldn\'t add new user contact details: ' . mysqli_error($GLOBALS['dbc']));

	echo "Created user: ".$username."<br/>\n";
}

function createUserContact($user_id, $cellphone, $landline, $facebook, $preferred){
	//Build UPDATE query
	$query = "UPDATE tbl_contact SET cellphone='$cellphone', landline='$landline', facebook='$facebook', preferred='$preferred' WHERE user_id='$user_id'";

	//Execute query
	mysqli_query($GLOBALS['dbc'], $query) or die('Couldn\'t add user contact details: ' . mysqli_error($GLOBALS['dbc']));

	echo "Created contact details<br/>\n";
}

function createSchedule($user_id, $start_date, $start_time, $end_time, $occurrences, $description){
	//Reformat times
	$start_time.=":00";
	$end_time  .=":00";
	//Dates can be provided as yyyy-mm-dd

	//Build query	
	$query = "INSERT INTO tbl_schedule (user_id, start_date, start_time, end_time, occurrences, description) VALUES ('$user_id', '$start_date', '$start_time', '$end_time', '$occurrences', '$description')";

	//Insert new schedule
	mysqli_query($GLOBALS['dbc'], $query) or die('Couldn\'t add new schedule: ' . mysqli_error($GLOBALS['dbc']));

	echo "Created schedule:<br/>\n";
}

/**
* Find the monday(default start) of the week in which the given date falls
* http://stackoverflow.com/questions/11771062/when-a-date-is-given-how-to-get-the-date-of-monday-of-that-week-in-php
* @param $date Date of the given week
* @returns Closest proceeding Monday, if not already a Monday
**/
function getMondayOfWeek($date){
	if (!is_numeric($date))
        $date = strtotime($date);
    if (date('w', $date) == 1)
        return $date;
    else
        return strtotime('last monday',$date);
}
require_once('../scripts/footer.php');
?>