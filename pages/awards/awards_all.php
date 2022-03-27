<?php
include("includes/constants.php");
include("includes/ez_sql.php");
include("includes/functions.php");
include("includes/header.php");
?>

<div class="bodyheader">All Awards and Orders for <?php=$WEAREKINGDOMCOLLECTIVE?></div><br />

<?php // get and print the subordinate groups, if any
/* $db_query_child_groups="SELECT GroupID as ChildGroupID, Name as ChildGroupName FROM Groups WHERE ParentGroupID=$MYGROUPID AND (Designator = \"Barony\" OR Designator = \"Principality\") ";
$results_child_groups = $db->get_results($db_query_child_groups);
if ($results_child_groups) {
	foreach($results_child_groups as $r) { 
		while(list($name,$value) = each($r)) { //name all values
			$$name = $value;
		};
		?> | <a href="awards_group.php?GroupID=<?php=$ChildGroupID?>"><?php=$ChildGroupName?></a> <?php
	}// end foreach
	?> | <a href="awards_group.php?GroupID=ALL">ALL</a> <?php
} //end if 
*/
$GroupID = $MYGROUPID;

// get info about the group
$db_query_group="SELECT Name as ThisIsNotSparta FROM Groups WHERE GroupID=$GroupID";
$results_group = $db->get_results($db_query_group);
foreach($results_group as $r) { 
	while(list($name,$value) = each($r)) { //name all values
		$$name = $value;
	};
} // end foreach


// Get the Grant-bearing awards
$db_query=" SELECT A.AwardID, A.Initials, A.Name, A.ShortFor, R.Rank FROM wAwards as A, wRanks as R WHERE A.RankID=R.RankID AND GroupID=$GroupID AND R.Rank='40' ORDER BY Name";
if ($results = $db->get_results($db_query)) {
	?><center><div style="text-transform:uppercase;">Kingdom Awards bearing a Grant of Arms</div></center>
	<p><?php	
	foreach($results as $r) { 
		while(list($name,$value) = each($r)) { //name all values
			$$name = $value;
		};
		?><a href="by_single_award.php?AwardID=<?php=$AwardID?>"><?php=$Name?></a> (<?php=$Initials?>) <?php
		if ($ShortFor!=NULL) {
			echo $ShortFor;
		}
		?></br><?php
	};  //end foreach
	?></p><?php
}; // end if


// Get the Award-bearing awards
$db_query=" SELECT A.AwardID, A.Initials, A.Name, A.ShortFor, R.Rank FROM wAwards as A, wRanks as R WHERE A.RankID=R.RankID AND GroupID=$GroupID AND (R.Rank='50' OR R.Rank='70') ORDER BY Name";
if ($results = $db->get_results($db_query)) {
	?><center><div style="text-transform:uppercase;">Kingdom Awards bearing an Award of Arms</div></center>
	<p><?php	
	foreach($results as $r) { 
		while(list($name,$value) = each($r)) { //name all values
			$$name = $value;
		};
		?><a href="by_single_award.php?AwardID=<?php=$AwardID?>"><?php=$Name?></a> (<?php=$Initials?>) <?php
		if ($ShortFor!=NULL) {
			echo $ShortFor;
		}
		?></br><?php
		$ShortFor=NULL;
	};  //end foreach
	?></p><?php
}; // end if


// Get the non-Armigerous awards
$db_query=" SELECT A.AwardID, A.Initials, A.Name, A.ShortFor, R.Rank FROM wAwards as A, wRanks as R WHERE A.RankID=R.RankID AND GroupID=$GroupID AND R.Rank='100' ORDER BY Name";
if ($results = $db->get_results($db_query)) {
	?><center><div style="text-transform:uppercase;">Non-Armigerous Kingdom Awards</div></center>
	<p><?php
	foreach($results as $r) { 
		while(list($name,$value) = each($r)) { //name all values
			$$name = $value;
		};
		?><a href="by_single_award.php?AwardID=<?php=$AwardID?>"><?php=$Name?></a> (<?php=$Initials?>) <?php
		if ($ShortFor!=NULL) {
			echo $ShortFor;
		}
		?></br><?php
		$ShortFor=NULL;
	};  //end foreach
	?></p><?php
}; // end if



// Now for the Baronies.  WHEE.

$db_query=" SELECT GroupID as BaronialGroupID, Name as BaronyName, ParentGroupID, Designator, BaronyOrder FROM Groups WHERE ParentGroupID=$GroupID AND Designator='Barony' ORDER BY BaronyOrder ASC";
if ($results = $db->get_results($db_query)) {
	?><center><div style="text-transform:uppercase;">Baronial Awards of the Kingdom of <?php=$WEAREKINGDOMCOLLECTIVE?></div></center><?php
	foreach($results as $r) { 
		while(list($name,$value) = each($r)) { //name all values
			$$name = $value;
		};
		?><p><center><em>Barony of <?php=$BaronyName?></em></center><?php


		// Get the Award-bearing awards
		$db_query=" SELECT A.AwardID, A.Initials, A.Name, A.ShortFor, R.Rank FROM wAwards as A, wRanks as R WHERE A.RankID=R.RankID AND GroupID=$BaronialGroupID AND R.Rank='70' ORDER BY Name";
		if ($results = $db->get_results($db_query)) {
			?><center><em>Armigerous (bearing an Award of Arms)</em></center><?php
			foreach($results as $r) { 
				while(list($name,$value) = each($r)) { //name all values
					$$name = $value;
				};
				?><a href="by_single_award.php?AwardID=<?php=$AwardID?>"><?php=$Name?></a> (<?php=$Initials?>) <?php
				if ($ShortFor!=NULL) {
					echo $ShortFor;
				}
				?></br><?php
				$ShortFor=NULL;
			};  //end foreach armigerous award
		}; // end if armigerous results
		
		
		// Get the non-Armigerous awards
		$db_query=" SELECT A.AwardID, A.Initials, A.Name, A.ShortFor, R.Rank FROM wAwards as A, wRanks as R WHERE A.RankID=R.RankID AND GroupID=$BaronialGroupID AND R.Rank='100' ORDER BY Name";
		if ($results = $db->get_results($db_query)) {
			?><center><em>Non-armigerous</em></center><?php
			foreach($results as $r) { 
				while(list($name,$value) = each($r)) { //name all values
					$$name = $value;
				};
				?><a href="by_single_award.php?AwardID=<?php=$AwardID?>"><?php=$Name?></a> (<?php=$Initials?>) <?php
				if ($ShortFor!=NULL) {
					echo $ShortFor;
				}
				?></br><?php
				$ShortFor=NULL;
			};  //end foreach non-armigerous award
		}; // end if non-armigerous results

	?></p><?php
	}; //end foreach barony

}; // end if barony results

?>

<?php
include("includes/footer.php");
?> 