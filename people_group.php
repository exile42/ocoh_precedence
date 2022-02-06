<?php
include("includes/constants.php");
include("includes/ez_sql.php");
include("includes/functions.php");
include("includes/header.php");
?>

<div class="bodyheader">People of <?php=$WEAREKINGDOMCOLLECTIVE?> (By Local Group)</div><br />

<?php
	$db_query="SELECT GroupID, Name, Active FROM Groups WHERE KingdomID=$MYKINGDOMID ORDER BY Active DESC, Name";
	$results = $db->get_results($db_query);
	
	if ($results) {
		foreach($results as $r) { 
			while(list($name,$value) = each($r)) { //name all values
				$$name = $value;
			};
			?><p><a href="by_group.php?GroupID=<?php echo $GroupID; ?>"><?php print "$Name"; ?></a> <?php if ($Active==0) print "(inactive)"; ?></p><?php 
		};  //end foreach
	}
?>

<?php
include("includes/footer.php");
?>