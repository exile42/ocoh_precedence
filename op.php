<?php
include 'includes/constants.php';
//include 'includes/ez_sql.php';
include 'includes/functions.php';

$cstring = new mysqli($SQLSERVER, $SQLUSERNAME, $SQLPASSWORD, $SQLDATABASE);
if ($cstring->connect_error) {
	die("Connection failed: " . $cstring->connect_error);
	}

$op_sql = "SELECT P.PersonID AS ID, P.SCAName, G.Name AS 'Group', P.RegName, P.RegDevice, P.SortRank from wPeople P, Groups G WHERE P.GroupID=G.GroupID AND P.Active = 1 AND P.KingdomID=";

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
		include 'includes/header.php';
		if (!$paged) {OPLong($MYKINGDOMID);}
		else OPLong($MYKINGDOMID, $start, true);
		break;
	case 'short':
		include 'includes/header.php';
		if (!$paged) {OPShort($MYKINGDOMID);}
		else OPShort($MYKINGDOMID, $start, true);
		break;
	case 'plain':
		include 'includes/printable-header.php';
		if (!$paged) {OPShort($MYKINGDOMID);}
		else OPShort($MYKINGDOMID, $start, true);
		break;
	default:
		include 'includes/header.php';
		?><div class="bodyheader"><?php echo $WEAREKINGDOM; ?> Order of Precedence</div><br /><br />
<p>Long Form: <a href="op.php?style=long&start=0">Paginated</a> | <a href="op.php?style=long">Non-Paginated</a><br />
		Short Form: <a href="op.php?style=short&start=0">Paginated</a> | <a href="op.php?style=short">Non-Paginated</a> | <a href="op.php?style=plain">Plain-style Non-Paginated</a></p>
<!-- <p>Note: The non-paginated forms are long enough that they may trigger a bug in Firefox relating to CSS; portions of the list will be black text on a dark green background. This is not a problem with the site itself, it is a long-standing bug in Firefox itself. If you use Firefox, try the 'plain' version or another browser.</p> -->
<?php
}
$cstring->close();
include 'includes/footer.php';


function OPLong($kingdom, $start=0, $paged=null){
	global $MYKINGDOMID;
	global $cstring;
	global $op_sql;
	$page = $OPPERPAGELONG;
	$nav = null;
		
	if ($paged) {	
		$header_sql="$op_sql $kingdom ORDER BY P.SortRank, P.SCAName LIMIT $start, $page";		
		if ($start >= $page){
			$nav="<a href=\"op.php?style=long&amp;start=".($start-$page)."\">&lt;&lt; PREVIOUS</a> | ";
		}
		$nav .= "<a href='op.php?style=long&amp;start=".($start+$page)."'>NEXT &gt;&gt;</a><hr/>";
	} else {
		$header_sql=$op_sql."$kingdom ORDER BY P.SortRank, P.SCAName";
	}
		
	$head_result = $cstring->query($header_sql) or die("MySQL Error (Long Header)!! ".$cstring->error);
	?><div class="bodyheader">Outlands Order of Precedence</div>
	<div style="margin-left:10px;"><br/><br/><?php echo $nav;?> <?php
	while ($head[] = $head_result->fetch_assoc());
	array_pop($head);
	$i = $start;
	foreach ($head as $person){
		$list_result=null;
		$list = null;
		echo ++$i.")\t<strong><a href=\"individual_record.php?PersonID=$person[ID]\">$person[SCAName]</a></strong>\t($person[Group])";

		if (isset($person['RegDevice']) && $person['RegName'] == 1) { 
			echo " -- Name And Device Registered<br/>";
		} else if ($person['RegName'] == 1) {
			echo " -- Name Registered<br/>";
		} else {
			echo "<br/>";
		}
	
		$award_sql="SELECT A.AwardID, A.Name, Kingdoms.Name AS 'Kingdom', Groups.Name AS 'Group', A.RankID, AG.Date, DATE_FORMAT(AG.Date, '%Y-%m-%d') AS AGDate, AG.AwardedBy, AG.Resigned, AG.Visible, AG.ReignID, AG.Notes, A.GiverType FROM wAwardsGiven AG INNER JOIN wAwards A ON AG.AwardID = A.AwardID LEFT OUTER JOIN Kingdoms ON Kingdoms.KingdomID = A.KingdomID LEFT OUTER JOIN Groups ON Groups.GroupID = A.GroupID WHERE AG.PersonID =$person[ID] ORDER BY A.Rank, AG.Date";

		$list_result = $cstring->query($award_sql) or die("MySQL Error (Long List)!!". $award_sql." ".$cstring->error);
		while ($list[] = $list_result->fetch_assoc());
	
		?><table width="520" style="margin-left:2em;"><?php
		foreach ($list as $award){
			if ($award['Visible'] && !$award['Resigned']){
				if ($award['GiverType'] != 'B' && isset($award['AwardedBy'])){
					?><tr><td><?php echo $award[Name];?> [<?php echo $award[AwardedBy];?>] -- (<?php echo $award[Kingdom];?>)</td><td align="right"><?php echo $award[AGDate];?></td></tr><?php
				} else if ($award['Kingdom'] != "Outlands"){
					if ($award['Kingdom'] == "SCA"){
						if ($award['AwardID'] == 3 || $award['AwardID'] == 257 || $award['AwardID'] == 7 || $award['AwardID'] == 275){
							?><tr><td><?php echo $award[Name];?></td><td align="right"><?php echo $award[AGDate];?></td></tr><?php
						} else {
							?><tr><td><?php echo $award[Name];?> -- <?php echo $award[Notes];?></td><td align="right"><?php echo $award[AGDate];?></td></tr><?php
						}
					} else {
						?><tr><td><?php echo $award[Name] ?> -- (<?php echo $award[Kingdom];?>)</td><td align="right"><?php echo $award[AGDate]; ?></td></tr><?php
					}
				} else if (!isset($award['Group'])){
					?><tr><td><?php echo $award[Name]; ?></td><td align="right"><?php echo $award[AGDate]; ?></td></tr><?php
				} else {
					?><tr><td><?php echo $award[Name]; ?> -- (<?php echo $award[Group]; ?>)</td><td align="right"><?php echo $award[AGDate]; ?></td></tr><?php
				}
			}
		}
			?></table><br/>
			<hr/><?php
		}
			echo $nav;
			?></div><?php
}

