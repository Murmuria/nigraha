<?php
mysql_connect('localhost', 'cake', 'cakephp');
mysql_select_db("cake");

$query = "SELECT code, title, dept, semester, credits, practical_hours, tutorial_hours, presentation_wt, area FROM syllabus";
$result = mysql_query( $query ) or die(mysql_error());

while($arr = mysql_fetch_assoc($result)) {
	$dept = $arr['dept'];
	if($dept == "all") $dept = NULL;
	if($dept == "IT") $dept = "CP";

	$query2 = "INSERT INTO courses VALUES ('', '".$arr['code']."','".$arr['title']."','".$dept."','".$arr['semester']."','".$arr['credits']."','".$arr['practical_hours']."','".$arr['tutorial_hours']."','".$arr['presentation_wt']."','".$arr['area']."')";
	$result2 = mysql_query( $query2 );
	var_dump($result2);
}	

?>
