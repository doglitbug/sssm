<?php
$title = "View schedule";
$login = true;
require_once('../scripts/header.php');

//TODO Check for form data:
//TODO user_id, default current
if (isset($_GET['user_id'])){
	//Get requested user_id
	$user_id  = mysqli_real_escape_string($dbc, trim($_GET['user_id']));
	//Check if we are not viewing ourself, if so are we a manager
	if ($_SESSION['user_id']!=$user_id AND $_SESSION['manager']!='1'){
		echo "<h1>Manager access required</h1>\n";
		echo "<ul>\n";
		echo "<li><a href='../index.php'>Click here to return to home page</a></li>\n";
		echo "</ul>\n";
		require_once('../scripts/footer.php');
		die();
	}
} else {
	//View schedule for ourselves
	$user_id = $_SESSION['user_id'];
}

//TODO date, default today/monday
$start_date = date("Y-m-d",getmondayOfWeek(date("Y-m-d")));
$end_date   = date("Y-m-d",strtotime("+1 week",getmondayOfWeek(date("Y-m-d"))));
//TODO span: day, week, month, 7 day. Default week
//TODO The ordering of the events is gonna really screw up when other than 7 days...
$span = 7;

//Get details on user we are viewing
$query = "SELECT CONCAT(firstname,' ',lastname) AS name from tbl_user WHERE user_id='$user_id' LIMIT 1";
$result = mysqli_query($dbc, $query) or die('Error getting users name: ' . mysqli_error($dbc));
//Check for result
if (mysqli_num_rows($result) == 0){
	echo "<h1>Invalid user_id</h1><br/>";
	die();
}
//Get name
$user=mysqli_fetch_array($result)['name'];

echo "<h1>View availability for $user, week starting ".date("d-m-Y",strtotime($start_date))."</h1>";

//Display staff selector if manager
if ($_SESSION['manager']==1){
	//Display a search box for all users
	$query = "SELECT CONCAT(firstname,' ',lastname) AS name, username, user_id from tbl_user";
	$result = mysqli_query($dbc, $query) or die('Error getting list of all staff: ' . mysqli_error($dbc));
	?>
	<h3>View schedule for user:</h3>
	<form method="get" action="#">
	<div class="form-group container">

	<?php
	echo "<select name='user_id'>\n";
	while ($row = mysqli_fetch_array($result)) {
		echo "<option value='".$row['user_id']."'>".$row['name']." (".$row['username'].")</option>\n";
	}
	echo "</select>\n";
	?>
	</div>
	<div class="form-group container">
		<button type="submit" name="view" class="btn btn-default">View</button>
	</div>
	</form>
	<?php
}

//Build query for weeks data
$query = "SELECT * FROM tbl_schedule 
WHERE user_id='$user_id' AND start_date<='$end_date' AND 
(occurrences=0 OR (DATE_ADD(start_date, INTERVAL ((occurrences-1)*7) DAY)>='$start_date'))
ORDER BY (case DAYOFWEEK(start_date) WHEN 1 THEN 8 else DAYOFWEEK(start_date) END), start_time";

$result = mysqli_query($dbc, $query) or die('Error getting schedule data: ' . mysqli_error($dbc));

if (mysqli_num_rows($result) == 0){
	echo "<div>User has no schedule events for this time</div><br/>";
} else {
	$last_day_of_week=-1;
	while ($row = mysqli_fetch_array($result)) {
		//TODO Calculate this events actual date
		//date("Y-m-d",strtotime("+8 day",$monday_of_week))
		
		//Find out if we need to print a new weekday header
		$day_of_week=date('w', strtotime($row['start_date']));
		//If it has changed from the last one printed...
		if($day_of_week!=$last_day_of_week){
			$last_day_of_week=$day_of_week;

			echo "<h2>".date('l', strtotime($row['start_date']))."</h2>\n";
		}

		echo "Time: ".$row['start_time']." - ".$row['end_time']."<br/>\n";
		echo $row['description']."<br/>\n";
	}
}

?>

<?php
require_once('../scripts/footer.php');

function getMondayOfWeek($date){
	/**
	* Find the monday(default start) of the week in which the given date falls
	* http://stackoverflow.com/questions/11771062/when-a-date-is-given-how-to-get-the-date-of-monday-of-that-week-in-php
	* @param $date Date of the given week
	* @returns Closest proceeding Monday, if not already a Monday
	**/
	if (!is_numeric($date))
        $date = strtotime($date);
    if (date('w', $date) == 1)
        return $date;
    else
        return strtotime('last monday',$date);
}
?>