function OPShort($kingdom, $start= 0, $paged=null){
	global $MYKINGDOMID;
	global $cstring;
	global $op_sql;
	$page= $OPPERPAGESHORT;
	$nav = null;
	
	if ($paged){
		//$short_sql = $op_sql."$kingdom GROUP BY P.PersonID ORDER BY Rank, AG.Date, P.SCAName LIMIT $start, $page";
		$short_sql = $op_sql."$kingdom ORDER BY P.SortRank, P.SCAName LIMIT $start,$page";
		
		if ($start >= $page){$nav="<br/><a href=\"op.php?style=short&amp;start=".($start-$page)."\">&lt;&lt; PREVIOUS</a> | ";}
		$nav .= "<a href='op.php?style=short&amp;start=".($start+$page)."'>NEXT &gt;&gt;</a><hr/>";
	}
	
	else {
		//$short_sql = $op_sql."$kingdom GROUP BY P.PersonID ORDER BY Rank, AG.Date, P.SCAName";
		$short_sql = $op_sql."$kingdom ORDER BY P.SortRank, P.SCAName";
		//$short_sql = "$op_sql $kingdom ORDER BY P.SortRank, P.SCAName";
	}

	$short_result = $cstring->query($short_sql) or die("MySQL Error (short-header)!! ".$cstring->error);
	while ($short[] = $short_result->fetch_assoc());
	array_pop($short);
	$i = $start;
	echo "<div class=\"bodyheader\">Outlands Order of Precedence</div>\n<div style=\"margin-left:10px; margin-right: 10px;\"><br/><br/>".$nav."\n";
	foreach ($short as $person){
		echo ++$i.")\t<strong><a href=\"individual_record.php?PersonID=".$person[ID]."\">".$person[SCAName]."</a></strong>\t(".$person[Group].")";
		if (isset($person['RegDevice']) && $person['RegName'] == 1){echo "\t-- Name &amp; Device<br/><br/>\n";}
		else if ($person['RegName'] == 1){echo "\t-- Name<br/><br/>\n";}
		else echo "<br/><br/>";
	}
	echo $nav."</div>\n";
} //end OPshort


?>