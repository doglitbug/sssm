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
$date = getmondayOfWeek(date("Y-m-d"));

//TODO span: day, week, month, 7 day. Default week
$span = 7;

//TODO Check to see if user is trying to view a different user without being a manager

//Build query
$query = "SELECT * FROM tbl_schedule 
WHERE user_id='1' AND start_date<='2017-04-02' AND 
(occurrences=0 OR (DATE_ADD(start_date, INTERVAL ((occurrences-1)*7) DAY)>='2017-03-27'))
ORDER BY DAYOFWEEK(start_date)";

$result = mysqli_query($dbc, $query) or die('Error getting schedule data: ' . mysqli_error($dbc));

if (mysqli_num_rows($result) == 0){
	echo "<div>User has no schedule events for this time</div><br/>";
} else {
	while ($row = mysqli_fetch_array($result)) {
		//TODO Calculate this events actual date
		//date("Y-m-d",strtotime("+8 day",$monday_of_week))
		
		//Get day of week(seeing as we cant figure out the date as yet)
		$day_of_week=date('l', strtotime($row['start_date']));

		echo "<h2>$day_of_week</h2>\n";
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