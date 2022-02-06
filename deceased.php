<?php
include("includes/constants.php");
include("includes/ez_sql.php");
include("includes/functions.php");
include("includes/header.php");

?>

<div class="bodyheader">A Tribute to those of <?php=$WEAREKINGDOMCOLLECTIVE?> who have passed on</div>
<br />

<dl>
<?php 
$db_query_deceased="SELECT PersonID, SCAName FROM wPeople WHERE Deceased=1 ORDER BY SearchName";
$results_deceased = $db->get_results($db_query_deceased);
if ($results_deceased) {
	foreach($results_deceased as $r) { 
		while(list($name,$value) = each($r)) { //name all values
			$$name = $value;
		}; // end while
		?>
		<dt><p><b><a href="individual_record.php?PersonID=<?php echo $PersonID; ?>"><?php echo $SCAName; ?></a>
		 </b></p></dt><?php
	} // end foreach
} //end if
?>
</dl>


<?php
include("includes/footer.php");
?>