<?php
include 'includes/constants.php';
include 'includes/ez_sql.php';
include 'includes/functions.php';
include 'includes/header.php';

$db_query_award="SELECT ReignID, RPersonID, CPersonID, RUseName, CUseName, RUseTitle, CUseTitle, Start, End, Notes FROM wReigns WHERE GroupID=$MYGROUPID ORDER BY ReignOrder";

$results_award = $db->get_results($db_query_award);

//Note to AM: Earliest reign for which data is known much at all is ReignID=34
?>

<div class="bodyheader">A Tribute to the Kings and Queens of <?php echo $WEAREKINGDOMCOLLECTIVE; ?></div><br />

<dl>
<?php foreach($results_award as $r) { 
	while(list($name,$value) = each($r)) { //name all values
		$$name = $value;
	}; // end while
?>
<dt><p><b><a href="individual_record.php?PersonID=<?php echo $RPersonID; ?>"><?php print "$RUseTitle $RUseName";?></a>
	<?php if (!is_null($CPersonID)) {?>
 and <a href="individual_record.php?PersonID=<?php echo $CPersonID; ?>"><?php print "$CUseTitle $CUseName"; ?></a>
 	<?php } ?>
 </b></p></dt>
<dd><?php print "$Start - $End"; ?></dd>
<?php	if (!is_null($Notes)) { ?>
	<dd><?php echo $Notes; ?></dd>
<?php	} // end if
} // end foreach
?>
</dl>


<?php
include 'includes/footer.php';
?>