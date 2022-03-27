<?php
include("includes/constants.php");
include("includes/ez_sql.php");
include("includes/functions.php");
include("includes/printable-header.php");

if (!($cstring = mysql_connect($SQLSERVER, $SQLUSERNAME, $SQLPASSWORD))) echo "Unable to Connect to $SQLSERVER ".mysql_error()."<br/>";
if (!mysql_select_db($SQLDATABASE)) echo "Unable to select db $SQLDATABASE ".mysql_error();

$op_sql = "SELECT P.PersonID AS ID, P.SCAName, G.Name AS 'Group', P.RegName, P.RegDevice, P.SortRank from wPeople P, Groups G WHERE P.GroupID=G.GroupID AND P.Active = 1 AND P.KingdomID=";

switch ($_REQUEST['style']){
	case 'long':
		if (!isset($_REQUEST['start'])) {OPLong();}
		else OPLong(17, $_REQUEST['start'], true);
		break;
	case 'short':
		if (!isset($_REQUEST['start'])) {OPShort();}
		else OPShort(17, $_REQUEST['start'], true);
		break;
	default:
		?><div class="bodyheader">Outlands Order of Precedence</div><br /><br />
		<p>Long Form (<a href="op-printable.php?style=long&start=0">Paginated</a> | <a href="op-printable.php?style=long">Non-Paginated)</a><br />
		Short Form (<a href="op-printable.php?style=short&start=0">Paginated</a> | <a href="op-printable.php?style=short">Non-Paginated)</a></p>
		<?php
}

include("includes/footer.php");


function OPLong($kingdom = 17, $start=0, $paged=null){
	global $cstring;
	global $op_sql;
	$page = 25;
	$nav = null;
		
	if ($paged) {	
		$header_sql=$op_sql."$kingdom ORDER BY P.SortRank, P.SCAName LIMIT $start, $page";		
		if ($start >= $page){
			$nav="<a href=\"op.php?style=long&amp;start=".($start-$page)."\">&lt;&lt; PREVIOUS</a> | ";
		}
		$nav .= "<a href='op.php?style=long&amp;start=".($start+$page)."'>NEXT &gt;&gt;</a><hr/>";
	} else {
		$header_sql=$op_sql."$kingdom ORDER BY P.SortRank, P.SCAName";
	}
		
	$head_result = mysql_query($header_sql, $cstring) or die("MySQL Error (header)!! ".mysql_error());
	while ($head[] = mysql_fetch_assoc($head_result));
	array_pop($head);
	
	?><div class="bodyheader">Outlands Order of Precedence</div>
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

function OPShort($kingdom = 17, $start= 0, $paged=null){
	global $cstring;
	global $op_sql;
	$page=40;
	$nav = null;
	
	if ($paged){
		//$short_sql = $op_sql."$kingdom GROUP BY P.PersonID ORDER BY Rank, AG.Date, P.SCAName LIMIT $start, $page";
		$short_sql = $op_sql."$kingdom ORDER BY P.SortRank, P.SCAName LIMIT $start, $page";
		
		if ($start >= $page){$nav="<br/><a href=\"op.php?style=short&amp;start=".($start-$page)."\">&lt;&lt; PREVIOUS</a> | ";}
		$nav .= "<a href='op.php?style=short&amp;start=".($start+$page)."'>NEXT &gt;&gt;</a><hr/>";
	}
	
	else {
		//$short_sql = $op_sql."$kingdom GROUP BY P.PersonID ORDER BY Rank, AG.Date, P.SCAName";
		$short_sql = $op_sql."$kingdom ORDER BY P.SortRank, P.SCAName";
	}

	$short_result = mysql_query($short_sql, $cstring) or die("MySQL Error (header)!! ".mysql_error());
	while ($short[] = mysql_fetch_assoc($short_result));
	array_pop($short);
	$i = $start;
	echo "<div class=\"bodyheader\">Outlands Order of Precedence</div>\n<div style=\"margin-left:10px; margin-right: 10px;\"><br/><br/>".$nav."\n";
	foreach ($short as $person){
		echo ++$i.")\t<strong><a href=\"individual_record.php?PersonID=$person[ID]\">$person[SCAName]</a></strong>\t($person[Group])";
		if (isset($person['RegDevice']) && $person['RegName'] == 1){echo "\t-- Name &amp; Device<br/><br/>\n";}
		else if ($person['RegName'] == 1){echo "\t-- Name<br/><br/>\n";}
		else echo "<br/><br/>";
	}
	echo $nav."</div>\n";
} //end OPshort


?>
