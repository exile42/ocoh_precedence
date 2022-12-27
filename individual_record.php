<?

function Personal($id){
/*	global $cstring;

	$rollofarms_link = "http://wharrow.outlandsheralds.org/individual_record.php?PersonID=$head[PersonID]";
	
	$header_query="SELECT P.PersonID, P.SCAName, P.ModernName, P.Deceased, Groups.Name AS 'Group', Kingdoms.Name AS 'Kingdom', H.Blazon, H.Filename, P.RegName FROM wPeople P LEFT OUTER JOIN wHeraldry H ON P.PersonID = H.PersonID LEFT OUTER JOIN Kingdoms ON Kingdoms.KingdomID = P.KingdomID LEFT OUTER JOIN Groups ON Groups.GroupID = P.GroupID WHERE P.PersonID = $id";
	$head_result = mysql_query($header_query, $cstring) or die("MySQL Error (header)!! ".mysql_error());
	$head = mysql_fetch_assoc($head_result);

$header_results = $db->get_results($header_query);
if ($header_results) {
	?><table border="0" width="560" cellpadding="3" cellspacing="3"><?
	foreach ($header_results as $r){
		while(list($name,$value) = each($r)) { //name all values
			$$name = $value;
		};
	// yes, this is clunky as hell.
	if ($Group!="")					//known group
		if ($Kingdom!="Outlands") 	//not in outlands
			$print_group=$Group . ", " . $Kingdom; //print both
		else								//in outlands
			$print_group=$Group;		//print group only
	else									//unknown group
		$print_group=$Kingdom;		//print kingdom only

	?><table border="0" width="560"><tr>
	<td valign="middle"><span class="bodyheader2"><?php echo $head[SCAName]?> (<?php echo $print_group?>)</span><br /><?
	// Added emblazon-functionality for those who have one in the RoA, and blazon-only for those without
	if ($Deceased==1){
		?><span class="small">(Deceased)</span><br /><?
	}// end if dead'
	
	if (isset($Blazon) && $RegName == 1 && isset($Filename)){
		?><span class="small">Name And Device Registered</td>
		<td align="right" valign="middle"><a href="<?php echo $rollofarms_link?>"><img src="http://wharrow.outlandsheralds.org/images/<?php echo $Filename?>" alt="[<?php echo $Blazon?>]" style="width:70px; border:0px;"></a></td>
		<table border=0>
		<tr><td align="middle"><div class="centered"><?php echo $Blazon?></div><br /><br /></td></tr>
		</table><?
	} else if (isset($Blazon) && $RegName == 1 ){
		?><span class="small">Name And Device Registered</span><br />
		<div class="centered"><?php echo $Blazon?></div><br /><?
	} else if ($RegName == 1){
		?><span class="small">Name Registered</span><br /><?
	} else {
		?><span class="small">Name NOT Registered</span><br />"<?
	}// end if name or device registered
*/
	
}// end Personal()

	

include 'includes/constants.php';
include 'includes/ez_sql.php';
include 'includes/functions.php';
include 'includes/header.php';

if (isset($_REQUEST['PersonID']) && is_numeric($_REQUEST['PersonID'])){
	$PersonID = $_REQUEST['PersonID'];
} else {
	$err = "We have no idea who you are talking about.  Go back and try again?";
}
	
