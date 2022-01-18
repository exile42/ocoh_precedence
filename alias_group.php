<?
include("includes/constants.php");
include("includes/ez_sql.php");
include("includes/functions.php");
include("includes/header.php");

if (!is_null($_REQUEST[GroupID]))
	$GroupID = $_REQUEST[GroupID];

if (!is_null($_REQUEST[by]))
	$by = $_REQUEST[by];
else
	$by = "alias";

if ($by=="alias") {
	$sortby="a.Alias";
	$switchby="name";
	}
elseif ($by=="name") {
	$sortby="p.SearchName";
	$switchby="alias";
	}
else
	die ("BZZT.  Bad sort value.  Try again");

// get info about the group
$db_query_group="SELECT Name, Notes FROM Groups WHERE GroupID=$GroupID";
$results_group = $db->get_results($db_query_group);
foreach($results_group as $r) { 
	while(list($name,$value) = each($r)) { //name all values
		$$name = $value;
	};
} // end foreach

// get the subordinate groups, if any
$db_query_child_groups="SELECT GroupID as ChildGroupID FROM Groups WHERE ParentGroupID=$GroupID";
$results_child_groups = $db->get_results($db_query_child_groups);
if ($results_child_groups) {
	foreach($results_child_groups as $r) { 
		while(list($name,$value) = each($r)) { //name all values
			$$name = $value;
			$subgroups .= "OR GroupID =$ChildGroupID ";
		};
	} // end foreach
} // end if


?>

<div class="bodyheader">List of People and Possible Alias</div><br />

<center>
<a href="alias.php">Back to alpha/group page</a><br />
</center>

<div class="bodyheader"><? print "$Name"; ?> (including subgroups, if any)</div>
<p>Sort: <? echo $by; ?> | <a href="alias_group.php?GroupID=<? echo $GroupID; ?>&by=<? echo $switchby; ?>"><? echo $switchby?></a></p>

<table border="0" cellpadding="3" cellspacing="3" width="515">
<? 
$db_query="SELECT a.Alias, p.SCAName, p.PersonID FROM wPeople p, wAliases a WHERE p.PersonID = a.PersonID AND (GroupID='$GroupID' $subgroups) ORDER BY $sortby";
$results = $db->get_results($db_query);
if ($results) {
	foreach($results as $r) { 
		while(list($name,$value) = each($r)) { //name all values
			$$name = $value;
		};
		?>
	<tr>
	<? 
	if ($by=="alias") { ?>
		<td align="left"><? echo $Alias; ?></td>
		<td align="right"><a href="individual_record.php?PersonID=<? echo $PersonID ; ?>"><? echo $SCAName;?></a></td>
	<? } else { ?>
		<td align="left"><a href="individual_record.php?PersonID=<? echo $PersonID ; ?>"><? echo $SCAName;?></a></td>
		<td align="right"><? echo $Alias; ?></td>
	<? } ?>
	</tr>
	<? 
	};  //end foreach
} else {
	?><p>Nobody in <?=$Name?> has a known alias.</p><?
}// end if
?>
</table>


<?
include("includes/footer.php");
?>