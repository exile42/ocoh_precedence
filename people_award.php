<?
include("includes/constants.php");
include("includes/ez_sql.php");
include("includes/functions.php");
include("includes/header.php");
?>

<div class="bodyheader">People of <?=$WEAREKINGDOMCOLLECTIVE?>, listed by Award received</div><br />

<center>
<? WriteAlpha("people_award.php"); ?>
<div style="text-transform:uppercase;">
<a href="people_award.php?GroupID=<?=$MYGROUPID?>"><?=$WEAREKINGDOM?></a>

<? // get and print the subordinate groups, if any
$db_query_child_groups="SELECT GroupID as ChildGroupID, Name as ChildGroupName FROM Groups WHERE ParentGroupID=$MYGROUPID AND (Designator = \"Barony\" OR Designator = \"Principality\") ";
$results_child_groups = $db->get_results($db_query_child_groups);
if ($results_child_groups) {
	foreach($results_child_groups as $r) { 
		while(list($name,$value) = each($r)) { //name all values
			$$name = $value;
		};
		?> | <a href="people_award.php?GroupID=<?=$ChildGroupID?>"><?=$ChildGroupName?></a> <?
	}// end foreach
	?> | <a href="people_award.php?GroupID=<?=$SOCIETYGROUPID?>">Society-wide awards</a> | <a href="people_award.php?GroupID=ALL">ALL</a> <?
} //end if
?>
</div>
</center>

<?
if (!is_null($_REQUEST['Letter']) && (strlen($_REQUEST['Letter'])==1))
	$Letter = $_REQUEST['Letter'];
if (!is_null($_REQUEST['GroupID']) && (is_numeric($_REQUEST['GroupID']) || ($_REQUEST['GroupID']=="ALL")))
	$GroupID = $_REQUEST['GroupID'];

if (!isset($Letter) && !isset($GroupID)) {
	?><p>Please click on a letter or group above to navigate the award list.</p><?
} elseif (isset($Letter)) {
	?><br /><div class="bodyheader3">Society and <?=$WEAREKINGDOM?> Awards</div><?
	if ($results = $db->get_results("SELECT AwardID, Name, Initials, Closed, KingdomID 
				FROM wAwards WHERE LEFT( Name, 1 )='$Letter' AND (KingdomID=$MYKINGDOMID OR KingdomID=$SOCIETYKINGDOMID) 
				ORDER BY Closed ASC, Name")) {
		foreach($results as $r) { 
			while(list($name,$value) = each($r)) { //name all values
				$$name = $value;
			};
			?><p><a href="by_single_award.php?AwardID=<?=$AwardID?>"><?=$Name?> (<?=$Initials?>)</a> <? if ($Closed==1) print "(order closed)"; ?></p><? 
		};  //end foreach
	} else {
		?><p>There are no Society or <?=$WEAREKINGDOM?> awards that start with letter <?=strtoupper($Letter)?>.</p><?
	} // end if results
	?><br />

	<div class="bodyheader3">Out-of-Kingdom Awards</div><?
	if($results_ook = $db->get_results("SELECT AwardID, Name, Initials, Closed, KingdomID 
				FROM wAwards WHERE LEFT( Name, 1 )='$Letter' AND KingdomID!=$MYKINGDOMID AND KingdomID!=$SOCIETYKINGDOMID 
				ORDER BY Closed, Name")) {
		foreach($results_ook as $r) { 
			while(list($name,$value) = each($r)) { //name all values
				$$name = $value;
			};
			?><p><a href="by_single_award.php?AwardID=<?=$AwardID?>"><?=$Name?> (<?=$Initials?>)</a> <? if ($Closed==1) print "(order closed)"; ?></p><? 
			};  //end foreach
	} else {
		?><p>There are no out-of-kingdom awards that start with letter <?=strtoupper($Letter)?>.</p><?
	} // end if ook_results
	
} elseif (isset($GroupID)) {
	// make the query smarter.
	if ($GroupID=="ALL") {
		$GroupID = $MYGROUPID;
		$wherequery="(A.GroupID=$GroupID OR A.GroupID=$SOCIETYGROUPID OR G.ParentGroupID=$GroupID) AND (A.KingdomID=$MYKINGDOMID OR A.KingdomID=$SOCIETYKINGDOMID)";
		?><p>Awards for <?=$WEAREKINGDOMCOLLECTIVE?> and the groups within it</p><?
	}else {
		if ($GroupID==$MYGROUPID)
			$wherequery = "(A.GroupID=$GroupID OR A.GroupID=$SOCIETYGROUPID) AND (A.KingdomID=$MYKINGDOMID OR A.KingdomID=$SOCIETYKINGDOMID)";
		else if ($GroupID==$SOCIETYGROUPID)
			$wherequery = "A.GroupID=$GroupID AND A.KingdomID=$SOCIETYKINGDOMID";
		else
			$wherequery = "A.GroupID=$GroupID AND A.KingdomID=$MYKINGDOMID";
		// get info about the group
		$db_query_group="SELECT Name as ThisIsNotSparta FROM Groups WHERE GroupID=$GroupID";
		$results_group = $db->get_results($db_query_group);
		foreach($results_group as $r) { 
			while(list($name,$value) = each($r)) { //name all values
				$$name = $value;
			};
		} // end foreach
		?><p>Awards for <?=$ThisIsNotSparta?></p><?
	}
	
	$db_query=" SELECT A.AwardID, G.ParentGroupID, A.Initials, A.Name, A.Closed FROM wAwards as A, Groups as G WHERE $wherequery AND A.GroupID=G.GroupID ORDER BY A.Name";
	$results = $db->get_results($db_query);
	
	foreach($results as $r) { 
		while(list($name,$value) = each($r)) { //name all values
			$$name = $value;
		};
		?><p><a href="by_single_award.php?AwardID=<?=$AwardID?>"><?=$Name?> (<?=$Initials?>)</a> <? if ($Closed==1) print "(order closed)"; ?></p><? 
	};  //end foreach

}; // end else
?>

<?


include("includes/footer.php");
?>
