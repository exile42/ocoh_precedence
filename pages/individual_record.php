<?php

function Personal($id){
/*	global $cstring;

	$rollofarms_link = "http://wharrow.outlandsheralds.org/individual_record.php?PersonID=$head[PersonID]";
	
	$header_query="SELECT P.PersonID, P.SCAName, P.ModernName, P.Deceased, Groups.Name AS 'Group', Kingdoms.Name AS 'Kingdom', H.Blazon, H.Filename, P.RegName FROM wPeople P LEFT OUTER JOIN wHeraldry H ON P.PersonID = H.PersonID LEFT OUTER JOIN Kingdoms ON Kingdoms.KingdomID = P.KingdomID LEFT OUTER JOIN Groups ON Groups.GroupID = P.GroupID WHERE P.PersonID = $id";
	$head_result = mysql_query($header_query, $cstring) or die("MySQL Error (header)!! ".mysql_error());
	$head = mysql_fetch_assoc($head_result);

$header_results = $db->get_results($header_query);
if ($header_results) {
	?><table border="0" width="560" cellpadding="3" cellspacing="3"><?php
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
	<td valign="middle"><span class="bodyheader2"><?php echo $head[SCAName]?> (<?php echo $print_group?>)</span><br /><?php
	// Added emblazon-functionality for those who have one in the RoA, and blazon-only for those without
	if ($Deceased==1){
		?><span class="small">(Deceased)</span><br /><?php
	}// end if dead
	
	if (isset($Blazon) && $RegName == 1 && isset($Filename)){
		?><span class="small">Name And Device Registered</td>
		<td align="right" valign="middle"><a href="<?php echo $rollofarms_link?>"><img src="http://wharrow.outlandsheralds.org/images/<?php echo $Filename?>" alt="[<?php echo $Blazon?>]" style="width:70px; border:0px;"></a></td>
		<table border=0>
		<tr><td align="middle"><div class="centered"><?php echo $Blazon?></div><br /><br /></td></tr>
		</table><?php
	} else if (isset($Blazon) && $RegName == 1 ){
		?><span class="small">Name And Device Registered</span><br />
		<div class="centered"><?php echo $Blazon?></div><br /><?php
	} else if ($RegName == 1){
		?><span class="small">Name Registered</span><br /><?php
	} else {
		?><span class="small">Name NOT Registered</span><br />"<?php
	}// end if name or device registered
*/
	
}// end Personal()

	

include("includes/constants.php");
//include("includes/ez_sql.php");
include("includes/ae_sql.php");
include("includes/functions.php");
include("includes/header.php");

if (isset($_REQUEST['PersonID']) && is_numeric($_REQUEST['PersonID'])){
	$PersonID = $_REQUEST['PersonID'];
} else {
	$err = "We have no idea who you are talking about.  Go back and try again?";
}
	
