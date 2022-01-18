<?
include("includes/constants.php");
include("includes/ez_sql.php");
include("includes/functions.php");
include("includes/header.php");
?>

<div class="bodyheader">People of <?=$WEAREKINGDOMCOLLECTIVE?> (By Local Group)</div><br />

<?
	$db_query="SELECT GroupID, Name, Active FROM Groups WHERE KingdomID=$MYKINGDOMID ORDER BY Active DESC, Name";
	$results = $db->get_results($db_query);
	
	if ($results) {
		foreach($results as $r) { 
			while(list($name,$value) = each($r)) { //name all values
				$$name = $value;
			};
			?><p><a href="by_group.php?GroupID=<? echo $GroupID; ?>"><? print "$Name"; ?></a> <? if ($Active==0) print "(inactive)"; ?></p><? 
		};  //end foreach
	}
?>

<?
include("includes/footer.php");
?>