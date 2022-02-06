<?php
include("includes/constants.php");
include("includes/ez_sql.php");
include("includes/functions.php");
if (isset($_REQUEST['PersonID']) && is_numeric($_REQUEST['PersonID'])){
	$PersonID = $_REQUEST['PersonID'];
} else {
	$err = "We have no idea who you are talking about.  Go back and try again?";
}
	
if ($err) {
	die($err);
} else {
//	Personal($_REQUEST['PersonID']);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta name="robots" content="noindex, nocache, noarchive" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head><body>
<?php	
	$header_query="SELECT P.PersonID, P.SCAName, P.ModernName, P.Deceased, P.Active, G.Name AS LocalGroup, K.Name AS Kingdom, P.RegName FROM wPeople P LEFT OUTER JOIN Kingdoms K ON K.KingdomID = P.KingdomID LEFT OUTER JOIN Groups G ON G.GroupID = P.GroupID WHERE P.PersonID = $PersonID";
	$header_results = $db->get_results($header_query);
	if ($header_results) {
		foreach ($header_results as $r){
			while(list($name,$value) = each($r)) { //name all values
				$$name = $value;
			};
		}// end foreach
		// Clunky way to determine what of group and kingdom location to print
		if ($LocalGroup != "")					//if we know what subgroup they're in
			if ($Kingdom != $WEAREKINGDOM) 	//and it's not in our kingdom
				$print_group=$LocalGroup . ", " . $Kingdom; //print both group and kingdom
			else								//but if it's in our kingdom
				$print_group=$LocalGroup;		//print group only
		else									//but when it's an unknown group
			$print_group=$Kingdom;		//print kingdom only
	
		// print out the basic info
		print $SCAName . ";" . $print_group . ";" . $Deceased . ";" . $Active;
		
		// Figure out and include the URL for the emblazon IF they have one
		$heraldry_query="SELECT H.PersonID, H.Blazon, H.Filename, H.Type FROM wHeraldry H WHERE H.PersonID = $PersonID AND H.Type LIKE \"%Device%\"";
//		$heraldry_results = $db->get_results($heraldry_query);
		if ($heraldry_results = $db->get_results($heraldry_query)) {
			foreach ($heraldry_results as $r){
				while(list($name,$value) = each($r)) { //name all values
					$$name = $value;
				};
			}// end foreach
			if (isset($Blazon) && $RegName == 1 && isset($Filename)){
				 print ";" . $ROLLOFARMSURL  . "/images/" . $Filename; 
			} else {
				print ";"; 
			} // end if name or device registered
		} else { // no results
				print ";"; 
 		}// end are there any heraldic results
	}// end if results

	$alias_query="SELECT A.Alias as Alias, P.PersonID FROM wPeople P LEFT OUTER JOIN wAliases A ON P.PersonID = A.PersonID WHERE A.PersonID = $PersonID";
	$results_alias = $db->get_results($alias_query);
	if ($results_alias) {
		foreach($results_alias as $r) { 
			while(list($name,$value) = each($r)) { //name all values
				$$name = $value;
			};
			print ";" . $Alias;
		}// end foreach result
	} //end if results

	print "<br>\n";

	$firstloop = 1;
	$semi = "";
	$award_query="SELECT A.AwardID, A.Name, K.Name AS Kingdom, G.Name AS LocalGroup, A.RankID, A.Rank, AG.Date, AG.AwardedBy, AG.Resigned, AG.Visible, AG.ReignID, AG.Notes, A.GiverType, DATE_FORMAT(AG.Date, '%Y-%m-%d') AS NiceDate FROM wAwardsGiven AG INNER JOIN wAwards A ON AG.AwardID = A.AwardID LEFT OUTER JOIN Kingdoms K ON K.KingdomID = A.KingdomID LEFT OUTER JOIN Groups G ON G.GroupID = A.GroupID WHERE AG.PersonID = $PersonID ORDER BY A.Rank, AG.Date";
	$award_results = $db->get_results($award_query);
	if ($award_results) {
		foreach ($award_results as $r){
			while(list($name,$value) = each($r)) { //name all values
				$$name = $value;
			};
		
			if ($Visible){
				if (!$firstloop)
					$semi = ";";
					
				if ($Resigned)
					$Resignedtext = "$" . "Resigned";
				else
					$Resignedtext = "";
					
				// If KSCA or MSCA, print NOTES field
				if ($AwardID == 262 || $AwardID == 282)
					$Notestext = "$" . $Notes;
				else 
					$Notestext = "";
				
				print $semi . $Name . "$" . $NiceDate . $Resignedtext . $Notestext;
			} // end if Visible

			// hacky bit to not have a trailing semicolon
			if ($firstloop)
				$firstloop = 0;
		}// end foreach
		print "\n";
	} // end if results
		

} // end if error
?>
</body></html>