if ($err) {
	die($err);
} else {
//	Personal($_REQUEST['PersonID']);
	$cstring = new mysqli($SQLSERVER, $SQLUSERNAME, $SQLPASSWORD, $SQLDATABASE);
	if ($cstring->connect_error) {
		die("Connection failed: " . $cstring->connect_error);
		}

	$header_query="SELECT P.PersonID, P.SCAName, P.ModernName, P.Deceased, P.Pronouns, G.Name AS LocalGroup, K.Name AS Kingdom, P.RegName FROM wPeople P LEFT OUTER JOIN Kingdoms K ON K.KingdomID = P.KingdomID LEFT OUTER JOIN Groups G ON G.GroupID = P.GroupID WHERE P.PersonID = $PersonID";
	$header_results = $cstring->query($header_query) or die("MySQL Error(Individal Header)!!" .$cstring->error);
	
	if ($header_results) {
		?><table border="0" width="560" cellpadding="3" cellspacing="3"><?php
		while ($head[] = $head_result->fetch_assoc());
		array_pop($head);
		foreach ($head as $person){
			$list_result=null;
			$list = null;
		}// end foreach
		// Clunky way to determine what of group and kingdom location to print
		if ($person[LocalGroup] != "")					//if we know what subgroup they're in
			if ($person[Kingdom] != $WEAREKINGDOM) 	//and it's not in our kingdom
				$print_group=$person[LocalGroup] . ", " . $person[Kingdom]; //print both group and kingdom
			else								//but if it's in our kingdom
				$print_group=$person[LocalGroup];		//print group only
		else									//but when it's an unknown group
			$print_group=$person[Kingdom];		//print kingdom only
	
		?><table border="0" width="560"><tr>
		<td valign="middle"><span class="bodyheader2"><?php echo $person[SCAName]?> (<?php echo $person[print_group]?>)</span><br /><?php
		//if someone has pronouns to display, show them here.
		if ($person[Pronouns]){
			?><span class="small">(<?print $person[Pronouns]?>)</span><?php
		}// end pronouns
		
		//if someone has passed on, note this here.
		if ($person[Deceased]==1){
			?><span class="small">(Deceased)</span><br /><?php
		}// end if dead
		
		// Displaying information about registered primary name/device, inc'l emblazon if one is available
		$heraldry_query="SELECT H.Blazon, H.Filename FROM wHeraldry H WHERE H.PersonID = $PersonID AND H.Type = 'Device'";
		$heraldry_results = $cstring->query($heraldry_query) or die("MySQL Error(heraldry)!!" .$cstring->error);
		
		if ($heraldry_results) {
		?><table border="0" width="560" cellpadding="3" cellspacing="3"><?php
		while ($registration[] = $registration_result->fetch_assoc());
		array_pop($registration);
		foreach ($registration as $heraldry) {

			if (isset($heraldry[Blazon]) && $person[RegName] == 1 && isset($heraldry[Filename])){
				?><span class="small">Name And Device Registered</td>
				<td align="right" valign="middle"><a href="<?php echo $ROLLOFARMSURL?>/individual_record.php?PersonID=<?php echo $PersonID?>"><img src="<?php echo $ROLLOFARMSURL?>/images/<?php echo $heraldry[Filename]?>" alt="[<?php echo $heraldry[Blazon]?>]" style="width:70px; border:0px;"></a></td>
				<table border=0>
				<tr><td align="middle"><div class="centered"><?php echo $heraldry[Blazon]?></div><br /><br /></td></tr>
				</table><?php
			} else if (isset($heraldry[Blazon]) && $person[RegName] == 1 ){
				?><span class="small">Name And Device Registered</span><br />
				<div class="centered"><?php echo $heraldry[Blazon]?></div><br /><?php
			} else if ($person[RegName] == 1){
				?><span class="small">Name Registered</span><br /><?php
			} else {
				?><span class="small">Name NOT Registered</span><br /><?php
			}// end if name or device registered
		} // end if heraldry results
	}// end if results

	$award_query="SELECT A.AwardID, A.Name, K.Name AS Kingdom, G.Name AS LocalGroup, A.RankID, A.Rank, AG.Date, AG.AwardedBy, AG.Resigned, AG.Premier, AG.Reason, AG.Visible, AG.ReignID, AG.Notes, A.GiverType, DATE_FORMAT(AG.Date, '%Y-%m-%d') AS NiceDate FROM wAwardsGiven AG INNER JOIN wAwards A ON AG.AwardID = A.AwardID LEFT OUTER JOIN Kingdoms K ON K.KingdomID = A.KingdomID LEFT OUTER JOIN Groups G ON G.GroupID = A.GroupID WHERE AG.PersonID = $PersonID ORDER BY A.Rank, AG.Date";
	$award_results = $cstring->query($award_query) or die("MySQL Error (Awards)!! ".$cstring->error);

	if ($award_results) {
		?><table border="0" width="560" cellpadding="3" cellspacing="3"><?php
	while($award[] = $award_result->fetch_assoc());
	array_pop($award);
	foreach ($award_results as $awards){
		
		if ($awards[Premier]==1) {$p = " (premier)"; } else {$p = "";}
		if ($awards[Reason]) {$r = " ($Reason)";} else {$r = "";}
		if ($awards[Rank] == 60 || $awards[Rank] == 70) {$a = " (AoA)"; } else {$a = "";}
			
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
			
			/*if ($ShowAwardedBy) $GiverText = "[$AwardedBy] ";
			if ($ShowKingdom) $GroupText = " ($Kingdom) ";
			if ($ShowLocalGroup) $GroupText = " ($LocalGroup) ";
			if ($ShowGiverKingdom) {
				//do some code to get the kingdom from the kingdomid in the AG
				$GroupText = " ($KingdomGivenBy)";
			}
			if ($ShowGivenNotes) $NotesText = " </ br>$Notes ";
			
			?><tr><td><?php echo $Name?> $GiverText  $GroupText $NotesText</td><td align="right"><?php echo $NiceDate?></td></tr><?php */

			
			if ($awards[Visible] && !$awards[Resigned]){
				if ($awards[GiverType] != 'B' && isset($awards[AwardedBy])){
					?><tr><td><?php echo $awards[Name]?> [<?php echo $awards[AwardedBy]?>] -- (<?php echo $awards[Kingdom]?>)</td><td align="right"><?php echo $awards[NiceDate]?></td></tr><?php
				} else if ($awards[Kingdom] != $WEAREKINGDOM){
					if ($awards[Kingdom] == "SCA"){
						if ($awards[AwardID] == 3 || $awards[AwardID] == 257 || $awards[AwardID] == 7 || $awards[AwardID] == 275){
							?><tr><td><?php echo $awards[Name]?> <?php echo $a;?><?php echo $p;?><?php echo $r;?></td><td align="right"><?php echo $awards[NiceDate]?></td></tr><?php
						} else {
							?><tr><td><?php echo $awards[Name]?> -- <?php echo $a;?><?php echo $p;?> <?php echo $awards[Notes]?></td><td align="right"><?php echo $awards[NiceDate]?></td></tr><?php
						} // end if a special award
					} else {
						?><tr><td><?php echo $awards[Name]?> -- <?php echo $awards[Kingdom]?> <?php echo $a;?><?php echo $r;?></td><td align="right"><?php echo $awards[NiceDate]?></td></tr><?php
					}//end if not Outlands Award
				} else if (!isset($awards[LocalGroup])){
					?><tr><td><?php echo $awards[Name]?> <?php echo $a;?><?php echo $p;?><?php echo $r;?></td><td align="right"><?php echo $awards[NiceDate]?></td></tr><?php
				} else {
					?><tr><td><?php echo $awards[Name]?> -- <?php echo $awards[LocalGroup]?> <?php echo $a;?><?php echo $p;?><?php echo $r;?></td><td align="right"><?php echo $awards[NiceDate]?></td></tr><?php
				}// end if Givertype
			} // end if Visible and not Resigned
		}// end foreach
		?></table><?php
	} // end if
		
	$alias_query="SELECT A.Alias as Name, P.PersonID FROM wPeople P LEFT OUTER JOIN wAliases A ON P.PersonID = A.PersonID WHERE A.PersonID = $PersonID";
	$results_alias = $cstring->query($alias_query) or die("MySQL Error (Aliases)!! ".$cstring->error);

	if ($results_alias) {
		?><p><span class="bodyheader2">Known Aliases</span></p>
		<table border="0" width="560" cellpadding="3" cellspacing="3"><?php
	while($aliases[] = $results_alias->fetch_assoc());
	array_pop($aliases);
	foreach ($results_alias as $alias){
			?><tr><td><?php echo $alias[Name]?></td></tr><?php
	}// end foreach
		?></table><?php
	} //end if

	if ($accessOPView =="1") {
		?><p><a href="view_person.php?PersonID=<?php echo $PersonID?>">VIEW DETAILS</a><?php
     	if ($accessWimble =="1") {
            ?> | <a href="edit_user.php?PersonID=<?php echo $PersonID?>&Action=Edit">EDIT PERSON</a> |
            <a href="edit_awards.php?PersonID=<?php echo $PersonID?>">EDIT AWARDS</a> </p><?php
        } // end if $accessWimble
		?></p><?php
	} // end if $accessOPView
} //end if error

include("includes/footer.php");
?>