if ($err) {
	die($err);
} else {
//	Personal($_REQUEST['PersonID']);
	
	$header_query="SELECT P.PersonID, P.SCAName, P.ModernName, P.Deceased, G.Name AS LocalGroup, K.Name AS Kingdom, P.RegName FROM wPeople P LEFT OUTER JOIN Kingdoms K ON K.KingdomID = P.KingdomID LEFT OUTER JOIN Groups G ON G.GroupID = P.GroupID WHERE P.PersonID = $PersonID";
	$header_results = $db->get_results($header_query);
	if ($header_results) {
		?><table border="0" width="560" cellpadding="3" cellspacing="3"><?
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
	
		?><table border="0" width="560"><tr>
		<td valign="middle"><span class="bodyheader2"><?php echo $SCAName?> (<?php echo $print_group?>)</span><br /><?
		//if someone has passed on, note this here.
		if ($Deceased==1){
			?><span class="small">(Deceased)</span><br /><?
		}// end if dead
		
		// Displaying information about registered primary name/device, inc'l emblazon if one is available
		$heraldry_query="SELECT H.Blazon, H.Filename FROM wHeraldry H WHERE H.PersonID = $PersonID AND H.Type = 'Device'";
		$heraldry_results = $db->get_results($heraldry_query);
		if ($heraldry_results) {
			?><table border="0" width="560" cellpadding="3" cellspacing="3"><?
			foreach ($heraldry_results as $r){
				while(list($name,$value) = each($r)) { //name all values
					$$name = $value;
				};
			}// end foreach

			if (isset($Blazon) && $RegName == 1 && isset($Filename)){
				?><span class="small">Name And Device Registered</td>
				<td align="right" valign="middle"><a href="<?php echo $ROLLOFARMSURL?>/individual_record.php?PersonID=<?php echo $PersonID?>"><img src="<?php echo $ROLLOFARMSURL?>/images/<?php echo $Filename?>" alt="[<?php echo $Blazon?>]" style="width:70px; border:0px;"></a></td>
				<table border=0>
				<tr><td align="middle"><div class="centered"><?php echo $Blazon?></div><br /><br /></td></tr>
				</table><?
			} else if (isset($Blazon) && $RegName == 1 ){
				?><span class="small">Name And Device Registered</span><br />
				<div class="centered"><?php echo $Blazon?></div><br /><?
			} else if ($RegName == 1){
				?><span class="small">Name Registered</span><br /><?
			} else {
				?><span class="small">Name NOT Registered</span><br /><?
			}// end if name or device registered
		} // end if heraldry results
	}// end if results

	$award_query="SELECT A.AwardID, A.Name, K.Name AS Kingdom, G.Name AS LocalGroup, A.RankID, A.Rank, AG.Date, AG.AwardedBy, AG.Resigned, AG.Premier, AG.Reason, AG.Visible, AG.ReignID, AG.Notes, A.GiverType, DATE_FORMAT(AG.Date, '%Y-%m-%d') AS NiceDate FROM wAwardsGiven AG INNER JOIN wAwards A ON AG.AwardID = A.AwardID LEFT OUTER JOIN Kingdoms K ON K.KingdomID = A.KingdomID LEFT OUTER JOIN Groups G ON G.GroupID = A.GroupID WHERE AG.PersonID = $PersonID ORDER BY A.Rank, AG.Date";
	$award_results = $db->get_results($award_query);
	if ($award_results) {
		?><table border="0" width="560" cellpadding="3" cellspacing="3"><?
		foreach ($award_results as $r){
			while(list($name,$value) = each($r)) { //name all values
				$$name = $value;
			};
		
			if ($Premier==1) {$p = " (premier)"; } else {$p = "";}
			if ($Reason) {$r = " ($Reason)";} else {$r = "";}
			if ($Rank == 60 || $Rank == 70) {$a = " (AoA)"; } else {$a = "";}
			
			//So, what are the speshul cases here?
			//If there is a single giver, show the AwardedBy
			//If not from here, is AoA, GoA, Aug, or Rose, show no notes
			//If not from here, is other "SCA" (which should be PoAs, CBs, Royal Ps), show the "Notes" (should be kingdom of giving??  what else is in those notes?)
			//If not from here, is other kingdom's, show the Kingdom of giving
			//If from here, not a baronial (localgroup is  -not- set), show nothing
			//If from here, baronial (localgroup is set), show local group
			//yeah, speshul.
			//so, for Awards, add flags ShowGiver, ShowKingdom, ShowLocalGroup, ShowGivenNotes
			//For AwardsGiven, add separate "Givenfor" vs generic Notes ... AND a PrivateNotes  ... and a KingdomIDGivenBy for AoA/GoA/Aug/Rose?

/*			
			if ($ShowAwardedBy) $GiverText = "[$AwardedBy] ";
			if ($ShowKingdom) $GroupText = " ($Kingdom) ";
			if ($ShowLocalGroup) $GroupText = " ($LocalGroup) ";
			if ($ShowGiverKingdom) {
				//do some code to get the kingdom from the kingdomid in the AG
				$GroupText = " ($KingdomGivenBy)";
			}
			if ($ShowGivenNotes) $NotesText = " </ br>$Notes ";
			
			?><tr><td><?php echo $Name?> $GiverText  $GroupText $NotesText</td><td align="right"><?php echo $NiceDate?></td></tr><?
*/
			
			if ($Visible && !$Resigned){
				if ($GiverType != 'B' && isset($AwardedBy)){
					?><tr><td><?php echo $Name?> [<?php echo $AwardedBy?>] -- (<?php echo $Kingdom?>)</td><td align="right"><?php echo $NiceDate?></td></tr><?
				} else if ($Kingdom != $WEAREKINGDOM){
					if ($Kingdom == "SCA"){
						if ($AwardID == 3 || $AwardID == 257 || $AwardID == 7 || $AwardID == 275){
							?><tr><td><?php echo $Name?> <?php echo $a;?><?php echo $p;?><?php echo $r;?></td><td align="right"><?php echo $NiceDate?></td></tr><?
						} else {
							?><tr><td><?php echo $Name?> -- <?php echo $a;?><?php echo $p;?> <?php echo $Notes?></td><td align="right"><?php echo $NiceDate?></td></tr><?
						} // end if a special award
					} else {
						?><tr><td><?php echo $Name?> -- <?php echo $Kingdom?> <?php echo $a;?><?php echo $r;?></td><td align="right"><?php echo $NiceDate?></td></tr><?
					}//end if not Outlands Award
				} else if (!isset($LocalGroup)){
					?><tr><td><?php echo $Name?> <?php echo $a;?><?php echo $p;?><?php echo $r;?></td><td align="right"><?php echo $NiceDate?></td></tr><?
				} else {
					?><tr><td><?php echo $Name?> -- <?php echo $LocalGroup?> <?php echo $a;?><?php echo $p;?><?php echo $r;?></td><td align="right"><?php echo $NiceDate?></td></tr><?
				}// end if Givertype
			} // end if Visible and not Resigned
		}// end foreach
		?></table><?
	} // end if
		
	$alias_query="SELECT A.Alias as Name, P.PersonID FROM wPeople P LEFT OUTER JOIN wAliases A ON P.PersonID = A.PersonID WHERE A.PersonID = $PersonID";
	$results_alias = $db->get_results($alias_query);
	if ($results_alias) {
		?><p><span class="bodyheader2">Known Aliases</span></p>
		<table border="0" width="560" cellpadding="3" cellspacing="3"><?
		foreach($results_alias as $r) { 
			while(list($name,$value) = each($r)) { //name all values
				$$name = $value;
			};
				?><tr><td><?php echo $Name?></td></tr><?
		}// end foreach
		?></table><?
	} //end if

	if ($accessOPView =="1") {
		?><p><a href="view_person.php?PersonID=<?php echo $PersonID?>">VIEW DETAILS</a><?
     	if ($accessWimble =="1") {
            ?> | <a href="edit_user.php?PersonID=<?php echo $PersonID?>&Action=Edit">EDIT PERSON</a> |
            <a href="edit_awards.php?PersonID=<?php echo $PersonID?>">EDIT AWARDS</a> </p><?
        } // end if $accessWimble
		?></p><?
	} // end if $accessOPView
} //end if error

include 'includes/footer.php';
?>
