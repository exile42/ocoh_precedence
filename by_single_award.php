<?
include 'includes/constants.php';
include 'includes/ez_sql.php';
include 'includes/functions.php';
include 'includes/header.php';

if (isset($_REQUEST['AwardID']))
	if (is_numeric($_REQUEST['AwardID']))
		$AwardID = $_REQUEST['AwardID'];
	else
		$err .= " That Honor doesn't exist. ";

if (!is_null($_REQUEST['by']) && ($_REQUEST['by'] == "date" || $_REQUEST['by'] == "alpha"))
	$by = $_REQUEST['by'];
else
	$by = "date";

$db_query_award="SELECT AwardID, RankID, Name, Initials, Stylization, GivenFor, GiverType, GroupID, KingdomID, Blazon, Charter, Notes FROM wAwards WHERE AwardID=$AwardID";

$results_award = $db->get_results($db_query_award);
foreach($results_award as $r) { 
	while(list($name,$value) = each($r)) { //name all values
		$$name = $value;
	};
}

// 9, 10, 11, 26 are Duchy, County, Viscouty, Peerage
if ($RankID == 9 || $RankID == 10 || $RankID == 11 || $RankID == 26)
	$special_limitation = "AND (AG.Notes LIKE '%$WEAREKINGDOM%' OR P.KingdomID=$MYKINGDOMID)";
else
	$special_limitation = "";

if ($by=="date") {
	$sortby="AG.Date, AG.Premier DESC, P.SCAName";
	$switchby="alpha";
	}
elseif ($by=="alpha") {
	$sortby="P.SCAName, AG.Date";
	$switchby="date";
	}
else
	die ("BZZT.  Bad sort value.  Try again");
	
$db_query_recipients="SELECT AG.AwardsGivenID, AG.Date, DATE_FORMAT(AG.Date, '%Y-%m-%d') AS NiceDate, P.PersonID as PersonID, P.SCAName as SCAName, AG.Premier, AG.Reason FROM wAwardsGiven AG INNER JOIN wPeople P ON AG.PersonID = P.PersonID WHERE AG.AwardID=$AwardID AND AG.Visible=1 AND AG.Resigned=0 $special_limitation ORDER BY $sortby";
//echo $db_query_recipients;

$results_recipients = $db->get_results($db_query_recipients);

?>

<div class="bodyheader"><?php echo $Name?> (<?php echo $Initials?>)</div>

<p>The stylization of members of this order is <?php echo $Stylization?>.<br /><br />
Notes on this order: <?php echo $Notes?><br /><br />
Sort: <?php echo $by?> | <a href="by_single_award.php?AwardID=<?php echo $AwardID?>&by=<?php echo $switchby?>"><?php echo $switchby?></a></p>
<table border="0" cellpadding="3" cellspacing="3" width="560">
<?php foreach($results_recipients as $r) { 
	while(list($name,$value) = each($r)) { //name all values
		$$name = $value;
	};

if ($Premier==1) {$p = " (premier)"; } else {$p = "";}
if ($Reason) {$r = " ($Reason)";} else {$r = "";}
	?>
<tr>
<td valign="middle" align="left"><a href="individual_record.php?PersonID=<?php echo $PersonID?>"><?php echo $SCAName?></a> <?php echo $p;?><?php echo $r;?></td>
<td valign="middle" align="right"><?php echo $NiceDate?></td>
</tr>
<?php 
};  //end foreach
?>
</table>


<?
include 'includes/footer.php';
?>