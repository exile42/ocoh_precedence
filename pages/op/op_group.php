<?php
include("includes/constants.php");
include("includes/ez_sql.php");
include("includes/functions.php");
include("includes/header.php");

if (!($cstring = mysql_connect($SQLSERVER, $SQLUSERNAME, $SQLPASSWORD))) echo "Unable to Connect to $SQLSERVER ".mysql_error()."<br/>";
if (!mysql_select_db($SQLDATABASE)) echo "Unable to select db $SQLDATABASE ".mysql_error();

// 1) is there a valid GroupID value? if so, list the OP of people
// 2) if there is a GroupID value but it's invalid, error
// 3) if there is no GroupID value, display the list of groups

if (isset($_REQUEST['GroupID']) && (is_numeric($_REQUEST['GroupID']) || ($_REQUEST['GroupID']=="ALL"))) {

	// so there is a valid GroupID value
	$GroupID = $_REQUEST['GroupID'];
 
	// get info about the group
	$db_query_group="SELECT Name as GroupName, Notes as GroupNotes FROM Groups WHERE GroupID=$GroupID";
	$results_group = $db->get_results($db_query_group);
	foreach($results_group as $r) { 
		while(list($name,$value) = each($r)) { //name all values
			$$name = $value;
		};
	} // end foreach results_group
	
	// get the subordinate groups, if any
	$subgroups = GetSubgroups($db, $GroupID, "");
/*	$db_query_child_groups="SELECT GroupID as ChildGroupID FROM Groups WHERE ParentGroupID=$GroupID";
	$results_child_groups = $db->get_results($db_query_child_groups);
	if ($results_child_groups) {
		foreach($results_child_groups as $r) { 
			while(list($name,$value) = each($r)) { //name all values
				$$name = $value;
				$subgroups .= " OR P.GroupID =$ChildGroupID ";
			};
		} // end foreach results_child_groups
	} // end if results_child_groups
*/

	$Groups = $GroupID . $subgroups;

	$op_sql = "SELECT P.PersonID AS ID, P.SCAName, G.Name AS 'Group', P.RegName, P.RegDevice, P.SortRank from wPeople P, Groups G WHERE P.GroupID=G.GroupID AND P.Active = 1 AND (P.GroupID=";

/*	
	if (isset($_REQUEST['style']) && ($_REQUEST['style'] == "long" || $_REQUEST['style'] == "short" ||$_REQUEST['style'] == "plain"))
		$style = $_REQUEST['style'];
	
	if (isset($_REQUEST['start']) && (is_numeric($_REQUEST['start'])) ) {
		$paged = 1;
		$start = $_REQUEST['start'];
	} else {
		$paged = 0;
	}
	
	switch ($_REQUEST['style']){
		case 'long':
			include("includes/header.php");
			if (!$paged) {OPLong($MYKINGDOMID);}
			else OPLong($MYKINGDOMID, $start, true);
			break;
		case 'short':
			include("includes/header.php");
			if (!$paged) {OPShort($MYKINGDOMID);}
			else OPShort($MYKINGDOMID, $start, true);
			break;
		case 'plain':
			include("includes/printable-header.php");
			if (!$paged) {OPShort($MYKINGDOMID);}
			else OPShort($MYKINGDOMID, $start, true);
			break;
		default:
			include("includes/header.php");
			?><div class="bodyheader"><?php=$WEAREKINGDOM?> Order of Precedence</div><br /><br />
		<p>Long Form: <a href="op.php?style=long&start=0">Paginated</a> | <a href="op.php?style=long">Non-Paginated</a><br />
			Short Form: <a href="op.php?style=short&start=0">Paginated</a> | <a href="op.php?style=short">Non-Paginated</a> | <a href="op.php?style=plain">Plain-style Non-Paginated</a></p>
		<p>Note: The non-paginated forms are long enough that they may trigger a bug in Firefox relating to CSS; portions of the list will be black text on a dark green background. This is not a problem with the site itself, it is a long-standing bug in Firefox itself. If you use Firefox, try the 'plain' version or another browser.</p>
		<?php
		}
*/

	OPLong($Groups, $GroupName);

} else {
	if (isset($_REQUEST['GroupID'])) // but it's not numeric or ALL, then it's an invalid value 	
		$err .= " Invalid value for GroupID. ";
	else { // if it's not set, then display the list of groups 
		$db_query="SELECT GroupID, Name, Active FROM Groups WHERE KingdomID=$MYKINGDOMID ORDER BY Active DESC, Name";
		$results = $db->get_results($db_query);
		
		if ($results) {
		?><p><?php
			foreach($results as $r) { 
				while(list($name,$value) = each($r)) { //name all values
					$$name = $value;
				};
				?><a href="op_group.php?GroupID=<?php=$GroupID?>"><?php=$Name?></a> <?php if ($Active==0) print "(inactive)"?> <br/><?php ; 
			};  //end foreach
			?></p><?php
		}
	}
}

