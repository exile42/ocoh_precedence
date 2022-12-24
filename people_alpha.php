<?php
include'includes/constants.php';
include'includes/ez_sql.php';
include'includes/functions.php';
include'includes/header.php';
?>

<div class="bodyheader">People of <?php echo $WEAREKINGDOMCOLLECTIVE; ?> (Alphabetically)</div><br />

<center>
<?php WriteAlpha("people_alpha.php"); ?>
</center>

<?php 	
if (isset($_REQUEST['Letter']) && !is_null($_REQUEST['Letter']) && (strlen($_REQUEST['Letter'])==1))
	$Letter = $_REQUEST['Letter'];

if (!isset($Letter)) {
	?><p>Please click on a letter above to navigate the alphabetical list of people in <?php echo $WEAREKINGDOMCOLLECTIVE; ?>.</p><?php	
} else {
	$alpha_query = "SELECT PersonID, SCAName FROM wPeople WHERE SearchName LIKE '$Letter%' OR SearchName LIKE '`$Letter%' ORDER BY SearchName";
	if ($alpha_results = $db->get_results($alpha_query)) {
		?><table border="0" width="560" cellpadding="3" cellspacing="3"><br /><?php
		foreach($alpha_results as $r) {
			$PersonID 	= $r->PersonID;
			$SCAName 	= $r->SCAName;
			
			?><tr><td><a href="individual_record.php?PersonID=<?php echo $PersonID; ?>"><?php echo $SCAName; ?></a></td><?php

			if ($accessWimble =="1") {
				?><td valign="middle" align="right" nowrap><a href="edit_user.php?PersonID=<?php echo $PersonID; ?>&Action=Edit">EDIT</a> |
				<a href="edit_awards.php?PersonID=<?php echo $PersonID; ?>">AWARDS</a> 
				</td><?php
			}
			?></tr><?php
		} // end foreach
		?></table><?php
	} else {
		?><p>There are no people that start with letter <?php echo $Letter; ?>.</p><?php
	}

} // end if Letter

include'includes/footer.php';
?>