<?
include 'includes/constants.php';
include 'includes/ez_sql.php';
include 'includes/functions.php';
include 'includes/header.php';
?>

<div class="bodyheader">Awards (By Group)</div><br />

<center>
<div style="text-transform:uppercase;">
<a href="awards_group.php?GroupID=<?php echo $MYGROUPID?>"><?php echo $WEAREKINGDOM?></a>

<?php // get and print the subordinate groups, if any
$db_query_child_groups="SELECT GroupID as ChildGroupID, Name as ChildGroupName FROM Groups WHERE ParentGroupID=$MYGROUPID AND (Designator = \"Barony\" OR Designator = \"Principality\") ";
$results_child_groups = $db->get_results($db_query_child_groups);
if ($results_child_groups) {
	foreach($results_child_groups as $r) { 
		while(list($name,$value) = each($r)) { //name all values
			$$name = $value;
		};
		?> | <a href="awards_group.php?GroupID=<?php echo $ChildGroupID?>"><?php echo $ChildGroupName?></a> <?
	}// end foreach
	?> | <a href="awards_group.php?GroupID=ALL">ALL</a> <?
} //end if
?>
</div>
</center>

<?php 		
if (!is_null($_REQUEST[GroupID]) && (is_numeric($_REQUEST[GroupID]) || ($_REQUEST[GroupID]=="ALL")))
	$GroupID = $_REQUEST[GroupID];

if (!$GroupID) {
	?><p>Please click on a group above to navigate the List of Awards.  The listing for <?php echo $WEAREKINGDOMCOLLECTIVE?> is for kingdom awards only and does not include old orders from the Principality days.</p> 
<?
} else {
	// make the query smarter.
	if ($GroupID=="ALL") {
		$GroupID = $MYGROUPID;
		$wherequery="(A.GroupID=$GroupID OR G.ParentGroupID=$GroupID) AND (A.KingdomID=$MYKINGDOMID OR A.KingdomID=$SOCIETYKINGDOMID)";
		?><p>Awards for <?php echo $WEAREKINGDOMCOLLECTIVE?> and the groups within it</p><?
	}else {
		$wherequery = "A.GroupID=$GroupID AND A.KingdomID=$MYKINGDOMID";
		// get info about the group
		$db_query_group="SELECT Name as ThisIsNotSparta FROM Groups WHERE GroupID=$GroupID";
		$results_group = $db->get_results($db_query_group);
		foreach($results_group as $r) { 
			while(list($name,$value) = each($r)) { //name all values
				$$name = $value;
			};
		} // end foreach
		?><p>Awards for <?php echo $ThisIsNotSparta?></p><?
	}
	

	$db_query=" SELECT A.AwardID, G.ParentGroupID, A.Initials, A.Name, A.GivenFor, A.Blazon, A.Charter, A.Filename, A.Notes, R.Description FROM wAwards as A, Groups as G, wRanks as R WHERE $wherequery AND A.GroupID=G.GroupID AND A.RankID=R.RankID AND R.Rank>=40 ORDER BY A.Name";
	$results = $db->get_results($db_query);
	
	foreach($results as $r) { 
	while(list($name,$value) = each($r)) { //name all values
		$$name = $value;
	};
	?>

	<p><b><?php echo $Name?> (<?php echo $Initials?>)</b> (<?php echo $Description?>) 
	<?php 
	if ($Charter != "") { 
		?>(<a href="charters.php?AwardID=<?php echo $AwardID?>" onclick="popUp(this.href,'elastic',810,650); return false;" target="newWin">charter</a>) <?
	}
	if ($Filename != "") { 
		?>(<a href="badges.php?AwardID=<?php echo $AwardID?>" onclick="popUp(this.href,'elastic',450,620); return false;" target="newWin">badge</a>) <?
	}
	if ($GivenFor!=NULL) {
		?> <br />Given for <?php echo $GivenFor;
	}
	if ($Blazon!=NULL) {
		?> <br />Badge/Insignia: <?php echo $Blazon; ?></a><?php 
	}
	if ($Notes!=NULL) {
		?> <br />Notes: <?php echo $Notes; ?></a><br /></p><?php 
	}
	};  //end foreach

}; // end else
?>

<?
include 'includes/footer.php';
?>