include("includes/footer.php");

function GetSubgroups($db, $GroupID, $subgroups) {

	// get the subordinate groups, if any
	$db_query_child_groups="SELECT GroupID as ChildGroupID FROM Groups WHERE ParentGroupID=$GroupID";
	$results_child_groups = $db->get_results($db_query_child_groups);
	$subgroups = "";
	if ($results_child_groups) {
		foreach($results_child_groups as $r) { 
			while(list($name,$value) = each($r)) { //name all values
				$$name = $value;
				$subgroups .= " OR P.GroupID =$ChildGroupID ";
				$subgroups .= GetSubgroups($db, $ChildGroupID, $subgroups);
			};
		} // end foreach results_child_groups
	} // end if results_child_groups
	return $subgroups;
}// end function GetSubgroups

function OPLong($groups, $GroupName, $start=0, $paged=null){
	global $MYKINGDOMID;
	global $cstring;
	global $op_sql;
	$page = 25;
	$nav = null;
		
	if ($paged) {	
		$header_sql=$op_sql."$groups ) ORDER BY P.SortRank, P.SCAName LIMIT $start, $page";		
		if ($start >= $page){
			$nav="<a href=\"op.php?style=long&amp;start=".($start-$page)."\">&lt;&lt; PREVIOUS</a> | ";
		}
		$nav .= "<a href='op.php?style=long&amp;start=".($start+$page)."'>NEXT &gt;&gt;</a><hr/>";
	} else {
		$header_sql=$op_sql."$groups )  ORDER BY P.SortRank, P.SCAName";
	}

	$head_result = mysql_query($header_sql, $cstring) or die("MySQL Error (header)!! ".mysql_error());
	while ($head[] = mysql_fetch_assoc($head_result));
	array_pop($head);
	
	?><div class="bodyheader"><?php=$GroupName?> Order of Precedence</div>
	<div style="margin-left:10px;"><br/><br/><?php=$nav?> <?php
	foreach ($head as $person){
		$list_result=null;
		$list = null;
		?><strong><a href="individual_record.php?PersonID=<?php=$person[ID]?>"><?php=$person[SCAName]?></a></strong>  (<?php=$person[Group]?>)<?php

		if (isset($person['RegDevice']) && $person['RegName'] == 1) { 
			?> -- Name And Device Registered<br/><?php
		} else if ($person['RegName'] == 1) {
			?> -- Name Registered<br/><?php
		} else {
			?><br/><?php
		}
	
		$award_sql="SELECT A.AwardID, A.Name, Kingdoms.Name AS 'Kingdom', Groups.Name AS 'Group', A.RankID, AG.Date, DATE_FORMAT(AG.Date, '%Y-%m-%d') AS AGDate, AG.AwardedBy, AG.Resigned, AG.Visible, AG.ReignID, AG.Notes, A.GiverType FROM wAwardsGiven AG INNER JOIN wAwards A ON AG.AwardID = A.AwardID LEFT OUTER JOIN Kingdoms ON Kingdoms.KingdomID = A.KingdomID LEFT OUTER JOIN Groups ON Groups.GroupID = A.GroupID WHERE AG.PersonID = $person[ID] ORDER BY A.Rank, AG.Date";

		$list_result = mysql_query($award_sql, $cstring) or die("MySQL Error (List)!! $award_sql ".mysql_error());
		while ($list[] = mysql_fetch_assoc($list_result));
	
		?><table width="520" style="margin-left:2em;"><?php
		foreach ($list as $award){
			if ($award['Visible'] && !$award['Resigned']){
				if ($award['GiverType'] != 'B' && isset($award['AwardedBy'])){
					?><tr><td><?php=$award[Name]?> [<?php=$award[AwardedBy]?>] -- (<?php=$award[Kingdom]?>)</td><td align="right"><?php=$award[AGDate]?></td></tr><?php
				} else if ($award['Kingdom'] != "Outlands"){
					if ($award['Kingdom'] == "SCA"){
						if ($award['AwardID'] == 3 || $award['AwardID'] == 257 || $award['AwardID'] == 7 || $award['AwardID'] == 275){
							?><tr><td><?php=$award[Name]?></td><td align="right"><?php=$award[AGDate]?></td></tr><?php
						} else {
							?><tr><td><?php=$award[Name]?> -- <?php=$award[Notes]?></td><td align="right"><?php=$award[AGDate]?></td></tr><?php
						}
					} else {
						?><tr><td><?php=$award[Name]?> -- (<?php=$award[Kingdom]?>)</td><td align="right"><?php=$award[AGDate]?></td></tr><?php
					}
				} else if (!isset($award['Group'])){
					?><tr><td><?php=$award[Name]?></td><td align="right"><?php=$award[AGDate]?></td></tr><?php
				} else {
					?><tr><td><?php=$award[Name]?> -- (<?php=$award[Group]?>)</td><td align="right"><?php=$award[AGDate]?></td></tr><?php
				}
			}
		}
			?></table><br/>
			<hr/><?php
		}
			echo $nav;
			?></div><?php
}

