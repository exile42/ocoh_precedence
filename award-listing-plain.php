<?
include("includes/constants.php");
include("includes/ez_sql.php");
include("includes/functions.php");
if (isset($_REQUEST['AwardID']) && is_numeric($_REQUEST['AwardID'])){
	$AwardID = $_REQUEST['AwardID'];
} else {
	$err = "We have no idea which award you are talking about.  Go back and try again?";
}
	
if ($err) {
	die($err);
} else {
	$firstloop = 1;
	$newline ="";
	$db_query_recipients=" SELECT AG.AwardsGivenID, AG.Date, DATE_FORMAT(AG.Date, '%Y-%m-%d') AS NiceDate, P.PersonID as PersonID, P.SCAName as SCAName FROM wAwardsGiven AG INNER JOIN wPeople P ON AG.PersonID = P.PersonID WHERE AG.AwardID=$AwardID AND AG.Visible=1 AND AG.Resigned=0 ORDER BY AG.Date";
	
	$results_recipients = $db->get_results($db_query_recipients);
	foreach($results_recipients as $r) { 
		while(list($name,$value) = each($r)) { //name all values
			$$name = $value;
		};
		if (!$firstloop)
			$newline = "\n";

		// print out the basic info
		print $newline . $SCAName . ";" . $PersonID . ";" . $NiceDate;

		// hacky bit to not have a leading/trailing newline in the file
		if ($firstloop)
			$firstloop = 0;

	};  //end foreach
}// end if $err
?>