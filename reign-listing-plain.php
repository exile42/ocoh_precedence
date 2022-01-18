<?
include("includes/constants.php");
include("includes/ez_sql.php");
include("includes/functions.php");

if (isset($_REQUEST['GroupID']) && is_numeric($_REQUEST['GroupID'])){
	$GroupID = $_REQUEST['GroupID'];
} elseif (isset($_REQUEST['GroupID']) && !(is_numeric($_REQUEST['GroupID']))) {
	$err = "Bad data in the GroupID field; please go back and fix it or leave the GroupID off (will default to the kingdom's ID)";
} else {
	$GroupID = $MYGROUPID;
}
	
if ($err) {
	die($err);
} else {
	$firstloop = 1;
	$newline ="";
	$db_query_award="SELECT ReignID, RPersonID, CPersonID, RUseName, CUseName, RUseTitle, CUseTitle, Start, End FROM wReigns WHERE GroupID=$GroupID ORDER BY ReignOrder";
	
	$results_award = $db->get_results($db_query_award);
	foreach($results_award as $r) { 
		while(list($name,$value) = each($r)) { //name all values
			$$name = $value;
		}; // end while
		if (!$firstloop)
			$newline = "\n";
					
		//print out the info
		print $newline . $RUseName .";" . $CUseName . ";" . $Start . ";" . $End;

		// hacky bit to not have a leading/trailing newline in the file
		if ($firstloop)
			$firstloop = 0;

	}//end foreach
}// end $err
?>