function OPShort($groups, $GroupName, $start= 0, $paged=null){
	global $MYKINGDOMID;
	global $cstring;
	global $op_sql;
	$page=40;
	$nav = null;
	
	if ($paged){
		//$short_sql = $op_sql."$kingdom GROUP BY P.PersonID ORDER BY Rank, AG.Date, P.SCAName LIMIT $start, $page";
		$short_sql = $op_sql."$groups ) ORDER BY P.SortRank, P.SCAName LIMIT $start, $page";
		
		if ($start >= $page){$nav="<br/><a href=\"op.php?style=short&amp;start=".($start-$page)."\">&lt;&lt; PREVIOUS</a> | ";}
		$nav .= "<a href='op.php?style=short&amp;start=".($start+$page)."'>NEXT &gt;&gt;</a><hr/>";
	}
	
	else {
		//$short_sql = $op_sql."$kingdom GROUP BY P.PersonID ORDER BY Rank, AG.Date, P.SCAName";
		$short_sql = $op_sql."$groups ) ORDER BY P.SortRank, P.SCAName";
	}

	$short_result = mysql_query($short_sql, $cstring) or die("MySQL Error (header)!! ".mysql_error());
	while ($short[] = mysql_fetch_assoc($short_result));
	array_pop($short);
	$i = $start;
	?><div class="bodyheader"><?php=$GroupName?> Order of Precedence</div>
	<div style="margin-left:10px;"><br/><br/><?php=$nav?> <?php
	foreach ($short as $person){
		echo ++$i.")\t<strong><a href=\"individual_record.php?PersonID=$person[ID]\">$person[SCAName]</a></strong>\t($person[Group])";
		if (isset($person['RegDevice']) && $person['RegName'] == 1){echo "\t-- Name &amp; Device<br/><br/>\n";}
		else if ($person['RegName'] == 1){echo "\t-- Name<br/><br/>\n";}
		else echo "<br/><br/>";
	}
	echo $nav."</div>\n";
} //end OPshort



?>
