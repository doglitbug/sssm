<?php
$title = "View schedule";
$login = true;
require_once('../scripts/header.php');

//Connect to database
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

//TODO Check for form data:
//TODO user_id, default current
$user_id = $_SESSION['user_id'];

//TODO date, default today/monday
$start_date = date("Y-m-d",getmondayOfWeek(date("Y-m-d")));
$end_date   = date("Y-m-d",strtotime("+1 week",getmondayOfWeek(date("Y-m-d"))));
//TODO span: day, week, month, 7 day. Default week
//TODO The ordering of the events is gonna really screw up when other than 7 days...
$span = 7;

//TODO Check to see if user is trying to view a different user without being a manager

//Build query

$query = "SELECT * FROM tbl_schedule 
WHERE user_id='1' AND start_date<='$end_date' AND 
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
<!-- TODO View schedule etc -->
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